<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountController extends Controller
{
    // ═══════════════════════════════════
    //  SHOW ACCOUNT PAGE
    // ═══════════════════════════════════
    public function index()
    {
        $user   = Auth::user();
        $orders = $user->orders()->latest()->take(5)->get();

        return view('customer.account', compact('user', 'orders'));
    }

    // ═══════════════════════════════════
    //  UPDATE ACCOUNT INFO
    // ═══════════════════════════════════
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Account updated successfully!');
    }

    // ═══════════════════════════════════
    //  UPDATE PASSWORD
    // ═══════════════════════════════════
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        // I-check kung tama ang current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect!');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    // ═══════════════════════════════════
    //  UPDATE PROFILE PHOTO
    // ═══════════════════════════════════
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        // Gumawa ng folder kung wala pa
        $folder = public_path('images/profile_photos');
        if (!file_exists($folder)) mkdir($folder, 0755, true);

        // I-delete ang lumang photo
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        // I-save ang bagong photo
        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move($folder, $filename);
        $path = 'images/profile_photos/' . $filename;

        $user->update(['photo' => $path]);

        return back()->with('success', 'Profile photo updated!');
    }
}