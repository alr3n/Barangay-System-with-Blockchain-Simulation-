<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('is_active', (bool) $request->status);

        $users       = $query->latest()->paginate(15)->withQueryString();
        $adminCount  = User::where('role', 'admin')->count();
        $staffCount  = User::where('role', 'staff')->count();
        $activeCount = User::where('is_active', true)->count();

        return view('users.index', compact('users', 'adminCount', 'staffCount', 'activeCount'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,staff',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        ActivityLog::log('create', 'Users', "Created new user: {$user->name} ({$user->role})");

        return redirect()->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    public function show(User $user)
    {
        $recentLogs = ActivityLog::where('user_id', $user->id)->latest()->take(10)->get();
        return view('users.show', compact('user', 'recentLogs'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'      => 'required|in:admin,staff',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        ActivityLog::log('update', 'Users', "Updated user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User account updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $name = $user->name;
        $user->delete();
        ActivityLog::log('delete', 'Users', "Deleted user: {$name}");
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    // Profile (self)
    public function profile()
    {
        $user = auth()->user();
        $recentLogs = ActivityLog::where('user_id', $user->id)->latest()->take(10)->get();
        return view('users.profile', compact('user', 'recentLogs'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password'  => 'required',
                'password'          => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        ActivityLog::log('update', 'Profile', 'Updated own profile.');

        return back()->with('success', 'Profile updated successfully.');
    }
}
