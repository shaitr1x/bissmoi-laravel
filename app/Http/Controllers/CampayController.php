<?php

namespace App\Http\Controllers;

use App\Services\CampayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class CampayController extends Controller
{
    private $campayService;

    public function __construct(CampayService $campayService)
    {
        $this->campayService = $campayService;
    }

    /**
     * Initier un paiement Campay
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string|min:9',
        ]);

        $order = Order::findOrFail($request->order_id);
        
        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        // Vérifier que la commande n'est pas déjà payée
        if ($order->payment_status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Commande déjà payée'], 400);
        }

        $phoneNumber = $this->formatPhoneNumber($request->phone_number);
        $amount = (int) ($order->total_amount * 100); // Campay utilise les centimes
        $description = "Paiement commande #" . $order->id . " - Bissmoi";
        $externalId = 'order_' . $order->id . '_' . time();

        $result = $this->campayService->initiatePayment($amount, $phoneNumber, $description, $externalId);

        if ($result['success']) {
            // Sauvegarder la référence de paiement dans la commande
            $order->update([
                'payment_reference' => $result['reference'],
                'payment_method' => 'campay',
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'reference' => $result['reference'],
                'ussd_code' => $result['ussd_code'],
                'operator' => $result['operator'],
                'message' => 'Paiement initié avec succès. Composez le code USSD pour confirmer.'
            ]);
        } else {
            Log::error('Échec initiation paiement Campay', [
                'order_id' => $order->id,
                'error' => $result['message']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement: ' . $result['message']
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        if (!$order->payment_reference) {
            return response()->json(['success' => false, 'message' => 'Aucune référence de paiement trouvée'], 400);
        }

        $result = $this->campayService->getPaymentStatus($order->payment_reference);

        if ($result['success']) {
            $status = $result['status'];
            
            // Mettre à jour le statut de la commande selon la réponse
            if ($status === 'SUCCESSFUL') {
                $order->update([
                    'payment_status' => 'completed',
                    'status' => 'confirmed'
                ]);
                
                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'message' => 'Paiement confirmé avec succès!'
                ]);
            } elseif ($status === 'FAILED') {
                $order->update(['payment_status' => 'failed']);
                
                return response()->json([
                    'success' => true,
                    'status' => 'failed',
                    'message' => 'Paiement échoué.'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'message' => 'Paiement en cours de traitement...'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification: ' . $result['message']
            ], 500);
        }
    }

    /**
     * Webhook pour recevoir les notifications de Campay
     */
    public function webhook(Request $request)
    {
        Log::info('Campay Webhook Received', $request->all());

        $reference = $request->input('reference');
        $status = $request->input('status');
        
        if (!$reference) {
            return response()->json(['message' => 'Référence manquante'], 400);
        }

        $order = Order::where('payment_reference', $reference)->first();
        
        if (!$order) {
            Log::warning('Commande introuvable pour la référence Campay: ' . $reference);
            return response()->json(['message' => 'Commande introuvable'], 404);
        }

        if ($status === 'SUCCESSFUL') {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'confirmed'
            ]);
            
            Log::info('Paiement Campay confirmé', [
                'order_id' => $order->id,
                'reference' => $reference
            ]);
            
        } elseif ($status === 'FAILED') {
            $order->update(['payment_status' => 'failed']);
            
            Log::info('Paiement Campay échoué', [
                'order_id' => $order->id,
                'reference' => $reference
            ]);
        }

        return response()->json(['message' => 'Webhook traité']);
    }

    /**
     * Formater le numéro de téléphone pour Campay
     */
    private function formatPhoneNumber($phone)
    {
        // Retirer tous les espaces et caractères spéciaux
        $phone = preg_replace('/[^\d]/', '', $phone);
        
        // Si le numéro commence par 237, le garder tel quel
        if (substr($phone, 0, 3) === '237') {
            return $phone;
        }
        
        // Si le numéro commence par 6, ajouter 237
        if (substr($phone, 0, 1) === '6') {
            return '237' . $phone;
        }
        
        // Sinon, considérer que c'est déjà un format valide
        return $phone;
    }
}
