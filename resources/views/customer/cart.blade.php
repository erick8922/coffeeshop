@extends('layouts.app')

@section('title', 'My Cart')

@section('content')

<div class="relative rounded-2xl overflow-hidden p-6 mb-10
            bg-[url('{{ asset('images/counter1.jpg') }}')] bg-cover bg-center">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10">

        <h1 class="text-3xl font-bold text-white mb-6">Your Cart</h1>

        @if($items->isEmpty())
            {{-- EMPTY CART --}}
            <div class="text-center py-20 text-gray-200">
                
                <p class="text-lg mb-4">Your cart is empty.</p>
                <a href="{{ route('menu') }}"
                   class="bg-white text-amber-900 px-6 py-3 rounded-full
                          hover:bg-amber-100 transition font-semibold">
                    Browse Menu
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- CART ITEMS --}}
                <div class="lg:col-span-2 flex flex-col gap-4">
                    @foreach($items as $item)
                    <div class="bg-white/90 backdrop-blur rounded-xl shadow p-4 flex gap-4 items-center">

                        {{-- IMAGE --}}
                        <div class="w-20 h-20 bg-amber-100 rounded-lg flex items-center
                                    justify-center flex-shrink-0">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     class="w-full h-full object-cover rounded-lg">
                            @else
                                <span class="text-3xl">☕</span>
                            @endif
                        </div>

                        {{-- DETAILS --}}
                        <div class="flex-1">
                            <h3 class="font-bold text-amber-900">{{ $item->product->name }}</h3>
                            @if($item->size)
                                <p class="text-xs text-gray-400">Size: {{ $item->size }}</p>
                            @endif
                            <p class="text-amber-700 font-semibold mt-1">
                                ₱{{ number_format($item->product->price, 2) }}
                            </p>
                        </div>

                        {{-- QUANTITY UPDATE --}}
                        <form method="POST" action="{{ route('cart.update', $item->id) }}"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                   min="1" max="10"
                                   class="border border-gray-300 rounded-lg px-2 py-1 w-16
                                          text-center text-sm focus:outline-none
                                          focus:ring-2 focus:ring-amber-500">
                            <button type="submit"
                                    class="text-xs bg-gray-100 px-3 py-1 rounded-lg
                                           hover:bg-gray-200 transition">
                                Update
                            </button>
                        </form>

                        {{-- REMOVE --}}
                        <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-400 hover:text-red-600 transition text-xl"
                                    onclick="return confirm('Remove this item?')">
                                ✕
                            </button>
                        </form>

                    </div>
                    @endforeach

                    {{-- CLEAR CART --}}
                    <form method="POST" action="{{ route('cart.clear') }}" class="text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-300 hover:text-red-500 transition"
                                onclick="return confirm('Clear all items from your cart?')">
                            🗑️ Clear Cart
                        </button>
                    </form>
                </div>

                {{-- ORDER SUMMARY --}}
                <div class="bg-white/90 backdrop-blur rounded-xl shadow p-6 h-fit">
                    <h2 class="text-xl font-bold text-amber-900 mb-4">Order Summary</h2>

                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 mb-4">
                        <span>Delivery Fee</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <hr class="mb-4">
                    <div class="flex justify-between font-bold text-amber-900 text-lg mb-6">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>

                    {{-- CHECKOUT --}}
                    <form method="POST" action="{{ route('orders.checkout') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                            </label>
                            <select name="payment_method"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="cash">💵 Cash</option>
                                <option value="gcash">📱 GCash</option>
                                <option value="card">💳 Card</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <textarea name="notes" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-amber-500"
                                placeholder="e.g. No sugar, extra hot..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-amber-900 text-white py-3 rounded-xl font-semibold
                                   hover:bg-amber-700 transition text-lg">
                            ✅ Proceed to Checkout
                        </button>
                    </form>
                </div>

            </div>
        @endif

    </div>
</div>

@endsection