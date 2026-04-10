@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-amber-900">Edit: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}"
           class="text-sm text-amber-600 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST"
              action="{{ route('admin.products.update', $product->id) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- CATEGORY --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NAME --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- PRICE & STOCK --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" name="price"
                           value="{{ old('price', $product->price) }}"
                           step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock"
                           value="{{ old('stock', $product->stock) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            {{-- SIZE OPTIONS --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Size Options & Price
                </label>
                @php
                    $rawSizes = $product->size_options;
                    if (is_string($rawSizes)) {
                        $rawSizes = json_decode($rawSizes, true);
                    }
                    if (is_string($rawSizes)) {
                        $rawSizes = json_decode($rawSizes, true);
                    }
                    $currentSizes = collect($rawSizes ?? [])->keyBy('size');
                @endphp
                <div class="flex flex-col gap-3">
                    @foreach(['Small', 'Medium', 'Large'] as $size)
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="sizes[]"
                            value="{{ $size }}"
                            id="size_{{ $size }}" class="rounded"
                            {{ $currentSizes->has($size) ? 'checked' : '' }}>
                        <label for="size_{{ $size }}" class="w-20 text-sm">
                            {{ $size }}
                        </label>
                        <input type="number" name="size_prices[{{ $size }}]"
                            placeholder="Price (₱)" step="0.01" min="0"
                            value="{{ $currentSizes->get($size)['price'] ?? '' }}"
                            class="border border-gray-300 rounded-lg px-3 py-1 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-amber-500 w-32">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Image (optional — upload only if you want to replace)
                </label>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="w-24 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            </div>

            {{-- IS AVAILABLE --}}
            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="is_available" id="is_available"
                       value="1" {{ $product->is_available ? 'checked' : '' }}>
                <label for="is_available" class="text-sm text-gray-700">
                    Available for customers
                </label>
            </div>

            <button type="submit"
                class="w-full bg-amber-900 text-white py-3 rounded-xl font-semibold
                       hover:bg-amber-700 transition">
                💾 Update Product
            </button>
        </form>
    </div>
</div>

@endsection