<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // ═══════════════════════════════════
    //  LIST NG LAHAT NG ORDERS
    // ═══════════════════════════════════
    public function index()
    {
        $orders = Order::with('user')
                       ->latest()
                       ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // ═══════════════════════════════════
    //  SHOW SINGLE ORDER
    // ═══════════════════════════════════
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])
                      ->findOrFail($id);

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

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        // Kung completed, i-mark bilang paid
        if ($request->status === 'completed') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Order status updated!');
    }
}