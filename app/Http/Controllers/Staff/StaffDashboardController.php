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
        $tomorrow = Carbon::tomorrow();
        $nextWeek = Carbon::today()->addDays(7);

        // Today's check-ins
        $todaysCheckins = Booking::with(['guest', 'property'])
            ->whereDate('check_in', $today)
            ->whereIn('status', ['CONFIRMED', 'PENDING_PAYMENT'])
            ->orderBy('check_in')
            ->get();

        // Today's check-outs
        $todaysCheckouts = Booking::with(['guest', 'property'])
            ->whereDate('check_out', $today)
            ->whereIn('status', ['CHECKED_IN', 'CONFIRMED'])
            ->orderBy('check_out')
            ->get();

        // Upcoming check-ins (next 7 days)
        $upcomingCheckins = Booking::with(['guest', 'property'])
            ->whereDate('check_in', '>', $today)
            ->whereDate('check_in', '<=', $nextWeek)
            ->whereIn('status', ['CONFIRMED', 'PENDING_PAYMENT'])
            ->orderBy('check_in')
            ->get();

        // Upcoming check-outs (next 7 days)
        $upcomingCheckouts = Booking::with(['guest', 'property'])
            ->whereDate('check_out', '>', $today)
            ->whereDate('check_out', '<=', $nextWeek)
            ->whereIn('status', ['CHECKED_IN', 'CONFIRMED'])
            ->orderBy('check_out')
            ->get();

        // NEW: Staff-specific pending tasks
        $pendingVerifications = \App\Models\MpesaManualSubmission::where('status', 'PENDING')->count();
        $myTickets = \App\Models\SupportTicket::where('assigned_to', auth()->id())
            ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->count();
        $unassignedTickets = \App\Models\SupportTicket::whereNull('assigned_to')
            ->where('status', 'OPEN')
            ->count();

        return view('dashboard.staff', [
            'today' => $today,
            'todaysCheckins' => $todaysCheckins,
            'todaysCheckouts' => $todaysCheckouts,
            'upcomingCheckins' => $upcomingCheckins,
            'upcomingCheckouts' => $upcomingCheckouts,
            'pendingVerifications' => $pendingVerifications,
            'myTickets' => $myTickets,
            'unassignedTickets' => $unassignedTickets,
            'stats' => [
                'today_checkins' => $todaysCheckins->count(),
                'today_checkouts' => $todaysCheckouts->count(),
                'upcoming_checkins' => $upcomingCheckins->count(),
                'upcoming_checkouts' => $upcomingCheckouts->count(),
            ],
        ]);
    }
}
