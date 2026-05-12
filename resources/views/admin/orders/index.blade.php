@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

<h2 class="text-xl font-bold text-amber-900 mb-6">All Orders</h2>

<div class="bg-white rounded-xl shadow overflow-hidden p-4">
    <table id="ordersTable" class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="px-4 py-3">Order #</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Payment</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
                $colors = [
                    'pending'   => 'bg-yellow-100 text-yellow-700',
                    'preparing' => 'bg-blue-100 text-blue-700',
                    'ready'     => 'bg-green-100 text-green-700',
                    'completed' => 'bg-gray-100 text-gray-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                ];
            @endphp
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                <td class="px-4 py-3">{{ $order->user_name }}</td>
                <td class="px-4 py-3 text-amber-700 font-semibold">
                    ₱{{ number_format($order->total_price, 2) }}
                </td>
                <td class="px-4 py-3 capitalize">{{ $order->payment_method }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                 {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-400">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.orders.show', $order->id) }}"
                       class="bg-amber-900 text-white px-3 py-1 rounded-lg
                              text-xs hover:bg-amber-700 transition">
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- HIDDEN URL para sa JS --}}
<div id="ordersUrl" data-url="{{ route('admin.orders.index') }}" class="hidden"></div>

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
@endpush






@endsection