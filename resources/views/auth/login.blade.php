@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl shadow-md p-8 w-full max-w-md hover:bg-amber-100 hover:border-amber-400 hover:shadow-md transition">
        
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/shop_logo.jpg') }}" 
                alt="Logo" 
                class="h-32 w-32 object-cover rounded-full border-4 border-white">
        </div>
        <h2 class="text-2xl font-bold text-amber-900 text-center mb-6">
            Welcome Back!
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="email@example.com" required>
                @error('email')
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
                    placeholder="••••••••" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- REMEMBER ME --}}
            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="text-sm text-gray-600">
                    Remember me
                </label>
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                class="w-full bg-amber-900 text-white py-2 rounded-lg
                       hover:bg-amber-700 font-semibold transition">
                Login
            </button>
        </form>

                {{-- DIVIDER --}}
        <div class="flex items-center gap-3 my-4">
            <hr class="flex-1 border-gray-300">
            <span class="text-sm text-gray-400">or</span>
            <hr class="flex-1 border-gray-300">
        </div>

        {{-- GOOGLE LOGIN BUTTON --}}
        <a href="{{ route('auth.google') }}"
        class="w-full flex items-center justify-center gap-3 border-2 border-gray-300 
                rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 
                hover:bg-gray-50 hover:border-gray-400 transition">
            <img src="https://www.google.com/favicon.ico" 
                alt="Google" 
                class="w-5 h-5">
            Continue with Google
        </a>

        <p class="text-center text-sm text-gray-600 mt-4">
            Have no account?
            <a href="{{ route('register') }}" class="text-amber-700 font-medium hover:underline">
                Register here
            </a>
        </p>

    </div>
</div>
@endsection