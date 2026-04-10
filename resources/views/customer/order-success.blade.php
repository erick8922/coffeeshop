@extends('layouts.app')

@section('title', 'Order Successful!')

@section('content')

<div class="max-w-lg mx-auto text-center py-16">
    <p class="text-7xl mb-6">🎉</p>
    <h1 class="text-3xl font-bold text-amber-900 mb-2">
        Thank You for Your Order!
    </h1>
    <p class="text-gray-500 mb-6">
        Order #{{ $order->id }} has been received.
        We'll start preparing it for you right away!
    </p>

    <div class="bg-white rounded-xl shadow p-6 text-left mb-6">
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-gray-400">Status</p>
                <p class="font-semibold capitalize text-amber-900">{{ $order->status }}</p>
            </div>
            <div>
                <p class="text-gray-400">Payment</p>
                <p class="font-semibold capitalize">{{ $order->payment_method }}</p>
            </div>
            <div>
                <p class="text-gray-400">Total</p>
                <p class="font-bold text-amber-700 text-lg">
                    ₱{{ number_format($order->total_price, 2) }}
                </p>
            </div>
            <div>
                <p class="text-gray-400">Items</p>
                <p class="font-semibold">{{ $order->items->count() }} item(s)</p>
            </div>
        </div>
    </div>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('orders.show', $order->id) }}"
           class="bg-amber-900 text-white px-6 py-3 rounded-full
                  hover:bg-amber-700 transition font-semibold">
            View Order
        </a>
        <a href="{{ route('menu') }}"
           class="bg-gray-100 text-gray-700 px-6 py-3 rounded-full
                  hover:bg-gray-200 transition font-semibold">
            Back to Menu
        </a>
    </div>
</div>

@endsection