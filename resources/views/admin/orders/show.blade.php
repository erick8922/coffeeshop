@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')

<div class="max-w-2xl">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-amber-900">Order #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}"
           class="text-sm text-amber-600 hover:underline">← Back</a>
    </div>

    {{-- CUSTOMER INFO --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-3">Customer Info</h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-gray-400">Name</p>
                <p class="font-semibold">{{ $order->user_name }}</p>
            </div>
            <div>
                <p class="text-gray-400">Email</p>
                <p class="font-semibold">{{ $order->user_email }}</p>
            </div>
            <div>
                <p class="text-gray-400">Phone</p>
                <p class="font-semibold">{{ $order->user_phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Address</p>
                <p class="font-semibold">{{ $order->user_address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- ORDER ITEMS --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-3">Items</h3>
        @foreach($order->items as $item)
        <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
            <div>
                <p class="font-semibold">{{ $item->product_name }}</p>
                @if($item->size)
                    <p class="text-xs text-gray-400">Size: {{ $item->size }}</p>
                @endif
                <p class="text-xs text-gray-400">x{{ $item->quantity }}</p>
            </div>
            <p class="font-bold text-amber-700">
                ₱{{ number_format($item->price * $item->quantity, 2) }}
            </p>
        </div>
        @endforeach
        <div class="flex justify-between font-bold text-amber-900 text-lg mt-4">
            <span>Total</span>
            <span>₱{{ number_format($order->total_price, 2) }}</span>
        </div>
    </div>

    {{-- UPDATE STATUS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Update Status</h3>

        <div class="flex gap-3" id="statusUpdateForm">
            <select id="statusSelect"
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-amber-500">
                @foreach(['pending', 'preparing', 'ready', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}"
                        {{ $order->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <button type="button" id="updateStatusBtn"
                class="bg-amber-900 text-white px-6 py-2 rounded-lg text-sm
                       hover:bg-amber-700 transition font-semibold">
                Update
            </button>
        </div>

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
        <div class="mt-3 flex items-center gap-2">
            <span class="text-sm text-gray-400">Current Status:</span>
            <span id="currentStatusBadge"
                  class="px-3 py-1 rounded-full text-xs font-semibold
                         {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        @if($order->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-400">Notes from customer:</p>
            <p class="text-sm mt-1">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

</div>

{{-- HIDDEN URLs --}}
<div id="updateStatusUrl"
     data-url="{{ route('admin.orders.updateStatus', $order->id) }}"
     class="hidden"></div>

@push('scripts')
<script>
$(document).ready(function() {

    const token  = $('meta[name="csrf-token"]').attr('content');
    const url    = $('#updateStatusUrl').data('url');

    // ═══════════════════════════════════
    //  UPDATE STATUS - AJAX
    // ═══════════════════════════════════
    $('#updateStatusBtn').on('click', function() {
        const btn    = $(this);
        const status = $('#statusSelect').val();

        btn.text('Updating...').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'PATCH',
                _token: token,
                status: status,
            },
            success: function(response) {
                if (response.success) {
                    // Update badge
                    const colors = {
                        pending:   'bg-yellow-100 text-yellow-700',
                        preparing: 'bg-blue-100 text-blue-700',
                        ready:     'bg-green-100 text-green-700',
                        completed: 'bg-gray-100 text-gray-600',
                        cancelled: 'bg-red-100 text-red-600',
                    };

                    const badge = $('#currentStatusBadge');
                    badge.attr('class', 'px-3 py-1 rounded-full text-xs font-semibold ' +
                               (colors[status] ?? 'bg-gray-100'));
                    badge.text(status.charAt(0).toUpperCase() + status.slice(1));

                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showToast('error', res?.message ?? 'Something went wrong!');
            },
            complete: function() {
                btn.text('Update').prop('disabled', false);
            }
        });
    });

    // ═══════════════════════════════════
    //  TOAST NOTIFICATION
    // ═══════════════════════════════════
    function showToast(type, message) {
        const colors = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error:   'bg-red-100 border-red-400 text-red-700',
        };
        const icon  = type === 'success' ? '✅' : '❌';
        const toast = $(`
            <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg border
                        shadow-lg ${colors[type]}">
                ${icon} ${message}
            </div>
        `);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, function() { $(this).remove(); }), 3000);
    }
});
</script>
@endpush

@endsection