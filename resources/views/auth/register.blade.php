@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-md p-8 w-full max-w-md">

        <h2 class="text-2xl font-bold text-amber-900 text-center mb-6">
            ☕ Create Your Account
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- NAME --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Juan Dela Cruz" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="email@example.com" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PHONE --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="09XXXXXXXXX">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ADDRESS --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Address
                </label>
                <textarea name="address" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Davao City">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Minimum 8 characters" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="••••••••" required>
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                class="w-full bg-amber-900 text-white py-2 rounded-lg
                       hover:bg-amber-700 font-semibold transition">
                Sign Up
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-amber-700 font-medium hover:underline">
                Log in here
            </a>
        </p>

    </div>
</div>
@endsection