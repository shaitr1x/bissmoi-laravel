<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0);

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtrage par ville (via l'utilisateur/commerçant)
        if ($request->filled('city')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtrage par prix
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active' || $product->stock_quantity <= 0) {
            abort(404);
        }

        $product->load(['category', 'user', 'reviews.user']);
        
        // Produits similaires
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('products.index');
        }

        $products = Product::with(['category', 'user'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            });

        // Filtrage par ville si spécifié
        if ($request->filled('city')) {
            $products->whereHas('user', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $products = $products->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::withCount('products')->get();

        return view('products.search', compact('products', 'categories', 'query'));
    }
}
