<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            AuditService::logLoginSuccess($user);

            $redirectTo = $user && $user->role === 'admin'
                ? route('admin.dashboard')
                : route('staff.dashboard');

            return redirect()->intended($redirectTo)->with('success', 'Welcome back!');
        }

        AuditService::logLoginFailed($request->input('email'));

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        AuditService::logLogout($user);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if a redirect URL was provided
        $redirectTo = $request->input('redirect_to', '/');

        return redirect($redirectTo)->with('success', 'You have been logged out successfully.');
    }
}
