<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ═══════════════════════════════════
    //  ORDER HISTORY
    // ═══════════════════════════════════
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)
                       ->with('items.product')
                       ->latest()
                       ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    // ═══════════════════════════════════
    //  SHOW SINGLE ORDER
    // ═══════════════════════════════════
    public function show($id)
    {
        $userId = Auth::id();

        $order = Order::where('id', $id)
                      ->where('user_id', $userId)
                      ->with('items.product')
                      ->firstOrFail();

        return view('customer.order-detail', compact('order'));
    }

    // ═══════════════════════════════════
    //  CHECKOUT
    // ═══════════════════════════════════
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,gcash,card',
            'notes'          => 'nullable|string|max:500',
        ]);

        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
                    ->with('items.product')
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Your cart is empty!');
        }

        $total = $cart->items->sum(
            fn($item) => $item->product->price * $item->quantity
        );

        DB::transaction(function () use ($cart, $total, $request, $userId) {

            $order = Order::create([
                'user_id'        => $userId,
                'total_price'    => $total,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'notes'          => $request->notes,
                'ordered_at'     => now(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                    'size'       => $item->size,
                ]);
            }

            $cart->items()->delete();

            session(['last_order_id' => $order->id]);
        });

        return redirect()->route('orders.success', session('last_order_id'))
                         ->with('success', 'Your order has been placed successfully!');
    }

    // ═══════════════════════════════════
    //  ORDER SUCCESS PAGE
    // ═══════════════════════════════════
    public function success($id)
    {
        $userId = Auth::id();

        $order = Order::where('id', $id)
                      ->where('user_id', $userId)
                      ->with('items.product')
                      ->firstOrFail();

        return view('customer.order-success', compact('order'));
    }
}