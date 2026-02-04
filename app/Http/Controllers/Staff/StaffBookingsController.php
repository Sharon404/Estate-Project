<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class StaffBookingsController extends Controller
{
    /**
     * Staff can view bookings but not edit status or refunds
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

        $bookings = $query->paginate(25);

        return view('staff.bookings.index', compact('bookings'));
    }

    /**
     * Staff can view booking details (read-only)
     */
    public function show(Booking $booking)
    {
        return view('staff.bookings.show', [
            'booking' => $booking->load(['guest', 'property', 'bookingTransactions', 'receipts'])
        ]);
    }
}
