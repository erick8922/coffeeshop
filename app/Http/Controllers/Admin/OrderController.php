<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ═══════════════════════════════════
    //  LIST ALL ORDERS
    // ═══════════════════════════════════
    public function index()
    {
        $orders = DB::select('
            SELECT o.*, u.name as user_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ');

        return view('admin.orders.index', compact('orders'));
    }

    // ═══════════════════════════════════
    //  SHOW SINGLE ORDER
    // ═══════════════════════════════════
    public function show($id)
    {
        $order = DB::selectOne('
            SELECT o.*, u.name as user_name, u.email as user_email,
                   u.phone as user_phone, u.address as user_address
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ', [$id]);

        if (!$order) abort(404);

        $order->items = DB::select('
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ', [$order->id]);

        return view('admin.orders.show', compact('order'));
    }

    // ═══════════════════════════════════
    //  UPDATE ORDER STATUS
    // ═══════════════════════════════════
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
        ]);

        DB::update('
            UPDATE orders SET status = ?, updated_at = NOW()
            WHERE id = ?
        ', [$request->status, $id]);

        if ($request->status === 'completed') {
            DB::update('
                UPDATE orders SET payment_status = ?, updated_at = NOW()
                WHERE id = ?
            ', ['paid', $id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!'
        ]);
    }

    // ═══════════════════════════════════
    //  DELETE ORDER
    // ═══════════════════════════════════
    public function destroy($id)
    {
        $order = DB::selectOne('
            SELECT * FROM orders WHERE id = ?
        ', [$id]);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!'
            ], 404);
        }

        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed orders can be deleted!'
            ], 400);
        }

        DB::delete('DELETE FROM order_items WHERE order_id = ?', [$id]);
        DB::delete('DELETE FROM orders WHERE id = ?', [$id]);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully!'
        ]);
    }
}