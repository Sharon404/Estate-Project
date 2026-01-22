<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\DB;

/**
 * PaymentService
 * 
 * Orchestrates payment flows:
 * 1. Create payment intent for a booking
 * 2. Initiate STK Push via M-PESA
 * 3. Handle payment confirmation
 * 
 * CRITICAL: Does NOT directly update booking.
 * Booking state is derived from PaymentIntent + BookingTransaction ledger only.
 */
class PaymentService
{
    protected MpesaStkService $stkService;

    public function __construct(MpesaStkService $stkService)
    {
        $this->stkService = $stkService;
    }

    /**
     * Create a payment intent for a booking.
     * 
     * Called when customer reaches payment screen.
     * Creates intent in INITIATED status (not yet sent to M-PESA).
     * 
     * Flow:
     * 1. Validate booking is in PENDING_PAYMENT status
     * 2. Create payment_intent record
     * 3. Return intent with deposit/full payment options
     * 
     * @param Booking $booking
     * @param float|null $amount Override amount (default: minimum_deposit)
     * @return PaymentIntent
     * @throws \Exception
     */
    public function createPaymentIntent(Booking $booking, ?float $amount = null): PaymentIntent
    {
        // Validate booking state
        if ($booking->status !== 'PENDING_PAYMENT' && $booking->status !== 'PARTIALLY_PAID') {
            throw new \Exception(
                "Cannot create payment intent for booking in {$booking->status} status. " .
                "Booking must be PENDING_PAYMENT or PARTIALLY_PAID."
            );
        }

        // Validate amount
        $amount = $amount ?? $booking->minimum_deposit;

        if ($amount <= 0) {
            throw new \Exception('Payment amount must be greater than zero');
        }

        if ($amount > $booking->amount_due) {
            throw new \Exception(
                "Payment amount ({$amount}) cannot exceed amount due ({$booking->amount_due})"
            );
        }

        // Create payment intent
        return PaymentIntent::create([
            'booking_id' => $booking->id,
            'amount' => $amount,
            'currency' => $booking->currency,
            'status' => 'INITIATED',
            'metadata' => [
                'booking_ref' => $booking->booking_ref,
                'guest_email' => $booking->guest->email,
                'property_name' => $booking->property->name,
            ],
        ]);
    }

    /**
     * Initiate STK Push payment.
     * 
     * Called when customer enters phone and clicks "Pay Now".
     * 
     * Flow:
     * 1. Validate payment intent
     * 2. Call STK service to send M-PESA request
     * 3. Store request + response payloads
     * 4. Mark intent as PENDING
     * 5. Return STK request details to client
     * 
     * @param PaymentIntent $paymentIntent
     * @param string $phoneE164 Customer phone in E.164 format (+254...)
     * @return array Status and checkout details
     * @throws \Exception
     */
    public function initiatePayment(PaymentIntent $paymentIntent, string $phoneE164): array
    {
        // Validate intent is in INITIATED status
        if ($paymentIntent->status !== 'INITIATED') {
            throw new \Exception(
                "Cannot initiate STK for payment intent in {$paymentIntent->status} status. " .
                "Must be INITIATED."
            );
        }

        // Call STK service (handles all M-PESA API interaction)
        $stkRequest = $this->stkService->initiateStk($paymentIntent, $phoneE164);

        // Return client-facing data
        return [
            'success' => true,
            'message' => 'STK Push initiated. Please enter PIN on your phone.',
            'stk_request_id' => $stkRequest->id,
            'checkout_request_id' => $stkRequest->checkout_request_id,
            'merchant_request_id' => $stkRequest->merchant_request_id,
            'phone_e164' => $stkRequest->phone_e164,
            'amount' => (float) $stkRequest->paymentIntent->amount,
            'booking_ref' => $stkRequest->paymentIntent->booking->booking_ref,
            'status_url' => route('mpesa.stk-status', ['stkRequest' => $stkRequest->id]),
        ];
    }

    /**
     * Get payment status for client-side polling.
     * 
     * Client polls this endpoint to check if STK was completed.
     * Callback processing happens server-side asynchronously.
     * 
     * @param PaymentIntent $paymentIntent
     * @return array Current payment and booking status
     */
    public function getPaymentStatus(PaymentIntent $paymentIntent): array
    {
        $booking = $paymentIntent->booking;
        $latestStk = $paymentIntent->mpesaStkRequests()->latest()->first();

        return [
            'payment_intent_id' => $paymentIntent->id,
            'payment_status' => $paymentIntent->status,
            'booking_id' => $booking->id,
            'booking_status' => $booking->status,
            'amount_paid' => (float) $booking->amount_paid,
            'amount_due' => (float) $booking->amount_due,
            'has_stk' => $latestStk !== null,
            'stk_status' => $latestStk?->status,
            'has_callback' => $latestStk?->mpesaStkCallbacks()->exists(),
            'last_callback_at' => $latestStk?->mpesaStkCallbacks()
                ->latest('received_at')
                ->first()
                ?->created_at,
        ];
    }

    /**
     * Calculate payment options for a booking.
     * 
     * Returns:
     * - Deposit amount (minimum required)
     * - Full amount (total due)
     * 
     * @param Booking $booking
     * @return array Payment options
     */
    public function getPaymentOptions(Booking $booking): array
    {
        return [
            'deposit' => [
                'label' => 'Pay Deposit',
                'amount' => (float) $booking->minimum_deposit,
                'description' => 'Secure your booking with minimum deposit',
            ],
            'full' => [
                'label' => 'Pay Full Amount',
                'amount' => (float) $booking->amount_due,
                'description' => 'Complete payment for full booking',
            ],
            'due' => [
                'label' => 'Pay Amount Due',
                'amount' => (float) $booking->amount_due,
                'description' => 'Pay remaining balance',
            ],
        ];
    }

    /**
     * Get payment history for a booking.
     * 
     * @param Booking $booking
     * @return array Payment history
     */
    public function getPaymentHistory(Booking $booking): array
    {
        $transactions = $booking->bookingTransactions()
            ->where('type', 'PAYMENT')
            ->with(['paymentIntent'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total_amount' => (float) $booking->total_amount,
            'amount_paid' => (float) $booking->amount_paid,
            'amount_due' => (float) $booking->amount_due,
            'transactions' => $transactions->map(fn ($txn) => [
                'id' => $txn->id,
                'amount' => (float) $txn->amount,
                'source' => $txn->source,
                'external_ref' => $txn->external_ref,
                'created_at' => $txn->created_at,
                'meta' => $txn->meta,
            ]),
        ];
    }
}
