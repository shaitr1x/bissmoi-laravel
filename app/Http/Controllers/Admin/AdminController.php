<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\AdminNotification;
use App\Models\MerchantVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Marquer toutes les notifications admin comme lues
     */
    public function markAllNotificationsAsRead()
    {
        AdminNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return redirect()->route('admin.notifications.index')->with('success', 'Toutes les notifications ont été marquées comme lues !');
    }
    // Afficher les demandes de vérification des marchands
    public function merchantVerificationRequests()
    {
        $requests = MerchantVerificationRequest::with('user')->orderByDesc('created_at')->get();
        return view('admin.merchant_verification_requests', compact('requests'));
    }

    // Approuver une demande de vérification
    public function approveMerchantVerification($id)
    {
        $request = MerchantVerificationRequest::findOrFail($id);
        $user = $request->user;
        $user->is_verified_merchant = true;
        $user->save();
        $request->status = 'approved';
        $request->save();
        // Optionnel: notifier le commerçant
        return redirect()->back()->with('success', 'Le marchand a été vérifié.');
    }

    // Rejeter une demande de vérification
    public function rejectMerchantVerification($id)
    {
        $request = MerchantVerificationRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        // Optionnel: notifier le commerçant
        return redirect()->back()->with('success', 'La demande a été rejetée.');
    }
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Statistiques de base
        $stats = [
            'total_users' => User::count(),
            'total_merchants' => User::where('role', 'merchant')->count(),
            'pending_merchants' => User::where('role', 'merchant')->where('merchant_approved', false)->count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
        ];

        // Données des derniers 30 jours
        $last30Days = Carbon::now()->subDays(30);
        
        // Évolution des inscriptions
        $userRegistrations = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $last30Days)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Évolution des ventes
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders_count'),
            DB::raw('SUM(total_amount) as total_sales')
        )
        ->where('created_at', '>=', $last30Days)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top catégories
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        // Top commerçants
        $topMerchants = User::where('role', 'merchant')
            ->where('merchant_approved', true)
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        // Activités récentes
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $pendingProducts = Product::with(['user', 'category'])->where('status', 'pending')->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        // Notifications non lues
        $notifications = AdminNotification::unread()->latest()->take(5)->get();
        $unreadNotificationsCount = AdminNotification::unread()->count();

        return view('admin.dashboard', compact(
            'stats',
            'userRegistrations',
            'salesData',
            'topCategories',
            'topMerchants',
            'recentOrders',
            'pendingProducts',
            'recentUsers',
            'notifications',
            'unreadNotificationsCount'
        ));
    }

    public function merchants(Request $request)
    {
        $query = User::where('role', 'merchant')->with('products');

        // Filtrage par statut
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('merchant_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('merchant_approved', false);
            }
        }

        // Filtrage par badge vérifié
        if ($request->has('verified') && $request->verified == '1') {
            $query->where('is_verified_merchant', true);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $merchants = $query->paginate(15);
        return view('admin.merchants', compact('merchants'));
    }

    public function approveMerchant(User $merchant)
    {
        $merchant->update(['merchant_approved' => true]);
        
        // Créer une notification
        AdminNotification::createNotification(
            'Commerçant approuvé',
            "Le commerçant {$merchant->name} a été approuvé.",
            'success',
            'check-circle'
        );

        return back()->with('success', 'Commerçant approuvé avec succès!');
    }

    public function rejectMerchant(User $merchant)
    {
        $merchant->update(['merchant_approved' => false]);
        
        // Créer une notification
        AdminNotification::createNotification(
            'Commerçant rejeté',
            "L'approbation du commerçant {$merchant->name} a été révoquée.",
            'warning',
            'x-circle'
        );

        return back()->with('success', 'Approbation du commerçant révoquée!');
    }

    // Nouvelle méthode pour les statistiques avancées
    public function analytics()
    {
        // Revenus par mois (année en cours)
        $revenue_by_month = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Croissance utilisateurs (90 jours)
        $user_growth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(90))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top produits vendus
        $top_selling_products = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        // Produits par catégorie
        $products_by_category = Category::withCount('products')->get();

        $data = [
            'revenue_by_month' => $revenue_by_month,
            'user_growth' => $user_growth,
            'top_selling_products' => $top_selling_products,
            'products_by_category' => $products_by_category,
        ];

        return view('admin.analytics', compact('data'));
    }

    // Méthodes d'assistance pour les analytics
    private function getRevenueByMonth()
    {
        return Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subYear())
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
    }

    private function getProductsByCategory()
    {
        return Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();
    }

    private function getUserGrowth()
    {
        return User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(90))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    private function getTopSellingProducts()
    {
        return Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();
    }

    // Gestion des utilisateurs
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['products', 'orders']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        AdminNotification::createNotification(
            'Statut utilisateur modifié',
            "L'utilisateur {$user->name} a été {$status}.",
            $user->is_active ? 'success' : 'warning'
        );

        return back()->with('success', "Statut de l'utilisateur mis à jour avec succès!");
    }

    // Gestion des commandes
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);
        
        AdminNotification::createNotification(
            'Statut de commande modifié',
            "La commande #{$order->id} est passée de '{$oldStatus}' à '{$request->status}'.",
            'info'
        );

        return back()->with('success', 'Statut de la commande mis à jour avec succès!');
    }

    // Gestion des notifications
    public function notifications()
    {
        $notifications = AdminNotification::latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead(AdminNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notification marquée comme lue!');
    }

    public function deleteNotification(AdminNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification supprimée!');
    }

    // Gestion des avis
    public function reviews()
    {
        $reviews = \App\Models\Review::with(['user', 'product'])
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approveReview(\App\Models\Review $review)
    {
        $review->update([
            'approved' => true,
            'approved_at' => now()
        ]);

        AdminNotification::createNotification(
            'Avis approuvé',
            "L'avis de {$review->user->name} pour {$review->product->name} a été approuvé.",
            'success'
        );

        return back()->with('success', 'Avis approuvé avec succès!');
    }

    public function rejectReview(\App\Models\Review $review)
    {
        $review->delete();

        AdminNotification::createNotification(
            'Avis supprimé',
            "L'avis de {$review->user->name} pour {$review->product->name} a été supprimé.",
            'warning'
        );

        return back()->with('success', 'Avis supprimé avec succès!');
    }
    /**
     * Vérifier un commerçant (ajouter le badge)
     */
    public function verifyMerchant(User $merchant)
    {
        $merchant->update(['is_verified_merchant' => true]);
        AdminNotification::createNotification(
            'Badge vérifié accordé',
            "Le badge vérifié a été accordé à {$merchant->name}.",
            'success',
            'badge-check'
        );
        return back()->with('success', 'Badge vérifié accordé au commerçant !');
    }

    /**
     * Retirer le badge vérifié d'un commerçant
     */
    public function unverifyMerchant(User $merchant)
    {
        $merchant->update(['is_verified_merchant' => false]);
        AdminNotification::createNotification(
            'Badge vérifié retiré',
            "Le badge vérifié a été retiré à {$merchant->name}.",
            'warning',
            'badge-off'
        );
        return back()->with('success', 'Badge vérifié retiré au commerçant !');
    }
}
