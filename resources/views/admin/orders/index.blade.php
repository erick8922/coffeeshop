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
            <tr class="border-t hover:bg-gray-50" id="order-row-{{ $order->id }}">
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
                    <div class="flex gap-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="bg-amber-900 text-white px-3 py-1 rounded-lg
                                  text-xs hover:bg-amber-700 transition">
                            View
                        </a>
                        @if($order->status === 'completed')
                        <button type="button"
                                class="delete-order bg-red-500 text-white px-3 py-1
                                       rounded-lg text-xs hover:bg-red-600 transition"
                                data-id="{{ $order->id }}"
                                data-url="{{ route('admin.orders.destroy', $order->id) }}">
                            Delete
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteConfirmModal"
     class="fixed inset-0 z-50 items-center justify-center hidden"
     style="display:none;">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full
                mx-auto mt-40 text-center z-10">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center
                    justify-center mx-auto mb-4">
            <span class="text-3xl">🗑️</span>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Delete this Order?</h3>
        <p class="text-gray-500 text-sm mb-2">
            This action <span class="font-semibold text-red-500">cannot be undone</span>.
        </p>
        <p class="text-gray-400 text-xs mb-6">
            Only <span class="font-semibold text-green-600">completed</span> orders
            can be deleted.
        </p>
        <div class="flex gap-3">
            <button id="cancelDeleteBtn"
                class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-xl
                       font-semibold hover:bg-gray-200 transition">
                Cancel
            </button>
            <button id="confirmDeleteBtn"
                class="flex-1 bg-red-500 text-white py-2 rounded-xl
                       font-semibold hover:bg-red-600 transition">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin/orders.js') }}"></script>
@endpush

@endsection