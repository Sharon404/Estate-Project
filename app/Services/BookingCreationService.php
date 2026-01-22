<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Support\Arr;

class BookingCreationService
{
    /**
     * Create a new booking in DRAFT status.
     *
     * Step 1: Create/find guest
     * Step 2: Calculate nights and totals
     * Step 3: Create booking in DRAFT status
     * Step 4: Return booking data
     *
     * @param array $validated Validated request data
     * @return array Booking data
     */
    public function create(array $validated): array
    {
        // Step 1: Create or find guest
        $guestData = Arr::get($validated, 'guest');
        $guest = $this->createOrFindGuest($guestData);

        // Step 2: Calculate metrics
        $property = \App\Models\Property::findOrFail(Arr::get($validated, 'property_id'));
        $checkIn = new \DateTime(Arr::get($validated, 'check_in'));
        $checkOut = new \DateTime(Arr::get($validated, 'check_out'));

        $nights = $checkOut->diff($checkIn)->days;
        $nightlyRate = $property->nightly_rate;
        $accommodationSubtotal = $nightlyRate * $nights;
        $addonsSubtotal = 0; // No add-ons at booking creation
        $totalAmount = $accommodationSubtotal + $addonsSubtotal;

        // Step 3: Create booking in DRAFT status
        $booking = Booking::create([
            'booking_ref' => null, // Will be set when moved to PENDING_PAYMENT
            'property_id' => $property->id,
            'guest_id' => $guest->id,
            'check_in' => Arr::get($validated, 'check_in'),
            'check_out' => Arr::get($validated, 'check_out'),
            'adults' => Arr::get($validated, 'adults'),
            'children' => Arr::get($validated, 'children', 0),
            'special_requests' => Arr::get($validated, 'special_requests'),
            'status' => 'DRAFT',
            'currency' => $property->currency,
            'nightly_rate' => $nightlyRate,
            'nights' => $nights,
            'accommodation_subtotal' => $accommodationSubtotal,
            'addons_subtotal' => $addonsSubtotal,
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'amount_due' => $totalAmount,
        ]);

        // Step 4: Return booking data
        return [
            'id' => $booking->id,
            'booking_ref' => $booking->booking_ref,
            'property_id' => $booking->property_id,
            'guest_id' => $booking->guest_id,
            'guest' => [
                'id' => $guest->id,
                'full_name' => $guest->full_name,
                'email' => $guest->email,
                'phone_e164' => $guest->phone_e164,
            ],
            'check_in' => $booking->check_in->format('Y-m-d'),
            'check_out' => $booking->check_out->format('Y-m-d'),
            'adults' => $booking->adults,
            'children' => $booking->children,
            'special_requests' => $booking->special_requests,
            'status' => $booking->status,
            'currency' => $booking->currency,
            'nightly_rate' => $booking->nightly_rate,
            'nights' => $booking->nights,
            'accommodation_subtotal' => $booking->accommodation_subtotal,
            'addons_subtotal' => $booking->addons_subtotal,
            'total_amount' => $booking->total_amount,
            'amount_paid' => $booking->amount_paid,
            'amount_due' => $booking->amount_due,
            'created_at' => $booking->created_at->toIso8601String(),
        ];
    }

    /**
     * Create a guest or find existing by email.
     *
     * @param array $guestData
     * @return Guest
     */
    private function createOrFindGuest(array $guestData): Guest
    {
        return Guest::firstOrCreate(
            ['email' => Arr::get($guestData, 'email')],
            [
                'full_name' => Arr::get($guestData, 'full_name'),
                'phone_e164' => Arr::get($guestData, 'phone_e164'),
            ]
        );
    }
}
