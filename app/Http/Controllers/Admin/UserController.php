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
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')->with('success', "New administrator '{$validated['name']}' created successfully!");
    }

    /**
     * Update the specified admin user.
     */
    public function update(Request $request, User $user)
    {
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

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Administrator '{$name}' deleted successfully.");
    }
}
