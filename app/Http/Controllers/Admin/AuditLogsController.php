<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    /**
     * Display audit logs with filtering
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by resource type
        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }

        $logs = $query->paginate(50);

        // Get available filters
        $actions = AuditLog::distinct('action')->pluck('action');
        $resourceTypes = AuditLog::distinct('resource_type')->pluck('resource_type');

        return view('dashboard.audit-logs', [
            'logs' => $logs,
            'actions' => $actions,
            'resourceTypes' => $resourceTypes,
            'filters' => [
                'action' => $request->action,
                'resource_type' => $request->resource_type,
                'user_id' => $request->user_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ]
        ]);
    }

    /**
     * Show detailed audit log entry
     */
    public function show(AuditLog $auditLog)
    {
        return view('dashboard.audit-log-detail', [
            'log' => $auditLog->load('user')
        ]);
    }
}
