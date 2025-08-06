<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_merchants' => User::where('role', 'merchant')->count(),
            'pending_merchants' => User::where('role', 'merchant')->where('merchant_approved', false)->count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'pending_products_list' => Product::with(['user', 'category'])->where('status', 'pending')->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function merchants()
    {
        $merchants = User::where('role', 'merchant')->with('products')->paginate(15);
        return view('admin.merchants', compact('merchants'));
    }

    public function approveMerchant(User $merchant)
    {
        $merchant->update(['merchant_approved' => true]);
        return back()->with('success', 'Commerçant approuvé avec succès!');
    }

    public function rejectMerchant(User $merchant)
    {
        $merchant->update(['merchant_approved' => false]);
        return back()->with('success', 'Approbation du commerçant révoquée!');
    }
}
