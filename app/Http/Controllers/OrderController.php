<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ═══════════════════════════════════
    //  ORDER HISTORY
    // ═══════════════════════════════════
    public function index()
    {
        $userId = Auth::id();

        $orders = DB::select('
            SELECT * FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 10
        ', [$userId]);

        foreach ($orders as $order) {
            $order->items = DB::select('
                SELECT oi.*, p.name as product_name, p.image as product_image
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?
            ', [$order->id]);
        }

        return view('customer.orders', compact('orders'));
    }

    // ═══════════════════════════════════
    //  SHOW SINGLE ORDER
    // ═══════════════════════════════════
    public function show($id)
    {
        $userId = Auth::id();

        $order = DB::selectOne('
            SELECT * FROM orders
            WHERE id = ? AND user_id = ?
        ', [$id, $userId]);

        if (!$order) abort(404);

        $order->items = DB::select('
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ', [$order->id]);

        return view('customer.order-detail', compact('order'));
    }

    // ═══════════════════════════════════
    //  CHECKOUT (AJAX)
    // ═══════════════════════════════════
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,gcash,card',
            'notes'          => 'nullable|string|max:500',
        ]);

        $userId = Auth::id();

        $cart = DB::selectOne('
            SELECT * FROM carts WHERE user_id = ?
        ', [$userId]);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!'
            ], 400);
        }

        $items = DB::select('
            SELECT ci.*, p.name as product_name,
                   p.price as product_price,
                   p.stock as product_stock
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ', [$cart->id]);

        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!'
            ], 400);
        }

        // ═══════════════════════════════════
        //  STOCK VALIDATION — bago mag-checkout
        // ═══════════════════════════════════
        foreach ($items as $item) {
            if ($item->product_stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => $item->product_name . ' is out of stock! 
                                  Please remove it from your cart.'
                ], 400);
            }

            if ($item->product_stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $item->product_stock . ' left for "' . 
                                 $item->product_name . '". Please update your cart.'
                ], 400);
            }
        }

        $total = array_sum(array_map(
            fn($item) => $item->product_price * $item->quantity,
            $items
        ));

        DB::beginTransaction();
        try {
            DB::insert('
                INSERT INTO orders
                (user_id, total_price, status, payment_method, 
                 payment_status, notes, ordered_at, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
            ', [
                $userId,
                $total,
                'pending',
                $request->payment_method,
                'unpaid',
                $request->notes,
            ]);

            $orderId = DB::getPdo()->lastInsertId();

            foreach ($items as $item) {
                // I-save ang order item
                DB::insert('
                    INSERT INTO order_items
                    (order_id, product_id, quantity, price, size, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ', [
                    $orderId,
                    $item->product_id,
                    $item->quantity,
                    $item->product_price,
                    $item->size,
                ]);

                // Bawasan ang stock
                DB::update('
                    UPDATE products
                    SET stock = stock - ?, updated_at = NOW()
                    WHERE id = ? AND stock >= ?
                ', [$item->quantity, $item->product_id, $item->quantity]);
            }

            // I-clear ang cart
            DB::delete('
                DELETE FROM cart_items WHERE cart_id = ?
            ', [$cart->id]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Your order has been placed successfully!',
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    // ═══════════════════════════════════
    //  ORDER SUCCESS PAGE
    // ═══════════════════════════════════
    public function success($id)
    {
        $userId = Auth::id();

        $order = DB::selectOne('
            SELECT * FROM orders
            WHERE id = ? AND user_id = ?
        ', [$id, $userId]);

        if (!$order) abort(404);

        $order->items = DB::select('
            SELECT oi.*, p.name as product_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ', [$order->id]);

        return view('customer.order-success', compact('order'));
    }
}