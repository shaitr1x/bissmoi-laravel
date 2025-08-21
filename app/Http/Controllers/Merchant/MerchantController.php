<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MerchantVerificationRequest;
use App\Models\AdminNotification;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware(['auth', 'merchant']);
    }

    /**
     * Affiche la page dédiée au formulaire de demande de badge de vérification
     */
    public function showVerificationRequestForm()
    {
        return view('merchant.verification-request');
    }
    /**
     * Soumettre une demande de vérification de marchand
     */
    public function verificationRequest(Request $request)
    {
        $user = Auth::user();
        // Vérifier si une demande en attente existe déjà
        $existing = MerchantVerificationRequest::where('user_id', $user->id)->where('status', 'pending')->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande en attente.');
        }
        MerchantVerificationRequest::create([
            'user_id' => $user->id,
            'message' => $request->input('message'),
            'status' => 'pending',
            'business_phone' => $request->input('business_phone'),
            'has_physical_office' => $request->input('has_physical_office'),
            'office_address' => $request->input('has_physical_office') ? $request->input('office_address') : null,
            'website_or_social' => $request->input('website_or_social'),
            'business_description' => $request->input('business_description'),
            'business_experience' => $request->input('business_experience'),
        ]);
            // Notifier l'admin
            AdminNotification::create([
                'title' => 'Nouvelle demande de vérification',
                'message' => 'Le marchand ' . $user->name . ' a soumis une demande de badge de vérification.',
                'type' => 'merchant_verification',
            ]);
        // TODO: Notifier l'admin ici si besoin
        return redirect()->back()->with('success', 'Votre demande de vérification a été envoyée à l\'administrateur.');
    }
    /**
     * Affiche l'historique des commandes livrées du marchand
     */
    public function orderHistory()
    {
        $merchant = auth()->user();
        $orders = Order::with(['user', 'items.product'])
            ->where('merchant_id', $merchant->id)
            ->where('status', 'delivered')
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('merchant.orders-history', compact('orders'));
    }
    /**
     * Marquer une commande comme expédiée
     */
    public function shipOrder(Request $request, $orderId)
    {
        $merchant = Auth::user();
        $order = Order::with('items.product')->findOrFail($orderId);

        // Vérifier que la commande contient au moins un produit du marchand
        $hasProduct = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id === $merchant->id) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            abort(403, 'Vous ne pouvez expédier que vos propres commandes.');
        }

        // Mettre à jour le statut de la commande
        if ($order->status === 'processing') {
            $order->status = 'shipped';
            $order->save();
            // Notifier le client
            $this->notificationService->sendToUser(
                $order->user_id,
                'Commande expédiée',
                "Votre commande #{$order->order_number} a été expédiée. Elle est en cours de livraison.",
                'info',
                'truck',
                null,
                true // Envoyer email
            );
            return redirect()->back()->with('success', 'Commande marquée comme expédiée.');
        }
        return redirect()->back()->with('error', 'La commande doit être en cours de traitement pour être expédiée.');
    }

    /**
     * Marquer une commande comme livrée
     */
    public function deliverOrder(Request $request, $orderId)
    {
        $merchant = Auth::user();
        $order = Order::with('items.product')->findOrFail($orderId);

        // Vérifier que la commande contient au moins un produit du marchand
        $hasProduct = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id === $merchant->id) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            abort(403, 'Vous ne pouvez livrer que vos propres commandes.');
        }

        // Mettre à jour le statut de la commande
        if ($order->status === 'shipped') {
            $order->status = 'delivered';
            $order->save();
            // Notifier le client
            $this->notificationService->sendToUser(
                $order->user_id,
                'Commande livrée',
                "Votre commande #{$order->order_number} a été livrée. Merci pour votre confiance !",
                'success',
                'package',
                null,
                true // Envoyer email
            );
            return redirect()->back()->with('success', 'Commande marquée comme livrée.');
        }
        return redirect()->back()->with('error', 'La commande doit être expédiée pour être livrée.');
    }

    public function dashboard()
    {
        $merchant = Auth::user();
        if (!$merchant->merchant_approved) {
            return view('merchant.pending');
        }
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

        $images = [];
        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $images[] = 'images/products/' . $imageName;
        }

        // Gestion des images supplémentaires

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('images/products'), $imageName);
                $images[] = 'images/products/' . $imageName;
            }
        }
        if (!empty($images)) {
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

        // Récupérer les images existantes
        $images = $product->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }
        if (!is_array($images)) {
            $images = [];
        }

        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image principale si elle existe
            if (count($images) > 0) {
                $mainImage = $images[0];
                if ($mainImage && file_exists(public_path($mainImage))) {
                    unlink(public_path($mainImage));
                }
                array_shift($images); // On retire l'ancienne image principale
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            array_unshift($images, 'images/products/' . $imageName);
        }

        // Gestion des images supplémentaires
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('images/products'), $imageName);
                $images[] = 'images/products/' . $imageName;
            }
        }

        // Toujours encoder en JSON avant sauvegarde
        $product->images = json_encode($images);

        $product->save();

        return redirect()->route('merchant.products.index')->with('success', 'Produit mis à jour avec succès! Il sera visible après approbation par l\'administrateur.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Supprimer les images
        $images = $product->images;
        if (is_array($images)) {
            foreach ($images as $image) {
                if ($image && file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $product->delete();

        return redirect()->route('merchant.products.index')->with('success', 'Produit supprimé avec succès!');
    }

    public function orders()
    {
        $merchant = Auth::user();
        $status = request('status');
        $search = request('search');

        $ordersQuery = Order::with(['user', 'items.product'])
            ->whereHas('items.product', function($q) use ($merchant) {
                $q->where('user_id', $merchant->id);
            });

        if ($status && in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            $ordersQuery->where('status', $status);
        }
        if ($search) {
            $ordersQuery->where('order_number', 'like', "%$search%");
        }

        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(15);

        // Calculer le revenu total
        $totalRevenue = OrderItem::whereHas('product', function($q) use ($merchant) {
            $q->where('user_id', $merchant->id);
        })->whereHas('order', function($q) {
            $q->whereIn('status', ['delivered', 'shipped', 'processing']);
        })->sum(\DB::raw('price * quantity'));

        return view('merchant.orders', compact('orders', 'totalRevenue'));
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
            'shop_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'merchant_phone' => 'nullable|string|max:20',
            'merchant_address' => 'nullable|string|max:500',
            'merchant_description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->update($request->only([
            'name',
            'shop_name',
            'email',
            'merchant_phone',
            'merchant_address',
            'merchant_description',
        ]));

        return back()->with('success', 'Profil mis à jour avec succès!');
    }
    /**
     * Valider une commande (changer le statut en 'processing')
     */
    public function validateOrder(Request $request, $orderId)
    {
        $merchant = Auth::user();
        $order = Order::with('items.product')->findOrFail($orderId);

        // Vérifier que la commande contient au moins un produit du marchand
        $hasProduct = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id === $merchant->id) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            abort(403, 'Vous ne pouvez valider que vos propres commandes.');
        }

        // Mettre à jour le statut de la commande
        $order->status = 'processing';
        $order->save();

        // Notifier le client
        $this->notificationService->sendToUser(
            $order->user_id,
            'Commande validée',
            "Votre commande #{$order->order_number} a été validée par le commerçant. Elle est en cours de préparation.",
            'success',
            'check-circle',
            null,
            true // Envoyer email
        );

        return redirect()->back()->with('success', 'Commande validée avec succès.');
    }
    /**
     * Retourne les détails d'une commande pour affichage AJAX (modal)
     */
    public function orderDetails(Request $request, $orderId)
    {
        $merchant = Auth::user();
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);
        // Vérifier que la commande appartient au marchand
        $hasProduct = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id === $merchant->id) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            abort(403, 'Vous ne pouvez voir que vos propres commandes.');
        }
        // Générer le HTML des détails (simple, à améliorer selon besoin)
        $html = view('merchant.partials.order-details', compact('order'))->render();
        return response()->json(['html' => $html]);
    }
    /**
     * Annuler une commande (changer le statut en 'cancelled')
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $merchant = Auth::user();
        $order = Order::with('items.product')->findOrFail($orderId);
        // Vérifier que la commande contient au moins un produit du marchand
        $hasProduct = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id === $merchant->id) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            abort(403, 'Vous ne pouvez annuler que vos propres commandes.');
        }
        // Mettre à jour le statut de la commande
        $order->status = 'cancelled';
        $order->save();
        return redirect()->back()->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Permet au marchand de demander la vérification (badge)
     */
    public function requestVerification(Request $request)
    {
        $merchant = Auth::user();
        
        if ($merchant->merchant_approved) {
            return back()->with('info', 'Vous êtes déjà vérifié.');
        }

        // Créer une notification admin
        \App\Models\AdminNotification::createNotification(
            'Demande de vérification',
            "Le marchand {$merchant->name} a demandé la vérification.",
            'info',
            'badge-check'
        );

        return back()->with('success', 'Votre demande de vérification a bien été envoyée à l\'administration.');
    }
}
