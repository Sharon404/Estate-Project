<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'intent_ref' => 'PI-' . Str::upper(Str::random(10)),
            'method' => 'MPESA_STK',
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

    /**
     * Submit manual M-PESA payment.
     * 
     * Called when STK fails or times out and guest wants to enter receipt manually.
     * Creates a submission record in UNDER_REVIEW status for admin verification.
     * 
     * Flow:
     * 1. Validate payment intent exists and is in PENDING or INITIATED status
     * 2. Validate receipt is unique (not already processed)
     * 3. Create MpesaManualSubmission record in SUBMITTED status
     * 4. Return submission details to guest
     * 
     * @param \App\Models\PaymentIntent $paymentIntent
     * @param string $mpesaReceiptNumber M-PESA receipt (e.g., LIK123ABC456)
     * @param float $amount Amount paid
     * @param string|null $phoneE164 Customer phone
     * @param string|null $notes Additional notes
     * @return array Submission details
     * @throws \Exception
     */
    public function submitManualPayment(
        \App\Models\PaymentIntent $paymentIntent,
        string $mpesaReceiptNumber,
        float $amount,
        ?string $phoneE164 = null,
        ?string $notes = null
    ): array {
        // Validate payment intent is waiting for payment
        if (!in_array($paymentIntent->status, ['INITIATED', 'PENDING'])) {
            throw new \Exception(
                "Cannot submit manual payment for intent in {$paymentIntent->status} status. " .
                "Intent must be INITIATED or PENDING."
            );
        }

        // Validate amount
        if ($amount <= 0) {
            throw new \Exception('Payment amount must be greater than zero');
        }

        if ($amount > $paymentIntent->booking->amount_due) {
            throw new \Exception(
                "Amount ({$amount}) exceeds amount due ({$paymentIntent->booking->amount_due})"
            );
        }

        // Check if receipt already exists (prevents duplicates)
        $existing = \App\Models\MpesaManualSubmission::where(
            'mpesa_receipt_number',
            $mpesaReceiptNumber
        )->first();

        if ($existing) {
            throw new \Exception(
                "Receipt '{$mpesaReceiptNumber}' has already been submitted. " .
                "Status: {$existing->status}"
            );
        }

        // Check if receipt already processed in callback
        $processed = \App\Models\BookingTransaction::where(
            'external_ref',
            $mpesaReceiptNumber
        )->first();

        if ($processed) {
            throw new \Exception(
                "Receipt '{$mpesaReceiptNumber}' has already been verified and processed."
            );
        }

        // Create manual submission record
        $submission = \App\Models\MpesaManualSubmission::create([
            'payment_intent_id' => $paymentIntent->id,
            'mpesa_receipt_number' => strtoupper($mpesaReceiptNumber),
            'phone_e164' => $phoneE164,
            'amount' => $amount,
            'status' => 'SUBMITTED',
            'raw_notes' => $notes,
            'submitted_by_guest' => true,
            'submitted_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Manual payment submitted. Awaiting automatic confirmation via M-PESA callback.',
            'submission_id' => $submission->id,
            'receipt_number' => $submission->mpesa_receipt_number,
            'amount' => (float) $submission->amount,
            'status' => $submission->status,
            'next_step' => 'We will auto-confirm once Safaricom sends the C2B callback. No admin action needed.',
            'submitted_at' => $submission->submitted_at,
        ];
    }

    /**
     * Get pending manual submissions (for admin verification).
     * 
     * @return array List of pending submissions
     */
    public function getPendingManualSubmissions(): array
    {
        $submissions = \App\Models\MpesaManualSubmission::where('status', 'SUBMITTED')
            ->with(['paymentIntent.booking.guest'])
            ->orderBy('submitted_at', 'asc')
            ->get();

        return [
            'total_pending' => $submissions->count(),
            'submissions' => $submissions->map(fn ($submission) => [
                'id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
                'amount' => (float) $submission->amount,
                'phone_e164' => $submission->phone_e164,
                'booking_ref' => $submission->paymentIntent->booking->booking_ref,
                'guest_name' => $submission->paymentIntent->booking->guest->name,
                'guest_email' => $submission->paymentIntent->booking->guest->email,
                'notes' => $submission->raw_notes,
                'submitted_at' => $submission->submitted_at,
            ]),
        ];
    }

    /**
     * Verify manual M-PESA payment and process like callback.
     * 
     * Called by admin to approve manual submission.
     * Creates ledger entry, updates payment intent, and updates booking amounts.
     * 
     * NON-NEGOTIABLE SEQUENCE:
     * 1. Validate submission exists and is SUBMITTED
     * 2. Create BookingTransaction (ledger entry)
     * 3. Update PaymentIntent status to SUCCEEDED
     * 4. Calculate and update Booking amounts
     * 5. Update MpesaManualSubmission to VERIFIED
     * 
     * @param \App\Models\MpesaManualSubmission $submission
     * @return array Verification result
     * @throws \Exception
     */
    public function verifyManualPayment(\App\Models\MpesaManualSubmission $submission): array
    {
        // Validate submission is pending
        if ($submission->status !== 'SUBMITTED') {
            throw new \Exception(
                "Cannot verify submission in {$submission->status} status. " .
                "Must be SUBMITTED."
            );
        }

        // Get related records
        $paymentIntent = $submission->paymentIntent;
        $booking = $paymentIntent->booking;

        try {
            return DB::transaction(function () use ($submission, $paymentIntent, $booking) {
                // Step 1: Create BookingTransaction (ledger entry)
                $transaction = \App\Models\BookingTransaction::create([
                    'booking_id' => $booking->id,
                    'payment_intent_id' => $paymentIntent->id,
                    'type' => 'PAYMENT',
                    'source' => 'MANUAL_ENTRY',
                    'external_ref' => $submission->mpesa_receipt_number,
                    'amount' => $submission->amount,
                    'currency' => $booking->currency,
                    'meta' => [
                        'phone_e164' => $submission->phone_e164,
                        'manual_submission_id' => $submission->id,
                        'verified_by' => 'admin',
                        'verified_at' => now()->toIso8601String(),
                    ],
                ]);

                // Step 2: Update PaymentIntent status
                $paymentIntent->update(['status' => 'SUCCEEDED']);

                // Step 3: Calculate booking amounts from ledger
                $totalPaid = \App\Models\BookingTransaction::where(
                    'booking_id',
                    $booking->id
                )
                    ->where('type', 'PAYMENT')
                    ->sum('amount');

                $amountDue = $booking->total_amount - $totalPaid;
                $bookingStatus = $amountDue <= 0 ? 'PAID' : 'PARTIALLY_PAID';

                // Step 4: Update booking
                $booking->update([
                    'amount_paid' => $totalPaid,
                    'amount_due' => max(0, $amountDue),
                    'status' => $bookingStatus,
                ]);

                // Step 5: Update manual submission status
                $submission->update([
                    'status' => 'VERIFIED',
                    'reviewed_at' => now(),
                ]);

                // Step 6: Create receipt
                $receiptService = new ReceiptService();
                $receipt = $receiptService->createManualReceipt($transaction, $submission->mpesa_receipt_number);

                // Step 7: Audit log manual payment verification
                try {
                    AuditService::logManualPaymentVerified($paymentIntent, $submission->mpesa_receipt_number, Auth::id());
                } catch (\Exception $e) {
                    Log::error('Failed to log manual payment audit', ['error' => $e->getMessage()]);
                }

                return [
                    'success' => true,
                    'message' => 'Manual payment verified successfully',
                    'transaction_id' => $transaction->id,
                    'receipt_number' => $submission->mpesa_receipt_number,
                    'receipt_id' => $receipt->id,
                    'receipt_no' => $receipt->receipt_no,
                    'amount' => (float) $submission->amount,
                    'booking_ref' => $booking->booking_ref,
                    'booking_status' => $bookingStatus,
                    'amount_paid' => (float) $totalPaid,
                    'amount_due' => max(0, (float) $amountDue),
                    'verified_at' => now(),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Manual payment verification failed', [
                'error' => $e->getMessage(),
                'submission_id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
            ]);

            throw new \Exception("Verification failed: {$e->getMessage()}");
        }
    }

    /**
     * Reject manual M-PESA submission.
     * 
     * Called by admin to reject a manual submission.
     * Booking remains unchanged, guest can resubmit or try STK again.
     * 
     * @param \App\Models\MpesaManualSubmission $submission
     * @param string|null $reason Rejection reason
     * @return array Rejection result
     * @throws \Exception
     */
    public function rejectManualPayment(\App\Models\MpesaManualSubmission $submission, ?string $reason = null): array
    {
        if ($submission->status !== 'SUBMITTED') {
            throw new \Exception(
                "Cannot reject submission in {$submission->status} status. " .
                "Must be SUBMITTED."
            );
        }

        $submission->update([
            'status' => 'REJECTED',
            'reviewed_at' => now(),
            'raw_notes' => ($submission->raw_notes ? $submission->raw_notes . "\n\n" : '') . 
                           "REJECTED: " . ($reason ?? 'Receipt could not be verified'),
        ]);

        return [
            'success' => true,
            'message' => 'Manual submission rejected',
            'submission_id' => $submission->id,
            'receipt_number' => $submission->mpesa_receipt_number,
            'reason' => $reason,
            'next_step' => 'Guest can resubmit with correct receipt or try STK Push again',
        ];
    }
}
