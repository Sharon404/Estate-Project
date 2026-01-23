<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\MpesaStkCallback;
use App\Models\MpesaStkRequest;
use App\Models\PaymentIntent;
use Illuminate\Support\Arr;

class MpesaCallbackService
{
    /**
     * Store callback from M-PESA BEFORE processing.
     * This ensures we have the raw data even if processing fails.
     *
     * @param MpesaStkRequest $stkRequest
     * @param array $callbackPayload Raw callback payload
     * @return MpesaStkCallback
     */
    public function storeCallback(MpesaStkRequest $stkRequest, array $callbackPayload): MpesaStkCallback
    {
        $bodyData = Arr::get($callbackPayload, 'Body.stkCallback', []);

        return MpesaStkCallback::create([
            'stk_request_id' => $stkRequest->id,
            'result_code' => Arr::get($bodyData, 'ResultCode'),
            'result_desc' => Arr::get($bodyData, 'ResultDescription'),
            'mpesa_receipt_number' => $this->extractReceiptNumber($bodyData),
            'transaction_date' => $this->extractTransactionDate($bodyData),
            'amount' => $this->extractAmount($bodyData),
            'phone_e164' => $this->extractPhoneNumber($bodyData),
            'raw_payload' => $callbackPayload,
        ]);
    }

    /**
     * Process callback: update intent, booking, create ledger.
     * Called after callback is safely stored.
     *
     * NON-NEGOTIABLES:
     * 1. Ledger entry created FIRST (immutable source of truth)
     * 2. Idempotent: Check for duplicate external_ref before creating ledger
     * 3. No direct booking overwrite: Booking state derived from ledger only
     *
     * @param MpesaStkCallback $callback
     * @return array Status update summary
     * @throws \Exception On processing errors
     */
    public function processCallback(MpesaStkCallback $callback): array
    {
        $stkRequest = $callback->mpesaStkRequest;
        $paymentIntent = $stkRequest->paymentIntent;
        $booking = $paymentIntent->booking;

        // Check result code: 0 = success
        $success = $callback->result_code == 0;

        if ($success) {
            return $this->handleSuccessfulPayment($callback, $stkRequest, $paymentIntent, $booking);
        } else {
            return $this->handleFailedPayment($callback, $stkRequest, $paymentIntent, $booking);
        }
    }

    /**
     * Handle successful payment.
     *
     * NON-NEGOTIABLE SEQUENCE:
     * 1. Create ledger entry FIRST (immutable source of truth)
     * 2. Update payment intent status
     * 3. Derive booking state from ledger (no direct overwrites)
     *
     * @param MpesaStkCallback $callback
     * @param MpesaStkRequest $stkRequest
     * @param PaymentIntent $paymentIntent
     * @param Booking $booking
     * @return array
     * @throws \Exception If duplicate payment detected (idempotency)
     */
    private function handleSuccessfulPayment(
        MpesaStkCallback $callback,
        MpesaStkRequest $stkRequest,
        PaymentIntent $paymentIntent,
        Booking $booking
    ): array {
        // IDEMPOTENCY CHECK: Ensure this receipt hasn't been processed before
        $existingTransaction = BookingTransaction::where('external_ref', $callback->mpesa_receipt_number)
            ->first();

        if ($existingTransaction) {
            throw new \Exception(
                "Duplicate payment detected. Receipt {$callback->mpesa_receipt_number} already processed."
            );
        }

        // ===== NON-NEGOTIABLE #1: CREATE LEDGER ENTRY FIRST =====
        // This is the immutable source of truth. All other changes derive from this.
        $transaction = BookingTransaction::create([
            'booking_id' => $booking->id,
            'payment_intent_id' => $paymentIntent->id,
            'type' => 'PAYMENT',
            'source' => 'MPESA_STK',
            'external_ref' => $callback->mpesa_receipt_number,
            'amount' => $callback->amount,
            'currency' => $booking->currency,
            'meta' => [
                'stk_request_id' => $stkRequest->id,
                'phone_e164' => $callback->phone_e164,
                'transaction_date' => $callback->transaction_date,
            ],
        ]);

        // ===== NON-NEGOTIABLE #2: UPDATE PAYMENT INTENT =====
        $paymentIntent->update(['status' => 'SUCCEEDED']);

        // ===== NON-NEGOTIABLE #3: DERIVE BOOKING STATE FROM LEDGER =====
        // Calculate new amounts from all transactions, not from callback directly
        $allTransactions = BookingTransaction::where('booking_id', $booking->id)
            ->where('type', 'PAYMENT')
            ->sum('amount');

        $newAmountPaid = $allTransactions;
        $newAmountDue = max(0, $booking->total_amount - $newAmountPaid);

        // Determine new booking status based on payment state
        $newStatus = $booking->status;
        if ($newAmountDue <= 0) {
            $newStatus = 'PAID';
        } elseif ($booking->status === 'PENDING_PAYMENT') {
            $newStatus = 'PARTIALLY_PAID';
        }

        // Update booking with derived state
        $booking->update([
            'amount_paid' => $newAmountPaid,
            'amount_due' => $newAmountDue,
            'status' => $newStatus,
        ]);

        // Update STK request status
        $stkRequest->update(['status' => 'SUCCESS']);

        // ===== CREATE RECEIPT =====
        // Generate receipt for successful payment with snapshot of all details
        $receiptService = new ReceiptService();
        $receipt = $receiptService->createStkReceipt($transaction, $callback->mpesa_receipt_number);

        // Audit log payment success
        try {
            AuditService::logPaymentSucceeded($paymentIntent, $callback->mpesa_receipt_number);
        } catch (\Exception $e) {
            Log::error('Failed to log payment success audit', ['error' => $e->getMessage()]);
        }

        return [
            'success' => true,
            'message' => 'Payment processed successfully',
            'booking_id' => $booking->id,
            'booking_status' => $newStatus,
            'amount_paid' => $newAmountPaid,
            'amount_due' => $newAmountDue,
            'transaction_id' => $transaction->id,
            'receipt_number' => $callback->mpesa_receipt_number,
            'receipt_id' => $receipt->id,
            'receipt_no' => $receipt->receipt_no,
        ];
    }

    /**
     * Handle failed payment.
     * 1. Update payment intent status to FAILED
     * 2. Update STK request status to FAILED
     * 3. Leave booking unchanged (no ledger entry)
     *
     * @param MpesaStkCallback $callback
     * @param MpesaStkRequest $stkRequest
     * @param PaymentIntent $paymentIntent
     * @param Booking $booking
     * @return array
     */
    private function handleFailedPayment(
        MpesaStkCallback $callback,
        MpesaStkRequest $stkRequest,
        PaymentIntent $paymentIntent,
        Booking $booking
    ): array {
        // Update payment intent status
        $paymentIntent->update(['status' => 'FAILED']);

        // Update STK request status
        $stkRequest->update(['status' => 'FAILED']);

        // Booking unchanged (no ledger entry created)

        return [
            'success' => false,
            'message' => 'Payment failed',
            'booking_id' => $booking->id,
            'booking_status' => $booking->status,
            'amount_paid' => $booking->amount_paid,
            'amount_due' => $booking->amount_due,
            'result_code' => $callback->result_code,
            'result_desc' => $callback->result_desc,
        ];
    }

    /**
     * Extract M-PESA receipt number from callback body.
     *
     * @param array $bodyData
     * @return string|null
     */
    private function extractReceiptNumber(array $bodyData): ?string
    {
        $callbackMetadata = Arr::get($bodyData, 'CallbackMetadata.Item', []);

        foreach ($callbackMetadata as $item) {
            if (Arr::get($item, 'Name') === 'MpesaReceiptNumber') {
                return Arr::get($item, 'Value');
            }
        }

        return null;
    }

    /**
     * Extract transaction date from callback body.
     *
     * @param array $bodyData
     * @return string|null
     */
    private function extractTransactionDate(array $bodyData): ?string
    {
        $callbackMetadata = Arr::get($bodyData, 'CallbackMetadata.Item', []);

        foreach ($callbackMetadata as $item) {
            if (Arr::get($item, 'Name') === 'TransactionDate') {
                return Arr::get($item, 'Value');
            }
        }

        return null;
    }

    /**
     * Extract amount from callback body.
     *
     * @param array $bodyData
     * @return float|null
     */
    private function extractAmount(array $bodyData): ?float
    {
        $callbackMetadata = Arr::get($bodyData, 'CallbackMetadata.Item', []);

        foreach ($callbackMetadata as $item) {
            if (Arr::get($item, 'Name') === 'Amount') {
                return (float) Arr::get($item, 'Value');
            }
        }

        return null;
    }

    /**
     * Extract phone number from callback body.
     *
     * @param array $bodyData
     * @return string|null
     */
    private function extractPhoneNumber(array $bodyData): ?string
    {
        $callbackMetadata = Arr::get($bodyData, 'CallbackMetadata.Item', []);

        foreach ($callbackMetadata as $item) {
            if (Arr::get($item, 'Name') === 'PhoneNumber') {
                return Arr::get($item, 'Value');
            }
        }

        return null;
    }
}
