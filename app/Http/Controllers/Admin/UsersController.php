<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['loginHistory' => function($query) {
            $query->latest('login_at')->limit(20);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,staff',
            'kyc_status' => 'required|in:PENDING,VERIFIED,REJECTED',
            'kyc_notes' => 'nullable|string',
        ]);

        if ($validated['kyc_status'] === 'VERIFIED' && $user->kyc_status !== 'VERIFIED') {
            $validated['kyc_verified_at'] = now();
            $validated['kyc_verified_by'] = auth()->id();
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }

    public function loginHistory(User $user)
    {
        $history = $user->loginHistory()
            ->latest('login_at')
            ->paginate(50);

        return view('admin.users.login-history', compact('user', 'history'));
    }
}
