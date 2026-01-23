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
     * Confirm booking and transition to PENDING_PAYMENT status.
     * Allows updates to editable fields (special_requests, adults, children).
     * Locks booking for payment processing.
     *
     * PATCH /bookings/{id}/confirm
     *
     * Request body:
     * {
     *   "adults": 2,
     *   "children": 1,
     *   "special_requests": "Early check-in preferred"
     * }
     *
     * Response: Booking confirmed in PENDING_PAYMENT status, ready for payment
     *
     * @param Booking $booking
     * @param ConfirmBookingRequest $request
     * @return JsonResponse
     */
    public function confirm(Booking $booking, ConfirmBookingRequest $request): JsonResponse
    {
        try {
            if ($booking->status !== 'DRAFT') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot confirm booking in {$booking->status} status",
                ], 400);
            }

            $confirmed = $this->bookingService->confirmAndLock($booking, $request->validated());

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Booking confirmed successfully and moved to payment',
                    'data' => $confirmed,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Booking confirmation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm booking',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
