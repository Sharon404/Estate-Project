<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\ConfirmBookingRequest;
use App\Models\Booking;
use App\Services\BookingCreationService;
use App\Services\BookingConfirmationService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected BookingCreationService $bookingCreationService;
    protected BookingConfirmationService $bookingConfirmationService;

    public function __construct(
        BookingCreationService $bookingCreationService,
        BookingConfirmationService $bookingConfirmationService
    ) {
        $this->bookingCreationService = $bookingCreationService;
        $this->bookingConfirmationService = $bookingConfirmationService;
    }

    /**
     * Store a new booking in DRAFT status.
     *
     * POST /bookings
     *
     * @param StoreBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingCreationService->create($request->validated());

        return response()->json(
            [
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking,
            ],
            201
        );
    }

    /**
     * Get booking summary for confirmation modal.
     * Generates booking reference at this stage.
     *
     * GET /bookings/{id}/summary
     *
     * @param Booking $booking
     * @return JsonResponse
     */
    public function summary(Booking $booking): JsonResponse
    {
        $summary = $this->bookingConfirmationService->getSummary($booking);

        return response()->json(
            [
                'success' => true,
                'data' => $summary,
            ]
        );
    }

    /**
     * Confirm booking and transition to PENDING_PAYMENT status.
     * Allows updates to editable fields (special_requests, adults, children).
     *
     * PATCH /bookings/{id}/confirm
     *
     * @param Booking $booking
     * @param ConfirmBookingRequest $request
     * @return JsonResponse
     */
    public function confirm(Booking $booking, ConfirmBookingRequest $request): JsonResponse
    {
        $confirmed = $this->bookingConfirmationService->confirm($booking, $request->validated());

        return response()->json(
            [
                'success' => true,
                'message' => 'Booking confirmed successfully and moved to payment pending status',
                'data' => $confirmed,
            ]
        );
    }
}
