<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ═══════════════════════════════════
    //  SHOW LOGIN PAGE
    // ═══════════════════════════════════
    public function showLogin()
    {
        return view('auth.login');
    }

    // ═══════════════════════════════════
    //  PROCESS LOGIN
    // ═══════════════════════════════════
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Mali ang email o password.',
        ])->onlyInput('email');
    }

    // ═══════════════════════════════════
    //  SHOW REGISTER PAGE
    // ═══════════════════════════════════
    public function showRegister()
    {
        return view('auth.register');
    }

    // ═══════════════════════════════════
    //  PROCESS REGISTER
    // ═══════════════════════════════════
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
            'role'     => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    // ═══════════════════════════════════
    //  LOGOUT
    // ═══════════════════════════════════
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    // ═══════════════════════════════════
    //  REDIRECT TO GOOGLE
    // ═══════════════════════════════════
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // ═══════════════════════════════════
    //  HANDLE GOOGLE CALLBACK
    // ═══════════════════════════════════
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // I-check kung may existing user na
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Mag-login lang kung existing user
                Auth::login($user);
            } else {
                // Gumawa ng bagong user
                $user = User::create([
                    'name'     => $googleUser->name,
                    'email'    => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'role'     => 'customer',
                    'phone'    => null,
                    'address'  => null,
                ]);
                Auth::login($user);
            }

            // I-redirect base sa role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')
                            ->with('error', 'Google login failed. Please try again.');
        }
    }
}