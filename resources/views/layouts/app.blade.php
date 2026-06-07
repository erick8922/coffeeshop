<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CoffeeShop — @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">

   

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- NAVBAR --}}
    <nav class="bg-amber-900 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/shop_logo.jpg') }}"
                     alt="Logo"
                     class="h-10 w-auto object-cover rounded-full border-2 border-white">
                <span class="text-xl font-bold tracking-wide">CoffeeShop</span>
            </a>

            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('menu') }}" class="hover:text-amber-200">Menu</a>

                @auth
                    <a href="{{ route('cart.index') }}" class="hover:text-amber-200 relative">
                        Cart
                        
                    </a>

                    <a href="{{ route('orders.index') }}" class="hover:text-amber-200">
                        My Orders
                    </a>
                    <a href="{{ route('account.index') }}" class="hover:text-amber-200">
                        My Account
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-200">
                            Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-amber-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-amber-200">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-amber-600 px-3 py-1 rounded hover:bg-amber-500">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    <div class="max-w-6xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700
                        px-4 py-3 rounded mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700
                        px-4 py-3 rounded mb-4">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-amber-900 text-white py-8 mt-10">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/shop_logo.jpg') }}"
                     alt="Logo"
                     class="h-16 w-16 object-cover rounded-full border-2 border-white">
            </div>
            <p class="text-lg font-bold mb-1">CoffeeShop</p>
            <p class="text-amber-200 text-sm mb-3">Banay-banay, Davao Oriental</p>
            <hr class="border-amber-700 mb-3">
            <p class="text-amber-300 text-xs">
                © {{ date('Y') }} CoffeeShop. All rights reserved.
            </p>
        </div>
    </footer>

     {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    {{-- CUSTOM JS --}}
    <script src="{{ asset('js/custom.js') }}"></script>

    @stack('scripts')

</body>
</html>