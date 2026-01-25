<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\ConfirmBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected AuditService $auditService;

    public function __construct(
        BookingService $bookingService,
        AuditService $auditService
    ) {
        $this->bookingService = $bookingService;
        $this->auditService = $auditService;
    }

    /**
     * Store a new booking in DRAFT status.
     *
     * POST /bookings
     *
     * Request body:
     * {
     *   "property_id": 1,
     *   "check_in": "2026-01-25",
     *   "check_out": "2026-01-28",
     *   "adults": 2,
     *   "children": 1,
     *   "special_requests": "High floor preferred",
     *   "guest": {
     *     "full_name": "John Doe",
     *     "email": "john@example.com",
     *     "phone_e164": "+254701123456"
     *   }
     * }
     *
     * Response: Booking created in DRAFT status, ready for summary view
     *
     * @param StoreBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createReservation($request->validated());

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Reservation created successfully',
                    'data' => $booking,
                ],
                201
            );
        } catch (\Exception $e) {
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'guest' => $request->input('guest.email'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get booking summary for confirmation modal.
     * Generates booking reference at this stage.
     *
     * GET /bookings/{id}/summary
     *
     * Response includes:
     * - Booking reference (newly generated if not set)
     * - Guest details
     * - Property details
     * - Dates and pricing breakdown
     * - Amount due
     *
     * @param Booking $booking
     * @return JsonResponse
     */
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
}
