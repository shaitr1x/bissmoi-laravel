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

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000'
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

            // Mettre à jour le total de la commande
            $order->update(['total_amount' => $total]);

            // Notifier le commerçant de la nouvelle commande
            if ($merchantId) {
                NotificationService::sendToUser(
                    $merchantId,
                    'Nouvelle commande reçue',
                    "Vous avez reçu une nouvelle commande #{$order->order_number} d'un montant de " . number_format($total, 2) . " €. Consultez vos commandes pour plus de détails.",
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

            return redirect()->route('orders.show', $order)->with('success', 'Commande passée avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la commande: ' . $e->getMessage());
        }
    }
}
