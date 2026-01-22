<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        // Different stats for different roles
        if ($user->role === 'ADMIN') {
            $stats = [
                'total_users' => User::count(),
                'admin_users' => User::where('role', 'ADMIN')->count(),
                'staff_users' => User::where('role', 'STAFF')->count(),
                'total_revenue' => 1856,
                'total_orders' => 1542,
                'growth_rate' => 87,
            ];

            return view('dashboard.admin', [
                'user' => $user,
                'stats' => $stats,
            ]);
        }

        // Staff dashboard
        $stats = [
            'assigned_tasks' => 12,
            'completed_tasks' => 8,
            'pending_tasks' => 4,
            'total_orders' => 523,
        ];

        return view('dashboard.staff', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}
