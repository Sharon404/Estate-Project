<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentIntent;
use Illuminate\Support\Str;

class PaymentIntentService
{
    /**
     * Create a new payment intent for a booking.
     * Supports partial payments.
     * Enforces idempotency - returns existing intent if already created for same amount within 5 minutes.
     *
     * @param Booking $booking
     * @param decimal|float $amount Amount to pay (can be partial)
     * @param string $method MPESA_STK or MPESA_MANUAL
     * @param string $createdBy Who created this intent (SYSTEM, ADMIN, GUEST email)
     * @return PaymentIntent
     * @throws \Exception If booking is not in payable status
     */
    public function create(
        Booking $booking,
        $amount,
        string $method = 'MPESA_STK',
        string $createdBy = 'SYSTEM'
    ): PaymentIntent {
        // Validate booking is in a payable status
        if (!in_array($booking->status, ['PENDING_PAYMENT', 'PARTIALLY_PAID'])) {
            throw new \Exception(
                "Cannot create payment intent for booking in {$booking->status} status. " .
                "Booking must be in PENDING_PAYMENT or PARTIALLY_PAID status."
            );
        }

        // Validate amount
        $amount = (float) $amount;
        if ($amount <= 0) {
            throw new \Exception("Payment amount must be greater than 0");
        }

        if ($amount > $booking->amount_due) {
            throw new \Exception(
                "Payment amount ({$amount}) exceeds amount due ({$booking->amount_due})"
            );
        }

        // Idempotency check: Don't create duplicate intents for same amount within 5 minutes
        $existingIntent = $this->findExistingIntent($booking, $amount);
        if ($existingIntent) {
            return $existingIntent;
        }

        // Create new payment intent
        $intent = PaymentIntent::create([
            'booking_id' => $booking->id,
            'intent_ref' => $this->generateIntentReference(),
            'method' => $method,
            'amount' => $amount,
            'currency' => $booking->currency,
            'status' => 'INITIATED',
            'created_by' => $createdBy,
        ]);

        return $intent;
    }

    /**
     * Find existing payment intent for idempotency.
     * Returns recent intent for same booking and amount (within 5 minutes).
     *
     * @param Booking $booking
     * @param float $amount
     * @return PaymentIntent|null
     */
    private function findExistingIntent(Booking $booking, float $amount): ?PaymentIntent
    {
        return PaymentIntent::where('booking_id', $booking->id)
            ->where('amount', $amount)
            ->where('status', '!=', 'FAILED')
            ->where('status', '!=', 'CANCELLED')
            ->where('created_at', '>', now()->subMinutes(5))
            ->latest()
            ->first();
    }

    /**
     * Generate unique payment intent reference.
     * Format: PI + timestamp + 6-char random suffix
     * Example: PI16740827391X2K9M
     *
     * @return string
     */
    private function generateIntentReference(): string
    {
        return 'PI' . now()->timestamp . Str::random(6);
    }

    /**
     * Mark intent as pending (waiting for callback/confirmation).
     * Called after STK push is initiated, before callback received.
     *
     * @param PaymentIntent $intent
     * @return PaymentIntent
     */
    public function markPending(PaymentIntent $intent): PaymentIntent
    {
        $intent->update(['status' => 'PENDING']);
        return $intent;
    }

    /**
     * Mark intent as succeeded.
     * Note: Booking status is updated by ledger posting service, not here.
     *
     * @param PaymentIntent $intent
     * @return PaymentIntent
     */
    public function markSucceeded(PaymentIntent $intent): PaymentIntent
    {
        $intent->update(['status' => 'SUCCEEDED']);
        return $intent;
    }

    /**
     * Mark intent as failed.
     *
     * @param PaymentIntent $intent
     * @return PaymentIntent
     */
    public function markFailed(PaymentIntent $intent): PaymentIntent
    {
        $intent->update(['status' => 'FAILED']);
        return $intent;
    }

    /**
     * Mark intent as under review (for manual verification).
     *
     * @param PaymentIntent $intent
     * @return PaymentIntent
     */
    public function markUnderReview(PaymentIntent $intent): PaymentIntent
    {
        $intent->update(['status' => 'UNDER_REVIEW']);
        return $intent;
    }

    /**
     * Cancel a payment intent.
     *
     * @param PaymentIntent $intent
     * @return PaymentIntent
     */
    public function cancel(PaymentIntent $intent): PaymentIntent
    {
        $intent->update(['status' => 'CANCELLED']);
        return $intent;
    }

    /**
     * Get payment summary for a booking.
     * Returns how much is paid, due, and minimum deposit info.
     *
     * @param Booking $booking
     * @return array
     */
    public function getPaymentSummary(Booking $booking): array
    {
        return [
            'booking_id' => $booking->id,
            'total_amount' => $booking->total_amount,
            'amount_paid' => $booking->amount_paid,
            'amount_due' => $booking->amount_due,
            'minimum_deposit' => $booking->minimum_deposit,
            'currency' => $booking->currency,
            'status' => $booking->status,
            'requires_full_payment' => is_null($booking->minimum_deposit),
            'pending_intents' => PaymentIntent::where('booking_id', $booking->id)
                ->whereIn('status', ['INITIATED', 'PENDING'])
                ->count(),
        ];
    }
}
