@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-10">

    {{-- IMAGE --}}
    <div>
        @if($product->image)
            <div class="relative w-full h-80 overflow-hidden rounded-2xl shadow">
                @php $bgImage = asset('images/product_images/' . $product->image); @endphp
                <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                     style="background-image: url('{{ $bgImage }}')">
                </div>
                <img src="{{ asset('images/product_images/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="relative z-10 w-full h-full object-contain">
            </div>
        @else
            <div class="w-full h-80 bg-amber-100 rounded-2xl flex items-center justify-center">
                <span class="text-8xl">☕</span>
            </div>
        @endif
    </div>

    {{-- DETAILS --}}
    <div>
        <span class="text-sm text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
            {{ $product->category_name }}
        </span>
        <h1 class="text-3xl font-bold text-amber-900 mt-3">{{ $product->name }}</h1>
        <p class="text-gray-500 mt-2">{{ $product->description }}</p>

        {{-- DYNAMIC PRICE --}}
        <p class="text-2xl font-bold text-amber-700 mt-4" id="displayPrice">
            ₱{{ number_format($product->price, 2) }}
        </p>

        {{-- ADD TO CART FORM --}}
        @auth
        <form method="POST" action="{{ route('cart.add') }}" class="mt-6" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

           {{-- SIZE --}}
            @php
                $sizes = $product->size_options;

                // Kung string, i-decode
                if (is_string($sizes)) {
                    $sizes = json_decode($sizes, true);
                }

                // Kung still string (double encoded), i-decode ulit
                if (is_string($sizes)) {
                    $sizes = json_decode($sizes, true);
                }

                // Kung null o empty, gawing empty array
                if (!$sizes) {
                    $sizes = [];
                }
            @endphp

            @if(count($sizes) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Choose Size <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    @foreach($sizes as $index => $option)
                    @php
                        $option = is_array($option) ? $option : (array) $option;
                    @endphp
                    <label class="cursor-pointer">
                        <input type="radio" name="size"
                               value="{{ $option['size'] }}"
                               data-price="{{ $option['price'] }}"
                               class="hidden peer size-radio"
                               {{ $index === 0 ? 'checked' : '' }}>
                        <span class="border-2 border-gray-300 rounded-lg px-4 py-2 text-sm
                                     peer-checked:border-amber-900 peer-checked:bg-amber-900
                                     peer-checked:text-white transition block">
                            {{ $option['size'] }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- QUANTITY --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity
                </label>
                <input type="number" name="quantity" value="1" min="1" max="10"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-24
                           focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            @if($product->stock <= 0)
                <div class="w-full bg-red-100 text-red-600 py-3 rounded-xl
                            font-semibold text-center text-lg mt-6">
                    ❌ Out of Stock
                </div>
            @else
                <button type="button" onclick="handleAddToCart()"
                    class="w-full bg-amber-900 text-white py-3 rounded-xl font-semibold
                        hover:bg-amber-700 transition text-lg">
                     Add to Cart
                </button>
            @endif
        </form>

        {{-- CUSTOM POPUP --}}
        <div id="sizePopup"
             class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="absolute inset-0 bg-black bg-opacity-50"
                 onclick="closePopup()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full
                        mx-4 text-center z-10">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/shop_logo.jpg') }}"
                         alt="Logo"
                         class="h-20 w-20 object-cover rounded-full border-4 border-amber-900">
                </div>
                <h3 class="text-xl font-bold text-amber-900 mb-2">
                    Oops! Choose a Size
                </h3>
                <p class="text-gray-500 mb-6">
                    Please select a size before adding this item to your cart.
                </p>
                <button onclick="closePopup()"
                    class="w-full bg-amber-900 text-white py-2 rounded-xl font-semibold
                           hover:bg-amber-700 transition">
                    Okay, Got it!
                </button>
            </div>
        </div>

        {{-- HIDDEN URL para sa JS --}}
        <div id="cartUrl" data-url="{{ route('cart.add') }}" class="hidden"></div>

        @push('scripts')
        <script src="{{ asset('js/customer/product.js') }}"></script>
        @endpush


        @else
            <a href="{{ route('login') }}"
               class="block text-center w-full bg-amber-900 text-white py-3 rounded-xl
                      font-semibold hover:bg-amber-700 transition text-lg mt-6">
                Login to Order
            </a>
        @endauth
    </div>
</div>

{{-- RELATED PRODUCTS --}}
@if(!empty($related))
<div class="mt-12">
    <h2 class="text-2xl font-bold text-amber-900 mb-4">Related Products</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($related as $item)
        <a href="{{ route('menu.show', $item->slug) }}"
           class="bg-white rounded-xl shadow p-4 hover:shadow-md transition text-center">
            @if($item->image)
                <div class="relative w-full h-28 overflow-hidden rounded-lg mb-3">
                    @php $bgRelated = asset('images/product_images/' . $item->image); @endphp
                    <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                         style="background-image: url('{{ $bgRelated }}')">
                    </div>
                    <img src="{{ asset('images/product_images/' . $item->image) }}"
                         alt="{{ $item->name }}"
                         class="relative z-10 w-full h-full object-contain">
                </div>
            @else
                <div class="w-full h-28 bg-amber-100 rounded-lg flex items-center
                            justify-center mb-3">
                    <span class="text-4xl">☕</span>
                </div>
            @endif
            <p class="font-semibold text-amber-900 text-sm">{{ $item->name }}</p>
            <p class="text-amber-700 font-bold text-sm mt-1">
                ₱{{ number_format($item->price, 2) }}
            </p>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection