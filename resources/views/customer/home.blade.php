@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- HERO SECTION --}}
<div class="relative rounded-2xl p-10 mb-10 text-center text-white overflow-hidden
     bg-[url('{{ asset('images/banner_image.jpg') }}')] bg-cover bg-center">

    <!-- Optional overlay para readable ang text -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="relative z-10">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/shop_logo.jpg') }}" 
                alt="Logo" 
                class="h-32 w-32 object-cover rounded-full border-4 border-white">
        </div>

         {{-- GREETING --}}
        @auth
            <h1 class="text-4xl font-bold mb-3">
                Welcome back, {{ auth()->user()->name }}!
            </h1>
        @else
            <h1 class="text-4xl font-bold mb-3">Welcome!</h1>
        @endauth

        <p class="text-amber-200 text-lg mb-6">
            We are serving the finest coffee in Banay-banay, Davao Oriental.
        </p>

        <a href="{{ route('menu') }}"
           class="bg-white/90 backdrop-blur text-amber-900 font-semibold px-6 py-3 rounded-full
                  hover:bg-white hover:scale-105 transition-all duration-300 shadow-md inline-block">
            View Menu
        </a>
    </div>
</div>

{{-- CATEGORIES --}}
<div class="mb-10">
    <h2 class="text-2xl font-bold text-amber-900 mb-4">Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('menu', ['category' => $category->slug]) }}"
        class="group bg-white border border-amber-200 rounded-xl p-5 text-center 
                hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

            <p class="font-semibold text-amber-900 group-hover:text-amber-700">
                {{ $category->name }}
            </p>

            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                {{ $category->description }}
            </p>
        </a>
        @endforeach
    </div>
</div>

{{-- FEATURED PRODUCTS --}}
<div>
    <h2 class="text-2xl font-bold text-amber-900 mb-4">Featured Drinks</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featured as $product)
        <div class="bg-white rounded-xl shadow hover:shadow-xl 
            hover:-translate-y-1 transition-all duration-300 overflow-hidden">

            {{-- IMAGE --}}
            @if($product->image)
                <div class="relative w-full h-48 overflow-hidden rounded-t-xl">
                    {{-- BLURRED BACKGROUND --}}
                    <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                        style="background-image: url('{{ Storage::url($product->image) }}')">
                    </div>
                    {{-- ACTUAL IMAGE --}}
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="relative z-10 w-full h-full object-contain">
                </div>
            @else
                <div class="w-full h-48 bg-amber-100 flex items-center justify-center rounded-t-xl">
                    <span class="text-5xl">☕</span>
                </div>
            @endif

            {{-- DETAILS --}}
            <div class="p-4">
                <h3 class="font-bold text-lg text-amber-900">{{ $product->name }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ $product->description }}</p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-amber-700 font-bold text-lg">
                        ₱{{ number_format($product->price, 2) }}
                    </span>
                    <a href="{{ route('menu.show', $product->slug) }}"
                       class="bg-amber-900 text-white px-4 py-1 rounded-full text-sm
                              hover:bg-amber-700 transition">
                        Order Now
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</div>

@endsection