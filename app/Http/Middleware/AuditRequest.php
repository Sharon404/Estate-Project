<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditRequest
{
    /**
     * Handle an incoming request and create an audit entry for authenticated users.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only audit authenticated users to avoid noise from guests and bots.
        if (!Auth::check()) {
            return $response;
        }

        try {
            $route = $request->route();

            AuditService::createLog([
                'user_id' => Auth::id(),
                'action' => 'route_' . strtolower($request->method()),
                'resource_type' => 'Route',
                'resource_id' => null,
                'status' => $response->getStatusCode() >= 400 ? 'failed' : 'success',
                'metadata' => [
                    'route_name' => $route?->getName(),
                    'controller_action' => $route?->getActionName(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'status_code' => $response->getStatusCode(),
                    'request_id' => $request->header('X-Request-Id'),
                ],
                'description' => sprintf('%s %s (%s)', $request->method(), $request->path(), $route?->getName() ?? 'unnamed'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to write audit trail for request', [
                'error' => $e->getMessage(),
                'path' => $request->path(),
                'method' => $request->method(),
            ]);
        }

        return $response;
    }
}
