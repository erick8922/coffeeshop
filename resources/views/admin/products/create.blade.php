@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-amber-900">New Product</h2>
        <a href="{{ route('admin.products.index') }}"
           class="text-sm text-amber-600 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- CATEGORY --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Category
                </label>
                <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NAME --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Product Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Ex: Caramel Latte">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Describe the product...">{{ old('description') }}</textarea>
            </div>

            {{-- PRICE & STOCK --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Price (₱)
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}"
                        step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="0.00">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stock
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                        min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- SIZE OPTIONS --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Size Options
                </label>
                <div class="flex gap-4">
                    @foreach(['Small', 'Medium', 'Large'] as $size)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="size_options[]"
                               value="{{ $size }}" class="rounded">
                        <span class="text-sm">{{ $size }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Image
                </label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- IS AVAILABLE --}}
            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="is_available" id="is_available"
                       value="1" checked>
                <label for="is_available" class="text-sm text-gray-700">
                    Available for customers
                </label>
            </div>

            <button type="submit"
                class="w-full bg-amber-900 text-white py-3 rounded-xl font-semibold
                       hover:bg-amber-700 transition">
                💾 Save Product
            </button>
        </form>
    </div>
</div>

@endsection