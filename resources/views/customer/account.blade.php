@extends('layouts.app')

@section('title', 'My Account')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-bold text-amber-900 mb-6">My Account</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- LEFT SIDE - PROFILE PHOTO --}}
        <div class="bg-white rounded-xl shadow p-6 text-center">

            {{-- PHOTO --}}
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                     alt="Profile Photo"
                     class="w-32 h-32 object-cover rounded-full mx-auto mb-4
                            border-4 border-amber-900">
            @else
                <div class="w-32 h-32 bg-amber-100 rounded-full mx-auto mb-4
                            flex items-center justify-center border-4 border-amber-900">
                </div>
            @endif

            <p class="font-bold text-amber-900 text-lg">{{ auth()->user()->name }}</p>
            <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 bg-amber-100 text-amber-700
                         rounded-full text-xs font-semibold capitalize">
                {{ auth()->user()->role }}
            </span>

            {{-- UPLOAD PHOTO --}}
            <form method="POST" action="{{ route('account.photo') }}"
                  enctype="multipart/form-data" class="mt-4">
                @csrf
                <label class="cursor-pointer block">
                    <input type="file" name="photo" accept="image/*"
                           class="hidden" onchange="this.form.submit()">
                    <span class="block w-full bg-amber-900 text-white py-2 rounded-lg
                                 text-sm hover:bg-amber-700 transition font-semibold">
                        Change Photo
                    </span>
                </label>
            </form>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="md:col-span-2 flex flex-col gap-6">

            {{-- ACCOUNT INFO FORM --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-amber-900 mb-4">
                    Personal Information
                </h2>

                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PATCH')

                    {{-- NAME --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PHONE --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
                        </label>
                        <input type="text" name="phone"
                               value="{{ old('phone', auth()->user()->phone) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500"
                               placeholder="09xxxxxxxxx">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <textarea name="address" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2
                                   text-sm focus:outline-none focus:ring-2
                                   focus:ring-amber-500"
                            placeholder="Davao City">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-amber-900 text-white py-2 rounded-xl
                               font-semibold hover:bg-amber-700 transition">
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- CHANGE PASSWORD FORM --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-amber-900 mb-4">
                    Change Password
                </h2>

                <form method="POST" action="{{ route('account.password') }}">
                    @csrf
                    @method('PATCH')

                    {{-- CURRENT PASSWORD --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Current Password
                        </label>
                        <input type="password" name="current_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500"
                               placeholder="••••••••">
                    </div>

                    {{-- NEW PASSWORD --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            New Password
                        </label>
                        <input type="password" name="new_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500"
                               placeholder="Min. 8 characters">
                    </div>

                    {{-- CONFIRM NEW PASSWORD --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm New Password
                        </label>
                        <input type="password" name="new_password_confirmation"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2
                                      text-sm focus:outline-none focus:ring-2
                                      focus:ring-amber-500"
                               placeholder="••••••••">
                    </div>

                    <button type="submit"
                        class="w-full bg-amber-900 text-white py-2 rounded-xl
                               font-semibold hover:bg-amber-700 transition">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-amber-900">Recent Orders</h2>
            <a href="{{ route('orders.index') }}"
               class="text-sm text-amber-600 hover:underline">
                View All →
            </a>
        </div>

        @if($orders->isEmpty())
            <p class="text-gray-400 text-sm text-center py-6">
                No orders yet.
            </p>
        @else
            <div class="flex flex-col gap-3">
                @foreach($orders as $order)
                @php
                    $colors = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'preparing' => 'bg-blue-100 text-blue-700',
                        'ready'     => 'bg-green-100 text-green-700',
                        'completed' => 'bg-gray-100 text-gray-600',
                        'cancelled' => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <div class="flex justify-between items-center py-2 border-b last:border-0">
                    <div>
                        <p class="font-semibold text-amber-900">
                            Order #{{ $order->id }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                     {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-amber-700 font-bold text-sm mt-1">
                            ₱{{ number_format($order->total_price, 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection