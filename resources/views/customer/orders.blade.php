@extends('layouts.app')

@section('title', 'My Orders')

@section('content')

<div class="relative rounded-2xl overflow-hidden p-6 mb-10
            bg-[url('{{ asset('images/counter1.jpg') }}')] bg-cover bg-center">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10">
<h1 class="text-3xl font-bold text-white mb-6"> My Orders</h1>

@if($orders->isEmpty())
    <div class="text-center py-20 text-white -400">
        
        <p class="text-lg mb-4">You haven’t placed any orders yet.</p>
        <a href="{{ route('menu') }}"
           class="bg-white text-amber-900 px-6 py-3 rounded-full
                          hover:bg-amber-100 transition font-semibold">
            Order Now
        </a>
    </div>
@else
    <div class="flex flex-col gap-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-amber-900 text-lg">
                        Order #{{ $order->id }}
                    </p>
                   <p class="text-sm text-gray-400 mt-1">
                        {{ $order->created_at->format('M d, Y h:i A') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $order->items->count() }} item(s)
                    </p>
                </div>
                <div class="text-right">
                    {{-- STATUS BADGE --}}
                    @php
                        $colors = [
                            'pending'   => 'bg-yellow-100 text-yellow-700',
                            'preparing' => 'bg-blue-100 text-blue-700',
                            'ready'     => 'bg-green-100 text-green-700',
                            'completed' => 'bg-gray-100 text-gray-600',
                            'cancelled' => 'bg-red-100 text-red-600',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                 {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <p class="font-bold text-amber-700 mt-2">
                        ₱{{ number_format($order->total_price, 2) }}
                    </p>
                    <a href="{{ route('orders.show', $order->id) }}"
                       class="text-sm text-amber-600 hover:underline mt-1 block">
                        View → 
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endif

    </div>
</div>
@endsection