<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\Booking;

class BookingSubmissionController extends Controller
{
    /**
     * Handle reservation form submission from frontend
     * Stores data in session and redirects to summary (doesn't create booking yet)
     */
    public function submitReservation(Request $request)
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
            'property_id' => 'nullable|integer',
        ]);

        try {
            // Store all form data in session for later booking creation
            session(['pending_booking_data' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'message' => $validated['message'] ?? '',
                'checkin' => $validated['checkin'],
                'checkout' => $validated['checkout'],
                'adult' => $validated['adult'],
                'children' => $validated['children'],
                'room_count' => $validated['room_count'],
                'room_type' => $validated['room_type'],
                'property_id' => $validated['property_id'],
            ]]);

            // Redirect to summary preview (without booking ref since no booking exists yet)
            return redirect()->route('booking.preview')->with('success', 'Please review your booking details.');
        } catch (\Exception $e) {
            \Log::error('Booking submission failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return back()->withErrors(['error' => 'Failed to process reservation. ' . $e->getMessage()]);
        }
    }
                        $exact = (clone $propertyQuery)
                            ->whereRaw('LOWER(name) = ?', [strtolower($roomType)])
                            ->first();

                        if ($exact) {
                            $property = $exact;
                            \Log::info('Property matched by exact name', ['property_id' => $property->id, 'name' => $property->name, 'room_type' => $roomType]);
                        } else {
                            // Then try contains match (case-insensitive)
                            $contains = (clone $propertyQuery)
                                ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($roomType) . '%'])
                                ->first();
                            if ($contains) {
                                $property = $contains;
                                \Log::info('Property matched by contains', ['property_id' => $property->id, 'name' => $property->name, 'room_type' => $roomType]);
                            }
                        }
                    }
                } else {
                    \Log::warning('room_type is empty', ['room_type' => $roomType]);
                }
            }

            if (!$property) {
                \Log::warning('No property matched selection', [
                    'room_type' => $roomType,
                    'property_id' => $propertyId,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'The selected room could not be found. Please reselect the room and try again.',
                ], 400);
            }

            // Calculate pricing from property rate
            $nightly_rate = (float) $property->nightly_rate;
            $roomCount = max(1, (int) $validated['room_count']);
            $accommodation_subtotal = $nightly_rate * $nights * $roomCount;
            $addons_subtotal = 0;
            $total_amount = $accommodation_subtotal + $addons_subtotal;

            // Create booking with PENDING_PAYMENT status
            $booking = Booking::create([
                'booking_ref' => $this->generateBookingReference(),
                'property_id' => $property->id,
                'guest_id' => $guest->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'adults' => $validated['adult'],
                'children' => $validated['children'],
                'rooms' => $roomCount,
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

            // Return success with summary page redirect
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully! Please review your details.',
                'booking_id' => $booking->id,
                'redirect_url' => route('booking.summary', ['booking' => $booking->id])
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

    /**
     * Generate a unique booking reference like BOOK-9XK2A7
     */
    private function generateBookingReference(): string
    {
        do {
            $ref = 'BOOK-' . Str::upper(Str::random(6));
        } while (Booking::where('booking_ref', $ref)->exists());

        return $ref;
    }
}
