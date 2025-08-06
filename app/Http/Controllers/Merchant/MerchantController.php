<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'merchant']);
    }

    public function dashboard()
    {
        $merchant = Auth::user();
        
        $stats = [
            'total_products' => $merchant->products()->count(),
            'active_products' => $merchant->products()->where('status', 'active')->count(),
            'pending_products' => $merchant->products()->where('status', 'pending')->count(),
            'total_orders' => OrderItem::whereHas('product', function($q) use ($merchant) {
                $q->where('user_id', $merchant->id);
            })->count(),
            'monthly_revenue' => OrderItem::whereHas('product', function($q) use ($merchant) {
                $q->where('user_id', $merchant->id);
            })->whereHas('order', function($q) {
                $q->where('created_at', '>=', now()->startOfMonth());
            })->sum('total'),
            'recent_orders' => OrderItem::with(['order.user', 'product'])
                ->whereHas('product', function($q) use ($merchant) {
                    $q->where('user_id', $merchant->id);
                })->latest()->take(5)->get(),
        ];

        return view('merchant.dashboard', compact('stats'));
    }

    public function products()
    {
        $products = Auth::user()->products()->with('category')->paginate(12);
        return view('merchant.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('merchant.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product($request->all());
        $product->user_id = Auth::id();
        $product->slug = Str::slug($request->name);
        $product->status = 'pending'; // Les produits doivent être approuvés par l'admin
        
        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = 'images/products/' . $imageName;
        }

        // Gestion des images supplémentaires
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('images/products'), $imageName);
                $images[] = 'images/products/' . $imageName;
            }
            $product->images = json_encode($images);
        }

        $product->save();

        return redirect()->route('merchant.products.index')->with('success', 'Produit créé avec succès! Il sera visible après approbation par l\'administrateur.');
    }

    public function showProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('merchant.products.show', compact('product'));
    }

    public function editProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        
        $categories = Category::all();
        return view('merchant.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->fill($request->all());
        $product->slug = Str::slug($request->name);
        
        // Si le produit était actif et qu'on le modifie, on le remet en pending
        if ($product->status === 'active') {
            $product->status = 'pending';
        }
        
        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = 'images/products/' . $imageName;
        }

        // Gestion des images supplémentaires
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images si elles existent
            if ($product->images) {
                $oldImages = json_decode($product->images, true);
                foreach ($oldImages as $oldImage) {
                    if (file_exists(public_path($oldImage))) {
                        unlink(public_path($oldImage));
                    }
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('images/products'), $imageName);
                $images[] = 'images/products/' . $imageName;
            }
            $product->images = json_encode($images);
        }

        $product->save();

        return redirect()->route('merchant.products.index')->with('success', 'Produit mis à jour avec succès! Il sera visible après approbation par l\'administrateur.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Supprimer les images
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        if ($product->images) {
            $images = json_decode($product->images, true);
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $product->delete();

        return redirect()->route('merchant.products.index')->with('success', 'Produit supprimé avec succès!');
    }

    public function orders()
    {
        $orders = OrderItem::with(['order.user', 'product'])
            ->whereHas('product', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->paginate(15);

        return view('merchant.orders', compact('orders'));
    }

    public function profile()
    {
        $merchant = Auth::user();
        return view('merchant.profile', compact('merchant'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'merchant_phone' => 'nullable|string|max:20',
            'merchant_address' => 'nullable|string|max:500',
            'merchant_description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->update($request->all());

        return back()->with('success', 'Profil mis à jour avec succès!');
    }
}
