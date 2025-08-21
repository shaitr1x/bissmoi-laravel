<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Confirmer un produit
    public function confirm(Product $product)
    {
        $product->status = 'active';
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Produit confirmé et publié !');
    }

    // Rejeter un produit
    public function reject(Product $product)
    {
        $product->status = 'inactive';
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Produit rejeté et désactivé !');
    }
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $query = Product::with(['user', 'category']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('name', 'like', "%$search%");
        }
        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request()->filled('merchant')) {
            $merchant = request('merchant');
            $query->whereHas('user', function($q) use ($merchant) {
                $q->where('name', 'like', "%$merchant%");
            });
        }
        if (request()->filled('shop_name')) {
            $shopName = request('shop_name');
            $query->whereHas('user', function($q) use ($shopName) {
                $q->where('shop_name', 'like', "%$shopName%");
            });
        }
        if (request()->filled('category')) {
            $catId = request('category');
            $query->where('category_id', $catId);
        }
        if (request()->filled('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request()->filled('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }
        // Tri
        $sort = request('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(15)->appends(request()->except('page'));
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

        $data = $request->only('status');
        $data['featured'] = $request->has('featured') ? true : false;
        $product->update($data);

        return back()->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy(Product $product)
    {
        $images = $product->images;
        if (is_array($images)) {
            foreach ($images as $img) {
                $imgPath = public_path($img);
                if (file_exists($imgPath)) {
                    @unlink($imgPath);
                }
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès et images nettoyées!');
    }
}
