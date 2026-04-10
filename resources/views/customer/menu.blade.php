@extends('layouts.app')

@section('title', 'Menu')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-3xl font-bold text-amber-900">Our Menu</h1>
    <p class="text-gray-500 mt-1">Handcrafted drinks made just for you</p>
</div>

{{-- FILTER BY CATEGORY + SEARCH --}}
<form method="GET" action="{{ route('menu') }}" class="flex flex-wrap gap-3 mb-8">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search your favorite drink..."
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-amber-500 flex-1">

    <select name="category"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-amber-500">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->slug }}"
                {{ request('category') === $category->slug ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <button type="submit"
        class="bg-amber-900 text-white px-5 py-2 rounded-lg text-sm
               hover:bg-amber-700 transition">
        Apply Filter
    </button>

    @if(request('search') || request('category'))
        <a href="{{ route('menu') }}"
           class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm
                  hover:bg-gray-300 transition">
            Reset
        </a>
    @endif
</form>

{{-- PRODUCTS GRID --}}
@if($products->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-lg">No drinks found.</p>
        <p class="text-sm mt-1">Try adjusting your search or filters.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">

            {{-- IMAGE --}}
            @if($product->image)
                <div class="relative w-full h-44 overflow-hidden rounded-t-xl">
                    {{-- BLURRED BACKGROUND --}}
                    @php $bgImage = asset('storage/' . $product->image); @endphp
                    <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                        style="background-image: url('{{ $bgImage }}')">
                    </div>
                    {{-- ACTUAL IMAGE --}}
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="relative z-10 w-full h-full object-contain">
                </div>
            @else
                <div class="w-full h-44 bg-amber-100 flex items-center justify-center">
                    <span class="text-5xl">☕</span>
                </div>
            @endif

            {{-- DETAILS --}}
            <div class="p-4">
                <span class="text-xs text-amber-600 font-medium bg-amber-50
                             px-2 py-1 rounded-full">
                    {{ $product->category->name }}
                </span>
                <h3 class="font-bold text-amber-900 mt-2">{{ $product->name }}</h3>
                <p class="text-gray-400 text-xs mt-1 line-clamp-2">
                    {{ $product->description }}
                </p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-amber-700 font-bold">
                        ₱{{ number_format($product->price, 2) }}
                    </span>
                    <a href="{{ route('menu.show', $product->slug) }}"
                       class="bg-amber-900 text-white px-3 py-1 rounded-full text-sm
                              hover:bg-amber-700 transition">
                        Order Now
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif

@endsection