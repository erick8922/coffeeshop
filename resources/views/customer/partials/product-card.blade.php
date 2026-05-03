<div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">

    {{-- IMAGE --}}
    @if($product->image)
        <div class="relative w-full h-44 overflow-hidden rounded-t-xl">
            @php $bgImage = asset('storage/' . $product->image); @endphp
            <div class="absolute inset-0 bg-cover bg-center blur-md scale-110"
                 style="background-image: url('{{ $bgImage }}')">
            </div>
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
            {{ $product->category_name }}
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