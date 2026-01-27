<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\PaymentIntent;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // KPI Cards Data
        $totalRevenue = PaymentIntent::where('status', 'SUCCEEDED')
            ->whereBetween('created_at', [$startOfMonth, Carbon::now()->endOfDay()])
            ->sum('amount');

        $totalBookings = Booking::whereBetween('created_at', [$startOfMonth, Carbon::now()->endOfDay()])
            ->count();

        $completedPayments = PaymentIntent::where('status', 'SUCCEEDED')
            ->whereBetween('created_at', [$startOfMonth, Carbon::now()->endOfDay()])
            ->count();

        $pendingOrFailedPayments = PaymentIntent::whereIn('status', ['FAILED', 'PENDING', 'INITIATED', 'UNDER_REVIEW'])
            ->whereBetween('created_at', [$startOfMonth, Carbon::now()->endOfDay()])
            ->count();

        $avgNights = Booking::whereBetween('created_at', [$startOfMonth, Carbon::now()->endOfDay()])
            ->avg('nights') ?? 0;

        // Bookings Overview Table
        $bookings = Booking::with(['guest', 'property'])
            ->latest('created_at')
            ->paginate(10);

        // Payment Monitoring
        $payments = PaymentIntent::with('booking')
            ->latest('created_at')
            ->limit(15)
            ->get();

        $transactions = BookingTransaction::with('booking')
            ->latest('posted_at')
            ->limit(10)
            ->get();

        // Payment Method Summary
        $paymentMethodSummary = PaymentIntent::selectRaw('method, status, count(*) as total')
            ->groupBy('method', 'status')
            ->get();

        return view('dashboard.admin', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'completedPayments' => $completedPayments,
            'pendingOrFailedPayments' => $pendingOrFailedPayments,
            'avgNights' => round($avgNights, 1),
            'bookings' => $bookings,
            'payments' => $payments,
            'transactions' => $transactions,
            'paymentMethodSummary' => $paymentMethodSummary,
            'today' => $today,
            'monthStart' => $startOfMonth,
        ]);
    }
}
