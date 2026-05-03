@extends('layouts.app')

@section('title', 'My Cart')

@section('content')

<div class="relative rounded-2xl overflow-hidden p-6 mb-10">

    <h1 class="text-3xl font-bold text-amber-900 mb-6">🛒 Your Cart</h1>

    @if(empty($items))
        {{-- EMPTY CART --}}
        <div class="text-center py-20 text-gray-400">
            
            <p class="text-lg mb-4">Your cart is empty.</p>
            <a href="{{ route('menu') }}"
               class="bg-amber-900 text-white px-6 py-3 rounded-full
                      hover:bg-amber-700 transition font-semibold">
                Browse Menu
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- CART ITEMS --}}
            <div class="lg:col-span-2 flex flex-col gap-4" id="cartItems">
                @foreach($items as $item)
                <div class="cart-item bg-white rounded-xl shadow p-4 flex gap-4 items-center"
                     id="item-{{ $item->id }}"
                     data-price="{{ $item->product_price }}">

                    {{-- IMAGE --}}
                    <div class="w-20 h-20 bg-amber-100 rounded-lg flex items-center
                                justify-center flex-shrink-0 overflow-hidden">
                        @if($item->product_image)
                            <img src="{{ asset('storage/' . $item->product_image) }}"
                                 class="w-full h-full object-cover rounded-lg">
                        @else
                            <span class="text-3xl">☕</span>
                        @endif
                    </div>

                    {{-- DETAILS --}}
                    <div class="flex-1">
                        <h3 class="font-bold text-amber-900">{{ $item->product_name }}</h3>
                        @if($item->size)
                            <p class="text-xs text-gray-400">Size: {{ $item->size }}</p>
                        @endif
                        <p class="text-amber-700 font-semibold mt-1">
                            ₱{{ number_format($item->product_price, 2) }}
                        </p>
                    </div>

                    {{-- QUANTITY UPDATE --}}
                    <div class="flex items-center gap-2">
                        <input type="number"
                               value="{{ $item->quantity }}"
                               min="1" max="10"
                               class="cart-quantity border border-gray-300 rounded-lg
                                      px-2 py-1 w-16 text-center text-sm
                                      focus:outline-none focus:ring-2 focus:ring-amber-500"
                               data-url="{{ route('cart.update', $item->id) }}">
                        <button type="button"
                                class="update-btn text-xs bg-gray-100 px-3 py-1 rounded-lg
                                       hover:bg-gray-200 transition"
                                data-id="{{ $item->id }}"
                                data-url="{{ route('cart.update', $item->id) }}">
                            Update
                        </button>
                    </div>

                    {{-- REMOVE --}}
                    <button type="button"
                            class="remove-item text-red-400 hover:text-red-600
                                   transition text-xl"
                            data-id="{{ $item->id }}"
                            data-url="{{ route('cart.remove', $item->id) }}">
                        ✕
                    </button>

                </div>
                @endforeach

                {{-- CLEAR CART --}}
                <div class="text-right">
                    <button type="button" id="clearCartBtn"
                            class="text-sm text-red-400 hover:text-red-600 transition">
                         Clear Cart
                    </button>
                </div>
            </div>

            {{-- ORDER SUMMARY --}}
            <div class="bg-white rounded-xl shadow p-6 h-fit">
                <h2 class="text-xl font-bold text-amber-900 mb-4">Order Summary</h2>

                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span id="cartTotal">₱{{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mb-4">
                    <span>Delivery Fee</span>
                    <span class="text-green-600">Free</span>
                </div>
                <hr class="mb-4">
                <div class="flex justify-between font-bold text-amber-900 text-lg mb-6">
                    <span>Total</span>
                    <span id="cartTotalBottom">₱{{ number_format($total, 2) }}</span>
                </div>

                {{-- CHECKOUT FORM --}}
                <form id="checkoutForm" action="{{ route('orders.checkout') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method
                        </label>
                        <select name="payment_method"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2
                                   text-sm focus:outline-none focus:ring-2
                                   focus:ring-amber-500">
                            <option value="cash"> Cash</option>
                            <option value="gcash"> GCash</option>
                            <option value="card"> Card</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <textarea name="notes" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2
                                   text-sm focus:outline-none focus:ring-2
                                   focus:ring-amber-500"
                            placeholder="e.g. No sugar, extra hot..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-amber-900 text-white py-3 rounded-xl
                               font-semibold hover:bg-amber-700 transition text-lg">
                         Proceed to Checkout
                    </button>
                </form>
            </div>

        </div>
    @endif

</div>

{{-- HIDDEN URL para sa JS --}}
<div id="cartUrl" data-url="{{ route('cart.add') }}" class="hidden"></div>

@push('scripts')
<script src="{{ asset('js/customer/cart.js') }}"></script>
@endpush


@endsection