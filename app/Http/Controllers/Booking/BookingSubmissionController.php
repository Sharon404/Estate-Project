<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BookingSubmissionController extends Controller
{
    /**
     * Handle reservation form submission from frontend
     * Creates a booking and returns JSON with payment redirect URL
     */
    public function submitReservation(Request $request): JsonResponse
    {
        // Log the incoming request for debugging
        \Log::info('Booking submission request:', [
            'all' => $request->all(),
            'checkin' => $request->input('checkin'),
            'checkout' => $request->input('checkout'),
        ]);

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
            'checkin' => 'required|date_format:n/j/Y',
            'checkout' => 'required|date_format:n/j/Y|after:checkin',
            'adult' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'room_count' => 'required|integer|min:1',
            'room_type' => 'required|string',
        ]);

        try {
            // Find or create guest
            $guest = Guest::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name' => $validated['name'],
                    'phone_e164' => $validated['phone'],
                ]
            );

            // Parse dates
            $checkInDate = Carbon::createFromFormat('n/j/Y', $validated['checkin'])->startOfDay();
            $checkOutDate = Carbon::createFromFormat('n/j/Y', $validated['checkout'])->startOfDay();
            $nights = $checkInDate->diffInDays($checkOutDate);

            // Get a property based on room type (for now, use first property)
            $property = Property::first();
            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'No properties available. Please try again later.'
                ], 400);
            }

            // Calculate pricing
            $nightly_rate = 15000; // Default KES rate
            $accommodation_subtotal = $nightly_rate * $nights;
            $addons_subtotal = 0;
            $total_amount = $accommodation_subtotal + $addons_subtotal;

            // Create booking with PENDING_PAYMENT status
            $booking = Booking::create([
                'booking_ref' => 'BK-' . Str::upper(Str::random(8)),
                'property_id' => $property->id,
                'guest_id' => $guest->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'adults' => $validated['adult'],
                'children' => $validated['children'],
                'special_requests' => $validated['message'] ?? null,
                'status' => 'PENDING_PAYMENT',
                'currency' => 'KES',
                'nightly_rate' => $nightly_rate,
                'nights' => $nights,
                'accommodation_subtotal' => $accommodation_subtotal,
                'addons_subtotal' => $addons_subtotal,
                'total_amount' => $total_amount,
                'amount_paid' => 0,
                'amount_due' => $total_amount,
                'minimum_deposit' => $total_amount,
            ]);

            // Return success with payment redirect URL
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully! Redirecting to payment...',
                'booking_id' => $booking->id,
                'redirect_url' => route('payment.show', ['booking' => $booking->id])
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Booking submission error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating your booking. Please try again.'
            ], 500);
        }
    }
}
