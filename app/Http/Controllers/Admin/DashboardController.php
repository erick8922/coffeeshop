<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // ═══════════════════════════════════
        //  STATS
        // ═══════════════════════════════════
        $totalSales    = Order::where('payment_status', 'paid')->sum('total_price');
        $totalOrders   = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // ═══════════════════════════════════
        //  RECENT ORDERS
        // ═══════════════════════════════════
        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(10)
                             ->get();

        // ═══════════════════════════════════
        //  ORDERS BY STATUS
        // ═══════════════════════════════════
        $pendingOrders   = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $readyOrders     = Order::where('status', 'ready')->count();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'pendingOrders',
            'preparingOrders',
            'readyOrders'
        ));
    }
}