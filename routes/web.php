<?php
// Marquer une notification comme lue (client)
Route::middleware('auth')->patch('/mes-notifications/{notification}/read', function ($notificationId) {
    $notification = \App\Models\UserNotification::where('id', $notificationId)
        ->where('user_id', Auth::id())
        ->firstOrFail();
    $notification->markAsRead();
    return back();
})->name('notifications.read');

// Supprimer une notification (client)
Route::middleware('auth')->delete('/mes-notifications/{notification}', function ($notificationId) {
    $notification = \App\Models\UserNotification::where('id', $notificationId)
        ->where('user_id', Auth::id())
        ->firstOrFail();
    $notification->delete();
    return back();
})->name('notifications.delete');
// Notifications client
Route::middleware('auth')->get('/mes-notifications', function () {
    $notifications = \App\Models\UserNotification::where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->paginate(15);
    return view('notifications', compact('notifications'));
})->name('notifications.client');
// Blog public
Route::get('/blog', function () {
    $posts = \App\Models\BlogPost::where('published', true)->orderByDesc('published_at')->paginate(8);
    return view('blog.index', compact('posts'));
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    $post = \App\Models\BlogPost::where('slug', $slug)->where('published', true)->firstOrFail();
    return view('blog.show', compact('post'));
})->name('blog.show');

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\EmailingController;
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/emailing', [EmailingController::class, 'index'])->name('admin.emailing');
    Route::post('/admin/emailing/send', [EmailingController::class, 'send'])->name('admin.emailing.send');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\ProductController as MerchantProductController;
use App\Http\Controllers\BecomeMerchantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\UserNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/home', function () {
    return redirect()->route('welcome');
})->name('home');
// Remerciements (Acknowledgements) routes
use App\Http\Controllers\RemerciementController;

// Public page
Route::get('/remerciements', [RemerciementController::class, 'index'])->name('remerciements.index');

// Admin panel (protected by 'auth' and 'admin' middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/remerciements', [RemerciementController::class, 'admin'])->name('remerciements.admin');
    Route::post('/admin/remerciements', [RemerciementController::class, 'store'])->name('remerciements.store');
    Route::delete('/admin/remerciements/{id}', [RemerciementController::class, 'destroy'])->name('remerciements.destroy');
    Route::post('/admin/remerciements/fondateurs', [RemerciementController::class, 'updateFondateurs'])->name('remerciements.fondateurs');
});
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Routes pour les avis produits
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/vote', [ReviewController::class, 'vote'])->name('reviews.vote');
});

// Routes du panier (authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    // Routes des commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
});

// Routes d'authentification (générées par Breeze)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isApprovedMerchant()) {
        return redirect()->route('merchant.dashboard');
    } else {
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/merchant-verification-requests', [AdminController::class, 'merchantVerificationRequests'])->name('merchant_verification_requests');
        Route::post('/merchant-verification-requests/{id}/approve', [AdminController::class, 'approveMerchantVerification'])->name('merchant_verification_requests.approve');
        Route::post('/merchant-verification-requests/{id}/reject', [AdminController::class, 'rejectMerchantVerification'])->name('merchant_verification_requests.reject');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
    Route::patch('/merchants/{merchant}/approve', [AdminController::class, 'approveMerchant'])->name('merchants.approve');
    Route::patch('/merchants/{merchant}/reject', [AdminController::class, 'rejectMerchant'])->name('merchants.reject');
    Route::patch('/merchants/{merchant}/verify', [AdminController::class, 'verifyMerchant'])->name('merchants.verify');
    Route::patch('/merchants/{merchant}/unverify', [AdminController::class, 'unverifyMerchant'])->name('merchants.unverify');
    
    // Gestion des utilisateurs
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Gestion des commandes
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // Notifications
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [AdminController::class, 'deleteNotification'])->name('notifications.delete');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.readAll');

    // Gestion des avis
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
    Route::delete('/reviews/{review}/reject', [AdminController::class, 'rejectReview'])->name('reviews.reject');
    
    // Paramètres système
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/role-signup', [\App\Http\Controllers\Admin\SettingsController::class, 'updateRoleSignup'])->name('settings.role-signup');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/maintenance', [App\Http\Controllers\Admin\SettingsController::class, 'maintenance'])->name('settings.maintenance');
    Route::post('/settings/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimizeSystem'])->name('settings.optimize');
    Route::post('/settings/cleanup', [App\Http\Controllers\Admin\SettingsController::class, 'systemCleanup'])->name('settings.cleanup');
    Route::post('/settings/backup', [App\Http\Controllers\Admin\SettingsController::class, 'createBackup'])->name('settings.backup');
    Route::get('/settings/stats', [App\Http\Controllers\Admin\SettingsController::class, 'systemStats'])->name('settings.stats');
    
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
    // SEO
    Route::get('/seo', [\App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('seo.edit');
    Route::post('/seo', [\App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');

    // Blog
    Route::get('/blog', [\App\Http\Controllers\Admin\BlogPostController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [\App\Http\Controllers\Admin\BlogPostController::class, 'create'])->name('blog.create');
    Route::post('/blog', [\App\Http\Controllers\Admin\BlogPostController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}', [\App\Http\Controllers\Admin\BlogPostController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [\App\Http\Controllers\Admin\BlogPostController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [\App\Http\Controllers\Admin\BlogPostController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [\App\Http\Controllers\Admin\BlogPostController::class, 'destroy'])->name('blog.destroy');
});

// Devenir commerçant (pour client connecté)
Route::middleware(['auth'])->group(function () {
    Route::get('/devenir-commercant', [BecomeMerchantController::class, 'showForm'])->name('become-merchant.form');
    Route::post('/devenir-commercant', [BecomeMerchantController::class, 'submit'])->name('become-merchant.submit');
});

// Routes Merchant
Route::middleware(['auth', 'merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::get('/verification-request', [MerchantController::class, 'showVerificationRequestForm'])->name('verification.request.form');
    Route::post('/verification-request', [MerchantController::class, 'verificationRequest'])->name('verification.request');
    Route::get('/products', [MerchantController::class, 'products'])->name('products.index');
    Route::get('/products/create', [MerchantController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [MerchantController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}', [MerchantController::class, 'showProduct'])->name('products.show');
    Route::get('/products/{product}/edit', [MerchantController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [MerchantController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [MerchantController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders', [MerchantController::class, 'orders'])->name('orders');
    Route::get('/orders-history', [MerchantController::class, 'orderHistory'])->name('orders.history');
    Route::post('/orders/{order}/validate', [MerchantController::class, 'validateOrder'])->name('orders.validate');
    Route::post('/orders/{order}/ship', [MerchantController::class, 'shipOrder'])->name('orders.ship');
    Route::post('/orders/{order}/deliver', [MerchantController::class, 'deliverOrder'])->name('orders.deliver');
    Route::get('/orders/{order}/details', [MerchantController::class, 'orderDetails'])->name('orders.details');
    Route::post('/orders/{order}/cancel', [MerchantController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/profile', [MerchantController::class, 'profile'])->name('profile');
    Route::put('/profile', [MerchantController::class, 'updateProfile'])->name('profile.update');
});

// Notifications
Route::middleware('auth')->get('/notifications/unread-count', function () {
    $count = UserNotification::where('user_id', Auth::id())->where('is_read', false)->count();
    return Response::json(['count' => $count]);
});

Route::middleware('auth')->get('/notifications/recent', function () {
    $notifications = UserNotification::where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->take(10)
        ->get(['id', 'title', 'message', 'is_read', 'created_at']);
    return Response::json(['notifications' => $notifications]);
});

require __DIR__.'/auth.php';
require __DIR__.'/admin_notifications.php';
