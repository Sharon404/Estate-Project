<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentIntent;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalRevenue = PaymentIntent::where('status', 'SUCCEEDED')->sum('amount');
        $pendingBookings = Booking::whereIn('status', ['PENDING_PAYMENT', 'PARTIALLY_PAID'])->count();
        $failedOrPendingPayments = PaymentIntent::whereIn('status', ['FAILED', 'PENDING', 'INITIATED', 'UNDER_REVIEW'])->count();

        $recentBookings = Booking::with(['guest', 'property'])
            ->latest()
            ->limit(8)
            ->get();

        $paymentStatusSummary = PaymentIntent::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        $paymentMethodSummary = PaymentIntent::selectRaw('method, status, count(*) as total')
            ->groupBy('method', 'status')
            ->get();

        return view('dashboard.admin', [
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'pendingBookings' => $pendingBookings,
            'failedOrPendingPayments' => $failedOrPendingPayments,
            'recentBookings' => $recentBookings,
            'paymentStatusSummary' => $paymentStatusSummary,
            'paymentMethodSummary' => $paymentMethodSummary,
            'today' => Carbon::today(),
        ]);
    }
}
