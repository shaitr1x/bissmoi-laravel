<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\ProductController as MerchantProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

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
    Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
    Route::patch('/merchants/{merchant}/approve', [AdminController::class, 'approveMerchant'])->name('merchants.approve');
    Route::patch('/merchants/{merchant}/reject', [AdminController::class, 'rejectMerchant'])->name('merchants.reject');
    
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
});

// Routes Merchant
Route::middleware(['auth', 'merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [MerchantController::class, 'products'])->name('products.index');
    Route::get('/products/create', [MerchantController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [MerchantController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}', [MerchantController::class, 'showProduct'])->name('products.show');
    Route::get('/products/{product}/edit', [MerchantController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [MerchantController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [MerchantController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders', [MerchantController::class, 'orders'])->name('orders');
    Route::get('/profile', [MerchantController::class, 'profile'])->name('profile');
    Route::put('/profile', [MerchantController::class, 'updateProfile'])->name('profile.update');
});

require __DIR__.'/auth.php';
