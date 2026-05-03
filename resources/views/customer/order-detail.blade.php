@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-amber-900">
            Order #{{ $order->id }}
        </h1>
        <a href="{{ route('orders.index') }}"
           class="text-sm text-amber-600 hover:underline">
            ← Back to Orders
        </a>
    </div>

    {{-- ORDER INFO --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Order Status</p>
                <p class="font-semibold text-amber-900 capitalize">{{ $order->status }}</p>
            </div>
            <div>
                <p class="text-gray-400">Payment Method</p>
                <p class="font-semibold text-amber-900 capitalize">{{ $order->payment_method }}</p>
            </div>
            <div>
                <p class="text-gray-400">Payment Status</p>
                <p class="font-semibold capitalize">{{ $order->payment_status }}</p>
            </div>
            <div>
                <p class="text-gray-400">Order Date</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                </p>
            </div>
            @if($order->notes)
            <div class="col-span-2">
                <p class="text-gray-400">Special Notes</p>
                <p class="font-semibold">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ORDER ITEMS --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="font-bold text-amber-900 text-lg mb-4">Order Items</h2>
        <div class="flex flex-col gap-3">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <div>
                    <p class="font-semibold">{{ $item->product_name }}</p>
                    @if($item->size)
                        <p class="text-xs text-gray-400">Size: {{ $item->size }}</p>
                    @endif
                    <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                </div>
                <p class="font-bold text-amber-700">
                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                </p>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between font-bold text-amber-900 text-lg mt-4">
            <span>Total Amount</span>
            <span>₱{{ number_format($order->total_price, 2) }}</span>
        </div>
    </div>

</div>

@endsection