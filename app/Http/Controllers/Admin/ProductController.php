<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $products = Product::with(['user', 'category'])->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'reviews']);
        return view('admin.products.show', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,pending',
        ]);

        $product->update($request->only('status'));

        return back()->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès!');
    }
}
