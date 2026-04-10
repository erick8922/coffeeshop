<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-amber-900 text-white flex flex-col">
            <div class="px-6 py-5 border-b border-amber-700 flex items-center gap-3">
                {{-- LOGO --}}
                <img src="{{ asset('images/shop_logo.jpg') }}" 
                    alt="Logo" 
                    class="h-10 w-10 object-cover rounded-full border-2 border-white">
                {{-- SHOP NAME --}}
                <span class="text-lg font-bold">Admin Panel</span>
            </div>    

            <nav class="flex flex-col gap-1 p-4 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 rounded hover:bg-amber-700">
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2 rounded hover:bg-amber-700">
                    Products
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 rounded hover:bg-amber-700">
                    Orders
                </a>
                <hr class="border-amber-700 my-2">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded hover:bg-amber-700">
                    View Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 rounded hover:bg-amber-700">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-lg font-semibold">@yield('title', 'Dashboard')</h1>
                <span class="text-sm text-gray-500">
                    {{ auth()->user()->name }}
                </span>
            </header>

            <main class="p-6 flex-1">
                {{-- FLASH MESSAGES --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                         {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                         {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>