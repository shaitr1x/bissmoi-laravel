<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'user', 'reviews'])
            ->take(8)
            ->get();

        $categories = Category::active()
            ->parents()
            ->withCount('products')
            ->take(6)
            ->get();

        $latestProducts = Product::active()
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'user', 'reviews'])
            ->latest()
            ->take(12)
            ->get();

        return view('welcome', compact('featuredProducts', 'categories', 'latestProducts'));
    }

    public function products(Request $request)
    {
        $query = Product::active()->with(['category', 'user', 'reviews']);

        // Filtres
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderByDesc('price');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(16);
        $categories = Category::active()->parents()->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'user', 'reviews.user']);
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
