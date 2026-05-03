<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = DB::selectOne('
            SELECT SUM(total_price) as total
            FROM orders WHERE payment_status = "paid"
        ')->total ?? 0;

        $totalOrders = DB::selectOne('
            SELECT COUNT(*) as total FROM orders
        ')->total;

        $totalProducts = DB::selectOne('
            SELECT COUNT(*) as total FROM products
        ')->total;

        $totalCustomers = DB::selectOne('
            SELECT COUNT(*) as total FROM users WHERE role = "customer"
        ')->total;

        $recentOrders = DB::select('
            SELECT o.*, u.name as user_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 10
        ');

        $pendingOrders = DB::selectOne('
            SELECT COUNT(*) as total FROM orders WHERE status = "pending"
        ')->total;

        $preparingOrders = DB::selectOne('
            SELECT COUNT(*) as total FROM orders WHERE status = "preparing"
        ')->total;

        $readyOrders = DB::selectOne('
            SELECT COUNT(*) as total FROM orders WHERE status = "ready"
        ')->total;

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