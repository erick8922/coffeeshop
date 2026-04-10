@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-400">Total Sales</p>
        <p class="text-3xl font-bold text-amber-900 mt-1">
            ₱{{ number_format($totalSales, 2) }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-400">Total Orders</p>
        <p class="text-3xl font-bold text-amber-900 mt-1">
            {{ $totalOrders }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-400">Total Products</p>
        <p class="text-3xl font-bold text-amber-900 mt-1">
            {{ $totalProducts }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-400">Total Customers</p>
        <p class="text-3xl font-bold text-amber-900 mt-1">
            {{ $totalCustomers }}
        </p>
    </div>

</div>

{{-- ORDER STATUS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
        <p class="text-sm text-yellow-600 font-medium">Pending Orders</p>
        <p class="text-3xl font-bold text-yellow-700 mt-1">{{ $pendingOrders }}</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
        <p class="text-sm text-blue-600 font-medium">Preparing Orders</p>
        <p class="text-3xl font-bold text-blue-700 mt-1">{{ $preparingOrders }}</p>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-xl p-5">
        <p class="text-sm text-green-600 font-medium">Ready Orders</p>
        <p class="text-3xl font-bold text-green-700 mt-1">{{ $readyOrders }}</p>
    </div>

</div>

{{-- RECENT ORDERS --}}
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-bold text-amber-900 mb-4">Recent Orders</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">Order #</th>
                <th class="pb-3">Customer</th>
                <th class="pb-3">Total</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Date</th>
                <th class="pb-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            @php
                $colors = [
                    'pending'   => 'bg-yellow-100 text-yellow-700',
                    'preparing' => 'bg-blue-100 text-blue-700',
                    'ready'     => 'bg-green-100 text-green-700',
                    'completed' => 'bg-gray-100 text-gray-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                ];
            @endphp
            <tr class="border-b last:border-0 hover:bg-gray-50">
                <td class="py-3 font-semibold">#{{ $order->id }}</td>
                <td class="py-3">{{ $order->user->name }}</td>
                <td class="py-3 text-amber-700 font-semibold">
                    ₱{{ number_format($order->total_price, 2) }}
                </td>
                <td class="py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                 {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="py-3 text-gray-400">
                    {{ $order->created_at->format('M d, Y') }}
                </td>
                <td class="py-3">
                    <a href="{{ route('admin.orders.show', $order->id) }}"
                       class="text-amber-600 hover:underline text-xs">
                        View →
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection