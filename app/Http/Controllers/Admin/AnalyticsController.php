<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentIntent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard with charts and reports
     */
    public function index()
    {
        $last30Days = now()->subDays(30);

        // Revenue per day (last 30 days)
        $revenuePerDay = PaymentIntent::where('status', 'SUCCEEDED')
            ->where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Bookings per day (last 30 days)
        $bookingsPerDay = Booking::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment success vs failure
        $paymentSuccessFailed = PaymentIntent::where('created_at', '>=', $last30Days)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        // STK vs C2B usage
        $stkVsC2b = PaymentIntent::where('created_at', '>=', $last30Days)
            ->selectRaw('method, COUNT(*) as total, SUM(amount) as amount')
            ->groupBy('method')
            ->get();

        // Monthly summary
        $monthlyStats = [
            'total_revenue' => PaymentIntent::where('status', 'SUCCEEDED')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount'),
            'total_bookings' => Booking::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            'avg_booking_value' => PaymentIntent::where('status', 'SUCCEEDED')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->avg('amount') ?? 0,
            'success_rate' => $this->calculateSuccessRate(),
        ];

        return view('dashboard.analytics', [
            'revenuePerDay' => $revenuePerDay,
            'bookingsPerDay' => $bookingsPerDay,
            'paymentSuccessFailed' => $paymentSuccessFailed,
            'stkVsC2b' => $stkVsC2b,
            'monthlyStats' => $monthlyStats,
            'chartLabels' => $this->generateChartLabels(30),
        ]);
    }

    /**
     * Calculate payment success rate
     */
    private function calculateSuccessRate(): float
    {
        $total = PaymentIntent::count();
        if ($total === 0) return 0;
        
        $succeeded = PaymentIntent::where('status', 'SUCCEEDED')->count();
        return round(($succeeded / $total) * 100, 2);
    }

    /**
     * Generate date labels for charts
     */
    private function generateChartLabels(int $days): array
    {
        $labels = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M d');
        }
        return $labels;
    }
}
