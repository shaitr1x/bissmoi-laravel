<?php

namespace App\Http\Controllers;

use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class CinetPayController extends Controller
{
    private $cinetPayService;

    public function __construct(CinetPayService $cinetPayService)
    {
        $this->cinetPayService = $cinetPayService;
    }

    /**
     * Initier un paiement CinetPay
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $user = Auth::user();

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Commande non autorisée'
            ], 403);
        }

        // Vérifier que la commande n'est pas déjà payée
        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'error' => 'Cette commande est déjà payée'
            ], 400);
        }

        // Récupérer les détails de paiement depuis l'ordre
        $paymentDetails = json_decode($order->payment_details ?? '{}', true);
        $cinetpayMethod = $paymentDetails['cinetpay_method'] ?? null;
        $paymentPhone = $paymentDetails['phone_number'] ?? null;

        // Calculer le montant total (produits + frais de livraison)
        $totalAmount = $order->total_amount + $order->shipping_fee;

        // Initier le paiement via CinetPay
        $paymentResult = $this->cinetPayService->initiatePayment(
            $order->id,
            $totalAmount,
            $user->name,
            $user->phone ?? '00000000',
            $user->email,
            "Commande #{$order->id} sur BISSMOI",
            $cinetpayMethod,
            $paymentPhone
        );

        if (!$paymentResult['success']) {
            Log::error('CinetPay payment initiation failed', [
                'order_id' => $order->id,
                'error' => $paymentResult['error'],
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'error' => $paymentResult['error']
            ], 500);
        }

        // Mettre à jour la commande avec les infos de paiement
        $order->update([
            'payment_method' => 'cinetpay',
            'payment_reference' => $paymentResult['transaction_id'],
            'payment_status' => 'pending'
        ]);

        Log::info('CinetPay payment initiated successfully', [
            'order_id' => $order->id,
            'transaction_id' => $paymentResult['transaction_id'],
            'payment_url' => $paymentResult['payment_url'],
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'payment_url' => $paymentResult['payment_url'],
            'transaction_id' => $paymentResult['transaction_id'],
            'message' => 'Paiement initialisé avec succès'
        ]);
    }

    /**
     * Page de retour après paiement
     */
    public function paymentReturn(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $token = $request->get('token');

        if (!$transactionId) {
            return redirect()->route('orders.index')->with('error', 'Transaction non trouvée');
        }

        // Vérifier le statut de la transaction
        $statusResult = $this->cinetPayService->checkTransactionStatus($transactionId);

        if (!$statusResult['success']) {
            Log::error('CinetPay status check failed on return', [
                'transaction_id' => $transactionId,
                'error' => $statusResult['error']
            ]);

            return redirect()->route('orders.index')->with('error', 'Erreur lors de la vérification du paiement');
        }

        // Extraire l'ID de commande du transaction_id
        $orderIdMatch = [];
        if (preg_match('/BSM_(\d+)_/', $transactionId, $orderIdMatch)) {
            $orderId = $orderIdMatch[1];
            $order = Order::find($orderId);

            if ($order) {
                $status = $statusResult['status'];
                
                if ($status === 'ACCEPTED' || $status === 'COMPLETED') {
                    // Paiement réussi
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'completed',
                        'paid_at' => now()
                    ]);

                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Paiement effectué avec succès !');
                } else {
                    // Paiement échoué ou en attente
                    $order->update([
                        'payment_status' => strtolower($status)
                    ]);

                    $message = $status === 'REFUSED' ? 'Paiement refusé' : 'Paiement en cours de traitement';
                    return redirect()->route('orders.show', $order->id)
                        ->with('warning', $message);
                }
            }
        }

        return redirect()->route('orders.index')->with('error', 'Commande non trouvée');
    }

    /**
     * Webhook de notification CinetPay
     */
    public function paymentNotify(Request $request)
    {
        Log::info('CinetPay webhook received', $request->all());

        $data = $request->all();
        $signature = $request->header('X-CinetPay-Signature');

        // Valider la signature (optionnel selon CinetPay)
        if ($signature && !$this->cinetPayService->validateWebhookSignature($data, $signature)) {
            Log::warning('CinetPay webhook signature invalid');
            return response('Invalid signature', 401);
        }

        $transactionId = $data['cpm_trans_id'] ?? $data['transaction_id'] ?? null;
        $status = $data['cpm_result'] ?? $data['status'] ?? null;

        if (!$transactionId || !$status) {
            Log::warning('CinetPay webhook missing required fields', $data);
            return response('Missing required fields', 400);
        }

        // Extraire l'ID de commande
        $orderIdMatch = [];
        if (preg_match('/BSM_(\d+)_/', $transactionId, $orderIdMatch)) {
            $orderId = $orderIdMatch[1];
            $order = Order::find($orderId);

            if (!$order) {
                Log::warning('CinetPay webhook: order not found', [
                    'transaction_id' => $transactionId,
                    'order_id' => $orderId
                ]);
                return response('Order not found', 404);
            }

            // Traiter selon le statut
            if ($status === '00' || $status === 'ACCEPTED' || $status === 'COMPLETED') {
                // Paiement réussi
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'completed',
                    'paid_at' => now()
                ]);

                Log::info('CinetPay payment completed', [
                    'order_id' => $order->id,
                    'transaction_id' => $transactionId
                ]);
            } else {
                // Paiement échoué
                $order->update([
                    'payment_status' => 'failed'
                ]);

                Log::info('CinetPay payment failed', [
                    'order_id' => $order->id,
                    'transaction_id' => $transactionId,
                    'status' => $status
                ]);
            }

            return response('OK', 200);
        }

        Log::warning('CinetPay webhook: invalid transaction ID format', [
            'transaction_id' => $transactionId
        ]);

        return response('Invalid transaction ID', 400);
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);
        $user = Auth::user();

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Commande non autorisée'
            ], 403);
        }

        if (!$order->payment_reference) {
            return response()->json([
                'success' => false,
                'error' => 'Aucune référence de paiement trouvée'
            ], 400);
        }

        // Vérifier le statut via CinetPay
        $statusResult = $this->cinetPayService->checkTransactionStatus($order->payment_reference);

        if (!$statusResult['success']) {
            return response()->json([
                'success' => false,
                'error' => $statusResult['error']
            ], 500);
        }

        $status = $statusResult['status'];

        // Mettre à jour le statut local si nécessaire
        if ($status === 'ACCEPTED' || $status === 'COMPLETED') {
            if ($order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'completed',
                    'paid_at' => now()
                ]);
            }
        } elseif ($status === 'REFUSED' || $status === 'FAILED') {
            $order->update([
                'payment_status' => 'failed'
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'order_status' => $order->fresh()->status
        ]);
    }
}
