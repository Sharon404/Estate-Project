<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuditController extends Controller
{
    /**
     * Get all audit logs with filters
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filter by action
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        // Filter by resource type
        if ($request->filled('resource_type')) {
            $query->byResourceType($request->resource_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->byIpAddress($request->ip_address);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'success') {
                $query->successful();
            } elseif ($request->status === 'failed') {
                $query->failed();
            }
        }

        // Filter by user role
        if ($request->filled('user_role')) {
            $query->byRole($request->user_role);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Order and paginate
        $logs = $query->recentFirst()
            ->with('user')
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'success' => true,
            'total' => $logs->total(),
            'per_page' => $logs->perPage(),
            'current_page' => $logs->currentPage(),
            'last_page' => $logs->lastPage(),
            'data' => $logs->map(function ($log) {
                return $this->formatLog($log);
            })
        ]);
    }

    /**
     * Get audit logs for a specific resource
     */
    public function forResource(Request $request)
    {
        $validated = $request->validate([
            'resource_type' => 'required|string',
            'resource_id' => 'required|integer'
        ]);

        $logs = AuditService::getResourceAudit(
            $validated['resource_type'],
            $validated['resource_id'],
            $request->limit ?? 50
        );

        return response()->json([
            'success' => true,
            'resource' => "{$validated['resource_type']} #{$validated['resource_id']}",
            'count' => $logs->count(),
            'data' => $logs->map(fn($log) => $this->formatLog($log))
        ]);
    }

    /**
     * Get audit logs for a specific user
     */
    public function forUser($userId, Request $request)
    {
        $logs = AuditService::getUserAudit(
            $userId,
            $request->limit ?? 100
        );

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'count' => $logs->count(),
            'data' => $logs->map(fn($log) => $this->formatLog($log))
        ]);
    }

    /**
     * Get audit logs for a specific action
     */
    public function byAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string'
        ]);

        $logs = AuditService::getActionAudit(
            $validated['action'],
            $request->limit ?? 50
        );

        return response()->json([
            'success' => true,
            'action' => $validated['action'],
            'count' => $logs->count(),
            'data' => $logs->map(fn($log) => $this->formatLog($log))
        ]);
    }

    /**
     * Get audit logs by IP address
     */
    public function byIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip'
        ]);

        $logs = AuditService::getIpAudit(
            $validated['ip_address'],
            $request->limit ?? 100
        );

        return response()->json([
            'success' => true,
            'ip_address' => $validated['ip_address'],
            'count' => $logs->count(),
            'data' => $logs->map(fn($log) => $this->formatLog($log))
        ]);
    }

    /**
     * Get suspicious activity
     */
    public function suspiciousActivity(Request $request)
    {
        $minutes = $request->minutes ?? 60;
        $threshold = $request->threshold ?? 5;

        $suspicious = AuditService::getSuspiciousActivity($minutes, $threshold);

        return response()->json([
            'success' => true,
            'time_period' => "{$minutes} minutes",
            'failure_threshold' => $threshold,
            'suspicious_ips' => $suspicious->keys(),
            'details' => $suspicious->map(function ($logs, $ip) {
                return [
                    'ip' => $ip,
                    'failure_count' => $logs->count(),
                    'first_attempt' => $logs->last()?->created_at,
                    'last_attempt' => $logs->first()?->created_at,
                    'actions' => $logs->pluck('action')->unique()->values()
                ];
            })->values()
        ]);
    }

    /**
     * Get audit statistics
     */
    public function statistics(Request $request)
    {
        $days = $request->days ?? 30;
        $stats = AuditService::getStatistics($days);

        return response()->json([
            'success' => true,
            'period_days' => $days,
            'total_logs' => $stats['total_logs'],
            'successful_actions' => $stats['successful_actions'],
            'failed_actions' => $stats['failed_actions'],
            'success_rate' => $stats['total_logs'] > 0 
                ? round(($stats['successful_actions'] / $stats['total_logs']) * 100, 2)
                : 0,
            'by_action' => $stats['by_action'],
            'by_resource_type' => $stats['by_resource_type'],
            'unique_ips' => $stats['unique_ips'],
            'top_users' => collect($stats['by_user'])
                ->sortByDesc('count')
                ->take(10)
                ->map(fn($item) => [
                    'user_id' => $item->user_id,
                    'user_name' => $item->user?->name,
                    'action_count' => $item->count
                ])
        ]);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('resource_type')) {
            $query->byResourceType($request->resource_type);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $logs = $query->recentFirst()->with('user')->get();

        // Format as CSV
        $csv = "Timestamp,User,Action,Resource,Status,IP Address,Description\n";

        foreach ($logs as $log) {
            $csv .= '"' . $log->created_at->format('Y-m-d H:i:s') . '",';
            $csv .= '"' . ($log->user?->name ?? 'System') . '",';
            $csv .= '"' . $log->getActionLabel() . '",';
            $csv .= '"' . $log->getResourceLabel() . '",';
            $csv .= '"' . ucfirst($log->status) . '",';
            $csv .= '"' . $log->ip_address . '",';
            $csv .= '"' . $log->description . '"' . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="audit-logs-' . now()->format('Y-m-d-His') . '.csv"');
    }

    /**
     * Get single audit log details
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => array_merge(
                $this->formatLog($log),
                [
                    'full_user_agent' => $log->user_agent,
                    'changes_before' => $log->getChangesBefore(),
                    'changes_after' => $log->getChangesAfter(),
                    'changed_fields' => $log->getChangedFields()
                ]
            )
        ]);
    }

    /**
     * Format audit log for response
     */
    private function formatLog($log)
    {
        return [
            'id' => $log->id,
            'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
            'user_id' => $log->user_id,
            'user_name' => $log->user?->name ?? 'System',
            'action' => $log->action,
            'action_label' => $log->getActionLabel(),
            'resource_type' => $log->resource_type,
            'resource_id' => $log->resource_id,
            'resource_label' => $log->getResourceLabel(),
            'status' => $log->status,
            'description' => $log->description,
            'ip_address' => $log->ip_address,
            'user_agent_short' => substr($log->user_agent, 0, 50) . '...',
            'user_role' => $log->user_role,
            'error_message' => $log->error_message
        ];
    }
}
