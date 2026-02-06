<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
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
        $statuses = ['DRAFT', 'PENDING_PAYMENT', 'PARTIALLY_PAID', 'PAID', 'CANCELLED', 'EXPIRED'];

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

    /**
     * Edit booking (admin only)
     */
    public function edit(Booking $booking)
    {
        $statuses = ['DRAFT', 'PENDING_PAYMENT', 'PARTIALLY_PAID', 'PAID', 'CANCELLED', 'EXPIRED'];

        return view('dashboard.booking-edit', [
            'booking' => $booking->load(['guest', 'property']),
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update booking (admin only)
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'check_in' => 'required|date_format:Y-m-d',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
            'adults' => 'required|integer|min:1|max:20',
            'children' => 'nullable|integer|min:0|max:20',
            'rooms' => 'required|integer|min:1|max:10',
            'nightly_rate' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'status' => 'required|in:DRAFT,PENDING_PAYMENT,PARTIALLY_PAID,PAID,CANCELLED,EXPIRED',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $checkInDate = Carbon::createFromFormat('Y-m-d', $validated['check_in'])->startOfDay();
        $checkOutDate = Carbon::createFromFormat('Y-m-d', $validated['check_out'])->startOfDay();
        $nights = $checkInDate->diffInDays($checkOutDate);

        if ($nights <= 0) {
            return back()->withErrors(['error' => 'Check-out date must be after check-in date.'])->withInput();
        }

        // *** CRITICAL: Check for overlapping bookings when editing dates ***
        $overlappingBooking = Booking::where('property_id', $booking->property_id)
            ->where('id', '!=', $booking->id) // Exclude current booking
            ->whereIn('status', ['PARTIALLY_PAID', 'PAID', 'PENDING_PAYMENT'])
            ->where(function($query) use ($checkInDate, $checkOutDate) {
                // Two bookings overlap if one starts before the other ends
                $query->where('check_in', '<', $checkOutDate)
                      ->where('check_out', '>', $checkInDate);
            })
            ->first();

        if ($overlappingBooking) {
            return back()->withErrors([
                'error' => 'These dates overlap with booking #' . $overlappingBooking->booking_ref . '. Please choose different dates.'
            ])->withInput();
        }

        $rooms = (int) $validated['rooms'];
        $nightlyRate = (float) $validated['nightly_rate'];
        $accommodationSubtotal = $nightlyRate * $nights * $rooms;
        $totalAmount = (float) $validated['total_amount'];
        $amountPaid = (float) $validated['amount_paid'];
        $amountDue = max(0, $totalAmount - $amountPaid);

        $booking->update([
            'check_in' => $checkInDate,
            'check_out' => $checkOutDate,
            'adults' => (int) $validated['adults'],
            'children' => (int) ($validated['children'] ?? 0),
            'rooms' => $rooms,
            'nightly_rate' => $nightlyRate,
            'nights' => $nights,
            'accommodation_subtotal' => $accommodationSubtotal,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'amount_due' => $amountDue,
            'status' => $validated['status'],
            'special_requests' => $validated['special_requests'] ?? '',
        ]);

        return redirect()->route('admin.booking-detail', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Delete booking (admin only)
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings')
            ->with('success', 'Booking deleted successfully.');
    }
}
