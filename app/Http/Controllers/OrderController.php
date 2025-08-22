<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class OrderController extends Controller
{
    // Suppression d'une commande par l'utilisateur (historique)
    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id() || !in_array($order->status, ['cancelled', 'delivered'])) {
            abort(403);
        }
        $order->orderItems()->delete();
        $order->delete();
        return redirect()->route('orders.index')->with('success', "Commande supprimée de l'historique.");
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id());

        // Filtres
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        if (in_array($sort, ['created_at', 'total_amount']) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10)->appends($request->except('page'));

        return view('orders.index', compact('orders'));
    }

    // Annulation de commande par l'utilisateur
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() || !in_array($order->status, ['pending', 'processing'])) {
            abort(403);
        }
        $order->status = 'cancelled';
        $order->save();
        return redirect()->route('orders.index')->with('success', 'Commande annulée avec succès.');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.product.user']);

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cartItems = Cart::with(['product.category', 'product.user'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide!');
        }

        // Vérifier la disponibilité des produits
        foreach ($cartItems as $item) {
            if ($item->product->status !== 'active' || $item->quantity > $item->product->stock_quantity) {
                return redirect()->route('cart.index')->with('error', 'Certains produits ne sont plus disponibles.');
            }
        }

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->current_price;
        });

    // Ajouter le montant de la livraison dynamique
    $shipping_fee = \App\Http\Controllers\Admin\ShippingSettingsController::getGlobalShippingFee();
    $total += $shipping_fee;

    return view('orders.checkout', compact('cartItems', 'total', 'shipping_fee'));
    }

    public function store(Request $request)
    {
        // Nettoyer le numéro de téléphone (supprimer espaces et caractères non numériques)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $request->merge(['phone' => $phone]);
        
        $validationRules = [
            'delivery_address' => 'required|string|max:500',
            'phone' => ['required', 'regex:/^\d{9}$/'],
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:campay,cash_on_delivery',
        ];
        
        // Si paiement mobile, valider le numéro de téléphone pour le paiement
        if ($request->payment_method === 'campay') {
            $validationRules['phone_number'] = 'required|string|min:9';
        }
        
        $request->validate($validationRules, [
            'phone.regex' => 'Le numéro de téléphone doit contenir exactement 9 chiffres.',
            'payment_method.required' => 'Veuillez sélectionner un mode de paiement.',
            'phone_number.required' => 'Veuillez saisir votre numéro de téléphone pour le paiement mobile.'
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide!');
        }

        DB::beginTransaction();

        try {
            // Créer la commande
        // Déterminer le marchand (propriétaire du premier produit du panier)
        $merchantId = null;
        if ($cartItems->count() > 0 && $cartItems[0]->product) {
            $merchantId = $cartItems[0]->product->user_id;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'merchant_id' => $merchantId,
            'order_number' => 'BSM-' . strtoupper(uniqid()),
            'total_amount' => 0, // Sera calculé après
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'campay' ? 'pending' : 'cash_on_delivery',
            'notes' => $request->notes,
            'delivery_address' => $request->delivery_address,
            'phone' => $request->phone
        ]);

            $total = 0;

            // Créer les items de commande
            foreach ($cartItems as $item) {
                // Vérifier encore la disponibilité
                if ($item->product->status !== 'active' || $item->quantity > $item->product->stock_quantity) {
                    throw new \Exception('Produit non disponible: ' . $item->product->name);
                }

                $price = $item->product->current_price;
                $itemTotal = $item->quantity * $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $itemTotal
                ]);

                // Décrémenter le stock
                $item->product->decrement('stock_quantity', $item->quantity);

                $total += $itemTotal;
            }

            // Ajouter le montant de la livraison (récupéré depuis les paramètres admin)
            $shipping_fee = \App\Http\Controllers\Admin\ShippingSettingsController::getGlobalShippingFee();
            $total += $shipping_fee;

            // Mettre à jour le total de la commande
            $order->update(['total_amount' => $total]);

                // Préparer le détail de la facture pour l'email
                $orderItems = $order->orderItems()->with('product')->get();
                $factureDetails = "Détail de la commande :\n";
                foreach ($orderItems as $item) {
                    $factureDetails .= "- " . $item->product->name . " x " . $item->quantity . " = " . number_format($item->total, 2) . " €\n";
                }
                $factureDetails .= "\nMontant total : " . number_format($total, 2) . " €";

                // Notifier le commerçant de la nouvelle commande avec facture
                if ($merchantId) {
                    NotificationService::sendToUser(
                        $merchantId,
                        'Nouvelle commande reçue',
                        "Vous avez reçu une nouvelle commande #{$order->order_number}.\n" . $factureDetails . "\nConsultez vos commandes pour plus de détails.",
                        'success',
                        'shopping-bag',
                        url('/merchant/orders'),
                        'Voir mes commandes',
                        true // Envoyer email
                    );
                }

            // Vider le panier
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Si paiement mobile, rediriger vers la page de paiement
            if ($request->payment_method === 'campay') {
                return redirect()->route('orders.show', $order)
                    ->with([
                        'success' => 'Commande créée avec succès!',
                        'initiate_payment' => true,
                        'payment_phone' => $request->phone_number
                    ]);
            }

            // Sinon, redirection normale pour paiement à la livraison
            return redirect()->route('orders.show', $order)
                ->with('success', 'Commande passée avec succès! Vous paierez à la livraison.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la commande: ' . $e->getMessage());
        }
    }
}
