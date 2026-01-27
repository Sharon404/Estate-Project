<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    /**
     * Display bookings list with filtering
     */
    public function index(Request $request)
    {
        $query = Booking::with(['guest', 'property'])->latest('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by guest name
        if ($request->filled('guest')) {
            $query->whereHas('guest', function($q) {
                $q->where('full_name', 'like', '%' . request('guest') . '%')
                  ->orWhere('email', 'like', '%' . request('guest') . '%');
            });
        }

        // Filter by property
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $bookings = $query->paginate(25);
        $statuses = ['DRAFT', 'PENDING_PAYMENT', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED', 'NO_SHOW'];

        return view('dashboard.bookings', [
            'bookings' => $bookings,
            'statuses' => $statuses,
            'filters' => [
                'status' => $request->status,
                'guest' => $request->guest,
                'property_id' => $request->property_id,
            ]
        ]);
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        return view('dashboard.booking-detail', [
            'booking' => $booking->load(['guest', 'property', 'bookingTransactions', 'receipts', 'paymentIntents'])
        ]);
    }
}
