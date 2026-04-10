@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-amber-900">All Products</h2>
    <a href="{{ route('admin.products.create') }}"
       class="bg-amber-900 text-white px-4 py-2 rounded-lg text-sm
              hover:bg-amber-700 transition">
        Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-400 text-left">
            <tr>
                <th class="px-6 py-3">Product</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Stock</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-amber-900">
                    {{ $product->name }}
                </td>
                <td class="px-6 py-4 text-gray-500">
                    {{ $product->category->name }}
                </td>
                <td class="px-6 py-4 text-amber-700 font-semibold">
                    ₱{{ number_format($product->price, 2) }}
                </td>
                <td class="px-6 py-4">{{ $product->stock }}</td>
                <td class="px-6 py-4">
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
                <td class="px-6 py-4 flex gap-3">
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="text-blue-500 hover:underline text-xs">
                        Edit
                    </a>
                    <form method="POST"
                          action="{{ route('admin.products.destroy', $product->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-400 hover:underline text-xs"
                                onclick="return confirm('Tanggalin ang product?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $products->links() }}</div>
</div>

@endsection