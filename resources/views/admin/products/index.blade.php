@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-amber-900">All Products</h2>
    <a href="{{ route('admin.products.create') }}"
       class="bg-amber-900 text-white px-4 py-2 rounded-lg text-sm
              hover:bg-amber-700 transition">
        + Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden p-4">
    <table id="productsTable" class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Image</th>
                <th class="px-4 py-3">Product Name</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">{{ $product->id }}</td>
                <td class="px-4 py-3">
                    @if($product->image)
                        <img src="{{ asset('images/product_images/' . $product->image) }}"
                             class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex
                                    items-center justify-center">
                            <span class="text-xl">☕</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 font-semibold text-amber-900">
                    {{ $product->name }}
                </td>
                <!-- <td class="px-4 py-3">
                    <span class="text-xs text-amber-600 font-medium bg-amber-50
                                 px-2 py-1 rounded-full">
                        {{ $product->category_name }}
                    </span>
                </td> -->
                <td class="px-4 py-3 text-amber-700 font-semibold">
                    ₱{{ number_format($product->price, 2) }}
                </td>
                <td class="px-4 py-3">{{ $product->stock }}</td>
                <td class="px-4 py-3">
                    @if($product->is_available)
                        <span class="bg-green-100 text-green-700 px-2 py-1
                                     rounded-full text-xs font-semibold">
                            Available
                        </span>
                    @else
                        <span class="bg-red-100 text-red-600 px-2 py-1
                                     rounded-full text-xs font-semibold">
                            Unavailable
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded-lg
                                  text-xs hover:bg-blue-600 transition">
                            Edit
                        </a>
                        <button type="button"
                                class="delete-btn bg-red-500 text-white px-3 py-1
                                       rounded-lg text-xs hover:bg-red-600 transition"
                                data-id="{{ $product->id }}"
                                data-url="{{ route('admin.products.destroy', $product->id) }}">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- HIDDEN URL para sa JS --}}
<div id="productsUrl" data-url="{{ route('admin.products.index') }}" class="hidden"></div>

@push('scripts')
<script src="{{ asset('js/admin/products.js') }}"></script>
@endpush



@endsection