<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaptureRequestData
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Capture request data in the request context
        $request->merge([
            '_audit_ip' => $request->ip(),
            '_audit_user_agent' => $request->header('User-Agent'),
            '_audit_user_id' => Auth::id(),
            '_audit_user_role' => $this->getUserRole(),
            '_audit_method' => $request->method(),
            '_audit_path' => $request->path(),
            '_audit_url' => $request->url(),
            '_audit_timestamp' => now(),
        ]);

        return $next($request);
    }

    /**
     * Get current user role
     */
    private function getUserRole(): string
    {
        if (!Auth::check()) {
            return 'guest';
        }

        $user = Auth::user();

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return 'admin';
        }

        return 'guest';
    }
}
