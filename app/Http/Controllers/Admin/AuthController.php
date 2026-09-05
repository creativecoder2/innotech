<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $loginInput = trim($request->input('login') ?: $request->input('email', ''));

        $request->validate([
            'password' => 'required',
        ]);

        if (empty($loginInput)) {
            return back()->withErrors([
                'login' => 'Please enter your email address or phone number.',
            ])->withInput();
        }

        // Find user by email or phone
        $user = \App\Models\User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Check if admin account is disabled
        if (!$user->is_active) {
            return back()->withErrors([
                'login' => 'This account has been disabled. Please contact the Super Administrator.',
            ])->withInput();
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back to Innotech Admin Portal, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('/admin/login')->with('success', 'You have been logged out successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->filled('phone') ? trim($request->phone) : null;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Admin profile updated successfully.');
    }
}
