<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Normalize roles to handle different casing (e.g., "Admin", "admin")
        $requiredRoles = array_map('strtolower', $roles);
        $userRole = strtolower(auth()->user()->role ?? '');

        if (in_array($userRole, $requiredRoles)) {
            return $next($request);
        }

        // Gracefully handle missing/incorrect role with a redirect instead of a 403 page
        return redirect()->route('login')->withErrors([
            'auth' => 'Unauthorized: your account does not have access to this area.',
        ]);
    }
}
