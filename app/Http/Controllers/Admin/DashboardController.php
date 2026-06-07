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
        // ═══════════════════════════════════
        //  STATS CARDS
        // ═══════════════════════════════════
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

        // ═══════════════════════════════════
        //  CHART DATA — Orders per month
        // ═══════════════════════════════════
        $ordersPerMonth = DB::select('
            SELECT 
                MONTHNAME(created_at) as month,
                MONTH(created_at) as month_num,
                COUNT(*) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(NOW())
            GROUP BY MONTH(created_at), MONTHNAME(created_at)
            ORDER BY MONTH(created_at) ASC
        ');

        // ═══════════════════════════════════
        //  CHART DATA — Sales per month
        // ═══════════════════════════════════
        $salesPerMonth = DB::select('
            SELECT 
                MONTHNAME(created_at) as month,
                MONTH(created_at) as month_num,
                SUM(total_price) as total
            FROM orders
            WHERE YEAR(created_at) = YEAR(NOW())
            AND payment_status = "paid"
            GROUP BY MONTH(created_at), MONTHNAME(created_at)
            ORDER BY MONTH(created_at) ASC
        ');

        // ═══════════════════════════════════
        //  CHART DATA — Orders by status
        // ═══════════════════════════════════
        $ordersByStatus = DB::select('
            SELECT status, COUNT(*) as total
            FROM orders
            GROUP BY status
        ');

        // ═══════════════════════════════════
        //  CHART DATA — Top products
        // ═══════════════════════════════════
        $topProducts = DB::select('
            SELECT p.name, SUM(oi.quantity) as total_sold
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            GROUP BY p.id, p.name
            ORDER BY total_sold DESC
            LIMIT 5
        ');

        $lowStockProducts = DB::select('
            SELECT * FROM products
            WHERE stock <= 5
            AND is_available = 1
            ORDER BY stock ASC
        ');

        $outOfStockProducts = DB::selectOne('
            SELECT COUNT(*) as total FROM products
            WHERE stock = 0
        ')->total;

        // Single return with all variables
        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'ordersPerMonth',
            'salesPerMonth',
            'ordersByStatus',
            'topProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}