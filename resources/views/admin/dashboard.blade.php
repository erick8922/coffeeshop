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

    <div class="bg-red-50 border border-red-200 rounded-xl shadow p-6">
        <p class="text-sm text-red-400">Out of Stock</p>
        <p class="text-3xl font-bold text-red-600 mt-1">
            {{ $outOfStockProducts }}
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

{{-- CHARTS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    {{-- ORDERS PER MONTH --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-amber-900 mb-4">
            Orders per Month
        </h3>
        <canvas id="ordersChart" height="120"></canvas>
    </div>

    {{-- SALES PER MONTH --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-amber-900 mb-4">
            Sales per Month
        </h3>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    {{-- ORDERS BY STATUS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-amber-900 mb-4">
            Orders by Status
        </h3>
        <canvas id="statusChart" height="120"></canvas>
    </div>

    {{-- TOP PRODUCTS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-amber-900 mb-4">
            Top 5 Products
        </h3>
        <canvas id="topProductsChart" height="120"></canvas>
    </div>

</div>

{{-- RECENT ORDERS --}}
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-bold text-amber-900 mb-4">Recent Orders</h2>
    <table id="recentOrdersTable" class="w-full text-sm">
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
                <td class="py-3">{{ $order->user_name }}</td>
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
                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
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

{{-- LOW STOCK WARNING --}}
@if(count($lowStockProducts) > 0)
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-red-600">
            ⚠️ Low Stock Alert
            <span class="ml-2 bg-red-100 text-red-600 px-2 py-0.5
                         rounded-full text-sm font-semibold">
                {{ count($lowStockProducts) }} product(s)
            </span>
        </h2>
        <a href="{{ route('admin.products.index') }}"
           class="text-sm text-amber-600 hover:underline">
            Manage Products →
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 border-b">
                    <th class="pb-3">Product</th>
                    <th class="pb-3">Stock</th>
                    <th class="pb-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                <tr class="border-b last:border-0 hover:bg-gray-50">
                    <td class="py-3 font-semibold text-amber-900">
                        {{ $product->name }}
                    </td>
                    <td class="py-3">
                        @if($product->stock == 0)
                            <span class="bg-red-100 text-red-600 px-2 py-1
                                         rounded-full text-xs font-semibold">
                                Out of Stock
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1
                                         rounded-full text-xs font-semibold">
                                {{ $product->stock }} left
                            </span>
                        @endif
                    </td>
                    <td class="py-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="bg-amber-900 text-white px-3 py-1 rounded-lg
                                  text-xs hover:bg-amber-700 transition">
                            Update Stock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- HIDDEN URL para sa JS --}}
<div id="dashboardUrl" data-url="{{ route('admin.dashboard') }}" class="hidden"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- I-pass ang PHP data sa JavaScript via data attributes --}}
<script>
    window.dashboardData = {
        ordersPerMonth: @json($ordersPerMonth),
        salesPerMonth: @json($salesPerMonth),
        ordersByStatus: @json($ordersByStatus),
        topProducts: @json($topProducts)
    };
</script>

<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush



@endsection