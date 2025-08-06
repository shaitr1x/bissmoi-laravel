<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
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
            return $item->quantity * $item->product->getCurrentPrice();
        });

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
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
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => 0, // Sera calculé après
                'status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'phone' => $request->phone,
                'notes' => $request->notes
            ]);

            $total = 0;

            // Créer les items de commande
            foreach ($cartItems as $item) {
                // Vérifier encore la disponibilité
                if ($item->product->status !== 'active' || $item->quantity > $item->product->stock_quantity) {
                    throw new \Exception('Produit non disponible: ' . $item->product->name);
                }

                $price = $item->product->getCurrentPrice();
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
            $order->update(['total' => $total]);

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
