<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\ConfirmBookingRequest;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Property;
use App\Services\BookingService;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected AuditService $auditService;

    public function __construct(BookingService $bookingService, AuditService $auditService)
    {
        $this->bookingService = $bookingService;
        $this->auditService = $auditService;
    }

    public function summary(Booking $booking): JsonResponse
    {
        try {
            if ($booking->status !== 'DRAFT') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot get summary for booking in {$booking->status} status",
                ], 400);
            }

            $summary = $this->bookingService->getConfirmationSummary($booking);

            return response()->json(
                [
                    'success' => true,
                    'data' => $summary,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Booking summary retrieval failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking summary',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show booking confirm details page.
     * User fills guest info and confirms booking data before creating booking.
     *
     * GET /booking/confirm-details
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmDetails()
    {
        return view('booking.confirm-details');
    }

    /**
     * Confirm and create booking from form submission.
     *
     * POST /booking/create
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createBooking(Request $request)
    {
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
            'room_type' => 'nullable|string',
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

            // Get property (using room_type as fallback property name or ID)
            $property = null;
            $roomType = trim($validated['room_type'] ?? '');
            
            if (!empty($roomType)) {
                // Try numeric ID first
                if (ctype_digit($roomType)) {
                    $property = Property::where('status', 'APPROVED')
                        ->where('pending_removal', false)
                        ->where('id', (int) $roomType)
                        ->first();
                }
                
                // Try name match
                if (!$property) {
                    $property = Property::where('status', 'APPROVED')
                        ->where('pending_removal', false)
                        ->whereRaw('LOWER(name) = ?', [strtolower($roomType)])
                        ->first();
                }
                
                // Try contains match
                if (!$property) {
                    $property = Property::where('status', 'APPROVED')
                        ->where('pending_removal', false)
                        ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($roomType) . '%'])
                        ->first();
                }
            }

            // If no property found, use first approved property
            if (!$property) {
                $property = Property::where('status', 'APPROVED')
                    ->where('pending_removal', false)
                    ->first();
            }

            if (!$property) {
                return back()->withErrors(['error' => 'No property available for booking.']);
            }

            // Create booking with PENDING_PAYMENT status
            $booking = Booking::create([
                'booking_ref' => $this->generateBookingReference(),
                'guest_id' => $guest->id,
                'property_id' => $property->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'nights' => $nights,
                'num_adults' => $validated['adult'],
                'num_children' => $validated['children'],
                'rooms' => $validated['room_count'],
                'special_requests' => $validated['message'] ?? '',
                'status' => 'PENDING_PAYMENT',
            ]);

            // Clear session data if any
            session()->forget('pending_booking_data');
            session()->forget('booking_data');

            // Redirect to payment page
            return redirect()->route('payment.show', ['booking' => $booking->id])
                ->with('success', 'Booking confirmed! Booking reference: ' . $booking->booking_ref);
        } catch (\Exception $e) {
            \Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return back()->withErrors(['error' => 'Failed to create booking. ' . $e->getMessage()]);
        }
    }

    /**
     * Generate unique booking reference.
     *
     * @return string
     */
    private function generateBookingReference(): string
    {
        do {
            $ref = 'BOOK-' . strtoupper(Str::random(8));
        } while (Booking::where('booking_ref', $ref)->exists());

        return $ref;
    }

    /**
     * Show booking summary/confirmation page.
     * Guest reviews all details and chooses to pay or edit.
     *
     * GET /bookings/{id}/summary
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function showSummary(Booking $booking)
    {
        if ($booking->status !== 'PENDING_PAYMENT') {
            return redirect('/')->with('error', 'This booking cannot be reviewed.');
        }

        return view('booking.summary', [
            'booking' => $booking,
        ]);
    }

    /**
     * Confirm booking and redirect to payment.
     *
     * POST /bookings/{id}/confirm-payment
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmAndPay(Booking $booking)
    {
        if ($booking->status !== 'PENDING_PAYMENT') {
            return redirect('/')->with('error', 'This booking cannot be processed.');
        }

        return redirect()->route('payment.show', ['booking' => $booking->id]);
    }

    /**
     * Redirect back to edit reservation with pre-filled data.
     *
     * POST /bookings/{id}/edit
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editReservation(Booking $booking)
    {
        // Store booking data in session before deletion
        session(['booking_data' => [
            'check_in' => $booking->check_in->format('m/d/Y'),
            'check_out' => $booking->check_out->format('m/d/Y'),
            'rooms' => $booking->rooms,
            'guests' => $booking->num_guests,
            'adults' => $booking->num_adults,
            'children' => $booking->num_children,
            'special_requests' => $booking->special_requests,
            'guest_full_name' => $booking->guest->full_name ?? '',
            'guest_email' => $booking->guest->email ?? '',
            'guest_phone' => $booking->guest->phone_e164 ?? '',
        ]]);

        // Delete the booking to start fresh
        $booking->delete();

        return redirect('/')->with('info', 'Edit your reservation details below.');
    }

    /**
     * Step 1: Display reservation form (no POST, no CSRF needed)
     * GET /reservation
     */
    public function reservationForm()
    {
        return view('booking.reservation');
    }

    /**
     * Step 2: Display confirmation form with @csrf token
     * GET /reservation/confirm?checkin=...&checkout=...&rooms=...&guests=...
     */
    public function confirmForm()
    {
        return view('booking.confirm');
    }

    /**
     * Step 3: Create booking after @csrf validation passes
     * POST /booking/store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
            'checkin' => 'required|string',
            'checkout' => 'required|string',
            'rooms' => 'required|integer|min:1|max:10',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Create or find guest by email
            $guest = Guest::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name' => $validated['name'],
                    'phone_e164' => $validated['phone'],
                ]
            );

            // Parse dates from ISO inputs (Y-m-d)
            $checkInDate = Carbon::createFromFormat('Y-m-d', $validated['checkin'])->startOfDay();
            $checkOutDate = Carbon::createFromFormat('Y-m-d', $validated['checkout'])->startOfDay();
            $nights = $checkOutDate->diffInDays($checkInDate);

            // Find first approved property (simplified)
            $property = Property::where('status', 'APPROVED')
                ->where('pending_removal', false)
                ->first();

            if (!$property) {
                return back()->withErrors([
                    'error' => 'No properties available for booking. Please try again later.'
                ]);
            }

            // Generate unique booking reference
            $bookingRef = $this->generateBookingReference();

            // Create booking with PENDING status
            $booking = Booking::create([
                'booking_ref' => $bookingRef,
                'guest_id' => $guest->id,
                'property_id' => $property->id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'nights' => $nights,
                'num_adults' => $validated['guests'],
                'num_children' => 0,
                'rooms' => $validated['rooms'],
                'special_requests' => $validated['special_requests'] ?? '',
                'status' => 'PENDING',
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

}
