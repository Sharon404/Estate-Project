<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Arr;

class BookingConfirmationService
{
    protected BookingReferenceService $referenceService;

    public function __construct(BookingReferenceService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    /**
     * Get booking summary for confirmation modal.
     * Generates booking reference at this stage.
     *
     * @param Booking $booking
     * @return array
     */
    public function getSummary(Booking $booking): array
    {
        // Generate booking reference if not already set
        if (!$booking->booking_ref) {
            $booking->update([
                'booking_ref' => $this->referenceService->generate(),
            ]);
        }

        return [
            'id' => $booking->id,
            'booking_ref' => $booking->booking_ref,
            'status' => $booking->status,
            'guest' => [
                'id' => $booking->guest->id,
                'full_name' => $booking->guest->full_name,
                'email' => $booking->guest->email,
                'phone_e164' => $booking->guest->phone_e164,
            ],
            'property' => [
                'id' => $booking->property->id,
                'name' => $booking->property->name,
                'nightly_rate' => $booking->property->nightly_rate,
                'currency' => $booking->property->currency,
            ],
            'check_in' => $booking->check_in->format('Y-m-d'),
            'check_out' => $booking->check_out->format('Y-m-d'),
            'adults' => $booking->adults,
            'children' => $booking->children,
            'special_requests' => $booking->special_requests,
            'nights' => $booking->nights,
            'nightly_rate' => $booking->nightly_rate,
            'accommodation_subtotal' => $booking->accommodation_subtotal,
            'addons_subtotal' => $booking->addons_subtotal,
            'total_amount' => $booking->total_amount,
            'currency' => $booking->currency,
            'amount_paid' => $booking->amount_paid,
            'amount_due' => $booking->amount_due,
            'minimum_deposit' => $booking->minimum_deposit,
        ];
    }

    /**
     * Confirm booking and move to PENDING_PAYMENT status.
     * Updates editable fields and transitions status.
     *
     * @param Booking $booking
     * @param array $validated Validated request data
     * @return array Updated booking data
     */
    public function confirm(Booking $booking, array $validated): array
    {
        // Only allow updates if still in DRAFT status
        if ($booking->status !== 'DRAFT') {
            throw new \Exception("Booking must be in DRAFT status to confirm. Current status: {$booking->status}");
        }

        // Update editable fields
        $updates = [];

        if (Arr::has($validated, 'special_requests')) {
            $updates['special_requests'] = Arr::get($validated, 'special_requests');
        }

        if (Arr::has($validated, 'adults')) {
            $updates['adults'] = Arr::get($validated, 'adults');
        }

        if (Arr::has($validated, 'children')) {
            $updates['children'] = Arr::get($validated, 'children');
        }

        // Move to PENDING_PAYMENT status
        $updates['status'] = 'PENDING_PAYMENT';

        $booking->update($updates);

        // Return updated summary
        return $this->getSummary($booking);
    }
}
