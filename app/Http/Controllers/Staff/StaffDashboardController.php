<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todaysBookings = Booking::forStaffOperations()
            ->whereDate('check_in', $today)
            ->with(['guest', 'property'])
            ->orderBy('check_in')
            ->get();

        $upcomingCheckins = Booking::forStaffOperations()
            ->whereDate('check_in', '>', $today)
            ->with(['guest', 'property'])
            ->orderBy('check_in')
            ->limit(6)
            ->get();

        return view('dashboard.staff', [
            'today' => $today,
            'todaysBookings' => $todaysBookings,
            'upcomingCheckins' => $upcomingCheckins,
            'stats' => [
                'today_count' => $todaysBookings->count(),
                'upcoming_count' => $upcomingCheckins->count(),
            ],
        ]);
    }
}
