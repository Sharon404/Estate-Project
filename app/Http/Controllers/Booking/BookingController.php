<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Step 1: Display reservation form (no POST, no CSRF needed)
     */
    public function reservationForm()
    {
        return view('booking.reservation');
    }

    /**
     * Step 2: Display confirmation form (read-only) with @csrf POST target
     */
    public function confirmForm()
    {
        return view('booking.confirm');
    }

    /**
     * Step 3: Create booking after CSRF validation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'checkin' => 'required|date_format:Y-m-d',
            'checkout' => 'required|date_format:Y-m-d|after:checkin',
            'rooms' => 'required|integer|min:1|max:10',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:6',
        ]);

        try {
            $children = (int) ($validated['children'] ?? 0);

            $guest = Guest::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name' => $validated['full_name'],
                    'phone_e164' => $validated['phone'],
                ]
            );

            $checkInDate = Carbon::createFromFormat('Y-m-d', $validated['checkin'])->startOfDay();
            $checkOutDate = Carbon::createFromFormat('Y-m-d', $validated['checkout'])->startOfDay();
            $nights = $checkInDate->diffInDays($checkOutDate);
            if ($nights <= 0) {
                return back()->withErrors(['error' => 'Check-out date must be after check-in date.']);
            }

            $property = Property::where('is_active', true)->first();
            if (!$property) {
                return back()->withErrors(['error' => 'No properties available for booking.']);
            }

            $nightlyRate = $property->nightly_rate ?? 0;
            $currency = $property->currency ?? 'KES';
            $accommodationSubtotal = $nightlyRate * $nights * (int) $validated['rooms'];
            $totalAmount = $accommodationSubtotal;

            $existing = Booking::where('guest_id', $guest->id)
                ->whereDate('check_in', $checkInDate->toDateString())
                ->whereDate('check_out', $checkOutDate->toDateString())
                ->whereIn('status', ['DRAFT', 'PENDING_PAYMENT'])
                ->first();
            if ($existing) {
                return redirect()->route('payment.show', ['booking' => $existing->id])
                    ->with('info', 'You already have a pending booking for these dates. Proceed to payment.');
            }

            $bookingRef = $this->generateBookingReference();

            $booking = Booking::create([
                'booking_ref' => $bookingRef,
                'guest_id' => $guest->id,
                'property_id' => $property->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'adults' => (int) $validated['adults'],
                'children' => $children,
                'rooms' => (int) $validated['rooms'],
                'special_requests' => $validated['notes'] ?? '',
                'status' => 'PENDING_PAYMENT',
                'currency' => $currency,
                'nightly_rate' => $nightlyRate,
                'nights' => $nights,
                'accommodation_subtotal' => $accommodationSubtotal,
                'addons_subtotal' => 0,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'amount_due' => $totalAmount,
                'minimum_deposit' => null,
            ]);

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'booking_ref' => $bookingRef,
                'guest_email' => $guest->email,
            ]);

            return redirect()->route('payment.show', ['booking' => $booking->id])
                ->with('success', 'Booking created! Your reference: ' . $bookingRef);

        } catch (\Exception $e) {
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return back()->withErrors([
                'error' => 'Failed to create booking: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Optional summary view (kept for compatibility)
     */
    public function showSummary(Booking $booking)
    {
        return view('booking.summary', ['booking' => $booking]);
    }

    private function generateBookingReference(): string
    {
        do {
            $ref = 'BOOK-' . strtoupper(Str::random(8));
        } while (Booking::where('booking_ref', $ref)->exists());

        return $ref;
    }
}
