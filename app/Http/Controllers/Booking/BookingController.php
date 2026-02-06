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
     * Step 1: Display reservation form with pre-selected property
     */
    public function reservationForm(Request $request)
    {
        // Get property_id from URL or fail
        $propertyId = $request->get('property_id');
        
        if (!$propertyId) {
            return redirect()->route('home')->with('error', 'Please select a property to book.');
        }
        
        // Load the specific property with images
        $property = Property::with('images')
            ->where('id', $propertyId)
            ->where('is_active', true)
            ->where('status', 'APPROVED')
            ->firstOrFail();
            
        return view('booking.reservation', compact('property'));
    }

    /**
     * API: Get booked dates for a property (for calendar availability)
     */
    public function getBookedDates($propertyId)
    {
        // Get all confirmed/pending/partially paid bookings for this property
        $bookings = Booking::where('property_id', $propertyId)
            ->whereIn('status', ['PENDING_PAYMENT', 'PARTIALLY_PAID', 'PAID'])
            ->get(['check_in', 'check_out']);
        
        // Build array of all booked dates
        $bookedDates = [];
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->check_in)->startOfDay();
            $end = Carbon::parse($booking->check_out)->startOfDay();
            
            // Include all dates in the range (checkin to day before checkout)
            for ($date = $start; $date->lt($end); $date->addDay()) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }
        
        // Return as a proper JSON array (re-index to ensure it's serialized as array, not object)
        return response()->json(['booked_dates' => array_values(array_unique($bookedDates))]);
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
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:6',
            'property_id' => 'required|exists:properties,id',
        ]);

        try {
            $children = (int) ($validated['children'] ?? 0);

            // Get the specific property from validated data
            $property = Property::where('id', $validated['property_id'])
                ->where('is_active', true)
                ->where('status', 'APPROVED')
                ->firstOrFail();

            $checkInDate = Carbon::createFromFormat('Y-m-d', $validated['checkin'])->startOfDay();
            $checkOutDate = Carbon::createFromFormat('Y-m-d', $validated['checkout'])->startOfDay();
            $nights = $checkInDate->diffInDays($checkOutDate);
            
            if ($nights <= 0) {
                return back()->withErrors(['error' => 'Check-out date must be after check-in date.']);
            }

            // *** CRITICAL: Check for overlapping bookings on this property ***
            $overlappingBooking = Booking::where('property_id', $property->id)
                ->whereIn('status', ['PARTIALLY_PAID', 'PAID', 'PENDING_PAYMENT'])
                ->where(function($query) use ($checkInDate, $checkOutDate) {
                    // Two bookings overlap if one starts before the other ends
                    // Overlap occurs when: existing_checkin < new_checkout AND existing_checkout > new_checkin
                    $query->where('check_in', '<', $checkOutDate)
                          ->where('check_out', '>', $checkInDate);
                })
                ->first();

            if ($overlappingBooking) {
                return back()->withErrors([
                    'error' => 'Sorry, this property is not available for the selected dates. Please choose different dates.'
                ])->withInput();
            }

            $guest = Guest::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name' => $validated['full_name'],
                    'phone_e164' => $validated['phone'],
                ]
            );

            $nightlyRate = $property->nightly_rate ?? 0;
            $currency = $property->currency ?? 'KES';
            
            // Entire home booking - no room multiplier
            $accommodationSubtotal = $nightlyRate * $nights;
            $totalAmount = $accommodationSubtotal;

            $existing = Booking::where('guest_id', $guest->id)
                ->whereDate('check_in', $checkInDate->toDateString())
                ->whereDate('check_out', $checkOutDate->toDateString())
                ->where('property_id', $property->id)
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
                'rooms' => 1, // Entire home booking
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
