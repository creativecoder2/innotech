<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        $users = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created admin user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:6',
            'is_active' => 'nullable',
        ]);

        User::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'password' => Hash::make($validated['password']),
            'plain_password' => $validated['password'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')->with('success', "New administrator '{$validated['name']}' created successfully!");
    }

    /**
     * Update the specified admin user.
     */
    public function update(Request $request, User $user)
    {
        // Hierarchy protection: Secondary admins cannot edit Primary Super Admin (#1)
        if ($user->id === 1 && Auth::id() !== 1) {
            return back()->with('error', 'You do not have permission to modify the Primary Super Administrator account.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable',
        ]);

        $user->name = $validated['name'];
        $user->role = $validated['role'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ? trim($validated['phone']) : null;

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
            $user->plain_password = $validated['password'];
        }

        // Logged-in admin cannot disable themselves
        if (Auth::id() === $user->id) {
            $user->is_active = true;
        } else {
            $user->is_active = $request->has('is_active');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Administrator '{$user->name}' updated successfully!");
    }

    /**
     * Toggle active/disabled status.
     */
    public function toggleStatus(User $user)
    {
        // Security check: logged-in user cannot disable themselves
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot disable your own active account while logged in.');
        }

        // Security check: Primary Super Admin (#1) can never be disabled
        if ($user->id === 1) {
            return back()->with('error', 'The Primary Super Administrator account cannot be disabled.');
        }

        // Security check: Only Primary Admin can toggle status of other Super Admins
        if (Auth::id() !== 1 && $user->role === 'Super Admin') {
            return back()->with('error', 'You do not have permission to disable or enable other Super Administrators.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "Admin user '{$user->name}' has been {$statusText} successfully.");
    }

    /**
     * Remove the specified admin user.
     */
    public function destroy(User $user)
    {
        // Security check: logged-in user cannot delete themselves
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Security check: Primary Super Admin (#1) can never be deleted
        if ($user->id === 1) {
            return back()->with('error', 'The Primary Super Administrator account cannot be deleted.');
        }

        // Security check: Secondary admins cannot delete Super Admins
        if (Auth::id() !== 1 && $user->role === 'Super Admin') {
            return back()->with('error', 'Only the Primary Super Administrator can delete other Super Administrators.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Administrator '{$name}' deleted successfully.");
    }
}
