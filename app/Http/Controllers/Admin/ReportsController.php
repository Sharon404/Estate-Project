<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Revenue by month
        $revenueByMonth = Receipt::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Revenue by property
        $revenueByProperty = Booking::with('property')
            ->whereBetween('check_in', [$startDate, $endDate])
            ->where('status', 'PAID')
            ->selectRaw('property_id, SUM(amount_paid) as total')
            ->groupBy('property_id')
            ->get();

        // Total revenue
        $totalRevenue = Receipt::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Payment methods breakdown
        $paymentMethods = Receipt::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.revenue', compact(
            'revenueByMonth',
            'revenueByProperty',
            'totalRevenue',
            'paymentMethods',
            'startDate',
            'endDate'
        ));
    }

    public function occupancy(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $properties = Property::withCount([
            'bookings as total_nights' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                      ->where('status', 'PAID')
                      ->selectRaw('SUM(DATEDIFF(check_out, check_in))');
            }
        ])->get();

        // Calculate occupancy rate
        $totalDays = now()->parse($startDate)->diffInDays(now()->parse($endDate));
        
        foreach ($properties as $property) {
            $property->occupancy_rate = $totalDays > 0 
                ? round(($property->total_nights / $totalDays) * 100, 2) 
                : 0;
        }

        return view('admin.reports.occupancy', compact('properties', 'startDate', 'endDate'));
    }

    public function cancellations(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $cancelledBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'CANCELLED')
            ->count();

        $cancellationRate = $totalBookings > 0 
            ? round(($cancelledBookings / $totalBookings) * 100, 2) 
            : 0;

        // Cancellations by property
        $cancellationsByProperty = Booking::with('property')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'CANCELLED')
            ->selectRaw('property_id, COUNT(*) as count')
            ->groupBy('property_id')
            ->get();

        // Recent cancelled bookings
        $recentCancellations = Booking::with(['property', 'guest'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'CANCELLED')
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.reports.cancellations', compact(
            'totalBookings',
            'cancelledBookings',
            'cancellationRate',
            'cancellationsByProperty',
            'recentCancellations',
            'startDate',
            'endDate'
        ));
    }
}
