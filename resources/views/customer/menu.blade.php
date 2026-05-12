@extends('layouts.app')

@section('title', 'Menu')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-3xl font-bold text-amber-900">Our Menu</h1>
    <p class="text-gray-500 mt-1">Handcrafted drinks made just for you</p>
</div>

{{-- SEARCH & FILTER --}}
<div class="flex flex-wrap gap-3 mb-8">
    {{-- SEARCH INPUT --}}
    <div class="relative flex-1">
        <input type="text" id="searchInput"
               placeholder="Search your favorite drink..."
               class="w-full border border-gray-300 rounded-lg px-4 py-2 pl-10 text-sm
                      focus:outline-none focus:ring-2 focus:ring-amber-500">
        <span class="absolute left-3 top-2.5 text-gray-400 text-sm"></span>
    </div>

    {{-- CATEGORY FILTER --}}
    <select id="categoryFilter"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-amber-500">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->slug }}">
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    {{-- RESET --}}
    <button id="resetBtn"
        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm
               hover:bg-gray-300 transition">
        Reset
    </button>
</div>

{{-- RESULTS COUNT --}}
<div class="mb-4 text-sm text-gray-500" id="resultsCount">
    Showing {{ count($products) }} product(s)
</div>

{{-- LOADING SPINNER --}}
<div id="loadingSpinner" class="hidden text-center py-10">
    <div class="inline-block w-8 h-8 border-4 border-amber-900 border-t-transparent
                rounded-full animate-spin"></div>
    <p class="text-gray-400 mt-2 text-sm">Searching...</p>
</div>

{{-- PRODUCTS GRID --}}
<div id="productsGrid">
    @if(empty($products))
        <div class="text-center py-20 text-gray-400" id="noResults">
            <p class="text-5xl mb-4">☕</p>
            <p class="text-lg">No drinks found.</p>
            <p class="text-sm mt-1">Try adjusting your search or filters.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productsList">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
                @if($product->image)
                    <div class="relative w-full h-44 overflow-hidden rounded-t-xl">
                        @php $bgImage = asset('images/product_images/' . $product->image); @endphp
                        <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                            style="background-image: url('{{ $bgImage }}')">
                        </div>
                        <img src="{{ asset('images/product_images/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="relative z-10 w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-full h-44 bg-amber-100 flex items-center justify-center">
                        <span class="text-5xl">☕</span>
                    </div>
                @endif
                <div class="p-4">
                    <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-full">
                        {{ $product->category_name }}
                    </span>
                    <h3 class="font-bold text-amber-900 mt-2">{{ $product->name }}</h3>
                    <p class="text-gray-400 text-xs mt-1 line-clamp-2">{{ $product->description }}</p>
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
    @endif
</div>

{{-- HIDDEN URL para sa JS --}}
<div id="menuUrl" data-url="{{ route('menu') }}" class="hidden"></div>

@push('scripts')
<script src="{{ asset('js/menu.js') }}"></script>
@endpush

@endsection