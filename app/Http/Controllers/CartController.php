<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Cart::with(['product.category', 'product.user'])
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->current_price;
        });

        // Ajouter le montant de la livraison (3000 FCFA)
        $shipping_fee = 3000;
        $total += $shipping_fee;

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Vérifier que le produit est disponible
        if ($product->status !== 'active' || $product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Ce produit n\'est pas disponible en quantité demandée.');
        }

        // Vérifier si le produit est déjà dans le panier
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Quantité insuffisante en stock.');
            }
            
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier avec succès!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($request->quantity > $cart->product->stock_quantity) {
            return back()->with('error', 'Quantité insuffisante en stock.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantité mise à jour!');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Produit retiré du panier!');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Panier vidé!');
    }

    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
