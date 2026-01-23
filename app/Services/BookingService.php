<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BookingService
 * 
 * High-level orchestration of booking workflow:
 * 1. Create reservation (DRAFT status)
 * 2. Get confirmation summary (generates booking_ref)
 * 3. Confirm and lock booking (PENDING_PAYMENT status)
 * 4. Complete booking lifecycle
 * 
 * Uses composition pattern with specialized services.
 * All state changes are transactional.
 */
class BookingService
{
    protected BookingCreationService $creationService;
    protected BookingConfirmationService $confirmationService;
    protected BookingReferenceService $referenceService;
    protected AuditService $auditService;

    public function __construct(
        BookingCreationService $creationService,
        BookingConfirmationService $confirmationService,
        BookingReferenceService $referenceService,
        AuditService $auditService
    ) {
        $this->creationService = $creationService;
        $this->confirmationService = $confirmationService;
        $this->referenceService = $referenceService;
        $this->auditService = $auditService;
    }

    /**
     * STEP 1: Create a new reservation (DRAFT status)
     * 
     * Accepts reservation form data and creates booking.
     * No payment processing yet.
     * 
     * @param array $validated Validated request data (from StoreBookingRequest)
     * @return array Booking data including ID
     */
    public function createReservation(array $validated): array
    {
        return DB::transaction(function () use ($validated) {
            // Create booking in DRAFT status
            $booking = $this->creationService->create($validated);
            
            // Log action
            $this->auditService->log(
                action: 'booking_created_draft',
                description: "Booking #{$booking['id']} created in DRAFT status",
                bookingId: $booking['id'],
                guestId: $booking['guest_id'],
                ipAddress: request()->ip(),
                userAgent: request()->userAgent()
            );

            return $booking;
        });
    }

    /**
     * STEP 2: Get booking summary for confirmation
     * 
     * Generates booking reference (if not already set).
     * Returns all details for confirmation modal.
     * Does NOT commit booking_ref permanently yet.
     * 
     * @param Booking $booking
     * @return array Summary data
     */
    public function getConfirmationSummary(Booking $booking): array
    {
        return DB::transaction(function () use ($booking) {
            // Validate booking is in DRAFT
            if ($booking->status !== 'DRAFT') {
                throw new \Exception(
                    "Cannot get summary for booking in {$booking->status} status. Must be DRAFT."
                );
            }

            // Get summary (may generate booking_ref)
            $summary = $this->confirmationService->getSummary($booking);

            return $summary;
        });
    }

    /**
     * STEP 3: Confirm booking and lock for payment
     * 
     * Validates booking data again.
     * Locks booking in PENDING_PAYMENT status.
     * Persists booking_ref permanently.
     * Prevents further edits until payment is received.
     * 
     * @param Booking $booking
     * @param array $validated Validated request data (from ConfirmBookingRequest)
     * @return array Confirmed booking data
     */
    public function confirmAndLock(Booking $booking, array $validated): array
    {
        return DB::transaction(function () use ($booking, $validated) {
            // Validate booking is in DRAFT
            if ($booking->status !== 'DRAFT') {
                throw new \Exception(
                    "Cannot confirm booking in {$booking->status} status. Must be DRAFT."
                );
            }

            // Confirm and move to PENDING_PAYMENT
            $confirmed = $this->confirmationService->confirm($booking, $validated);

            // Refresh to get latest state
            $booking->refresh();

            // Log action
            $this->auditService->log(
                action: 'booking_confirmed_pending_payment',
                description: "Booking #{$booking->id} ({$booking->booking_ref}) confirmed and locked for payment",
                bookingId: $booking->id,
                guestId: $booking->guest_id,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent()
            );

            return $confirmed;
        });
    }

    /**
     * Mark booking as paid after successful payment.
     * 
     * Creates booking_transactions ledger entry.
     * Updates booking amount_paid and amount_due.
     * Updates booking status to PAID or PARTIALLY_PAID.
     * 
     * CRITICAL: Ledger entry is the source of truth, not direct updates.
     * 
     * @param Booking $booking
     * @param PaymentIntent $paymentIntent
     * @param float $amountPaid
     * @param string $reference Transaction reference (receipt number)
     * @param array $meta Additional metadata
     * @return array Updated booking status
     */
    public function markAsPaid(
        Booking $booking,
        PaymentIntent $paymentIntent,
        float $amountPaid,
        string $reference,
        array $meta = []
    ): array {
        return DB::transaction(function () use (
            $booking,
            $paymentIntent,
            $amountPaid,
            $reference,
            $meta
        ) {
            // Validate amount
            if ($amountPaid <= 0 || $amountPaid > $booking->amount_due) {
                throw new \Exception(
                    "Invalid payment amount. Amount due: {$booking->amount_due}, Amount paid: {$amountPaid}"
                );
            }

            // STEP 1: Create ledger entry (source of truth)
            $transaction = BookingTransaction::create([
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
                'type' => 'CREDIT',
                'amount' => $amountPaid,
                'reference' => $reference,
                'description' => $meta['description'] ?? 'Payment received',
                'meta' => $meta,
                'posted_at' => now(),
            ]);

            // STEP 2: Recalculate booking totals from ledger
            $totalPaid = BookingTransaction::where('booking_id', $booking->id)
                ->where('type', 'CREDIT')
                ->sum('amount');

            $newAmountDue = $booking->total_amount - $totalPaid;
            $newStatus = $newAmountDue <= 0 ? 'PAID' : 'PARTIALLY_PAID';

            // STEP 3: Update booking
            $booking->update([
                'amount_paid' => $totalPaid,
                'amount_due' => max(0, $newAmountDue),
                'status' => $newStatus,
            ]);

            // STEP 4: Update payment intent status
            $paymentIntent->update([
                'status' => 'SUCCEEDED',
            ]);

            // STEP 5: Log action
            $this->auditService->log(
                action: 'booking_payment_received',
                description: "Booking #{$booking->id} payment received: {$amountPaid} {$booking->currency} (status: {$newStatus})",
                bookingId: $booking->id,
                guestId: $booking->guest_id,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                meta: [
                    'amount' => $amountPaid,
                    'reference' => $reference,
                    'transaction_id' => $transaction->id,
                ]
            );

            return [
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'status' => $newStatus,
                'amount_paid' => $totalPaid,
                'amount_due' => max(0, $newAmountDue),
                'transaction_id' => $transaction->id,
                'transaction_reference' => $reference,
            ];
        });
    }
}
