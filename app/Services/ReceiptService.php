<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentIntent;
use App\Models\Receipt;
use App\Models\BookingTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * ReceiptService
 * 
 * Handles receipt generation and management.
 * 
 * Rules:
 * - One receipt per successful payment (per booking transaction)
 * - Receipt number is system-generated (unique, sequential format)
 * - Linked to booking + payment intent
 * - Stores receipt_data JSON snapshot of all payment details
 */
class ReceiptService
{
    /**
     * Generate receipt number in format: RCP-YYYY-XXXXX
     * Where YYYY is year and XXXXX is sequential number
     * 
     * @return string Receipt number (e.g., RCP-2026-00001)
     */
    public static function generateReceiptNumber(): string
    {
        $year = date('Y');
        $prefix = "RCP-{$year}-";
        
        // Get the latest receipt number for this year
        $lastReceipt = Receipt::where('receipt_no', 'like', "{$prefix}%")
            ->orderBy('receipt_no', 'desc')
            ->first();
        
        if ($lastReceipt) {
            // Extract sequence number from last receipt
            $lastNumber = (int) substr($lastReceipt->receipt_no, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create receipt for STK payment.
     * 
     * Called after successful M-PESA STK Push payment.
     * Captures snapshot of payment details at time of receipt.
     * 
     * @param BookingTransaction $transaction Ledger entry for payment
     * @param string|null $mpesaReceiptNumber M-PESA receipt number (from callback)
     * @return Receipt Created receipt record
     * @throws \Exception
     */
    public function createStkReceipt(BookingTransaction $transaction, ?string $mpesaReceiptNumber = null): Receipt
    {
        $booking = $transaction->booking;
        $paymentIntent = $transaction->paymentIntent;

        // Capture snapshot of payment data
        $receiptData = $this->buildReceiptData(
            $booking,
            $paymentIntent,
            $transaction,
            'STK_PUSH',
            $mpesaReceiptNumber
        );

        // Create receipt
        $receipt = Receipt::create([
            'booking_id' => $booking->id,
            'payment_intent_id' => $paymentIntent->id,
            'receipt_no' => self::generateReceiptNumber(),
            'mpesa_receipt_number' => $mpesaReceiptNumber,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'receipt_data' => $receiptData,
            'issued_at' => now(),
        ]);

        Log::info('STK receipt created', [
            'receipt_id' => $receipt->id,
            'receipt_no' => $receipt->receipt_no,
            'booking_id' => $booking->id,
            'amount' => $transaction->amount,
            'mpesa_receipt_number' => $mpesaReceiptNumber,
        ]);

        // Audit log receipt generation
        try {
            AuditService::logReceiptGenerated($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to log receipt audit', ['error' => $e->getMessage()]);
        }

        // Queue receipt notification email
        try {
            $emailService = new EmailService();
            $emailService->queueReceiptNotification($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to queue receipt email', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $receipt;
    }

    /**
     * Create receipt for manual payment verification.
     * 
     * Called after admin verifies manual M-PESA submission.
     * Captures snapshot of payment details at time of receipt.
     * 
     * @param BookingTransaction $transaction Ledger entry for payment
     * @param string $mpesaReceiptNumber M-PESA receipt number (from manual submission)
     * @return Receipt Created receipt record
     * @throws \Exception
     */
    public function createManualReceipt(BookingTransaction $transaction, string $mpesaReceiptNumber): Receipt
    {
        $booking = $transaction->booking;
        $paymentIntent = $transaction->paymentIntent;

        // Capture snapshot of payment data
        $receiptData = $this->buildReceiptData(
            $booking,
            $paymentIntent,
            $transaction,
            'MANUAL_ENTRY',
            $mpesaReceiptNumber
        );

        // Create receipt
        $receipt = Receipt::create([
            'booking_id' => $booking->id,
            'payment_intent_id' => $paymentIntent->id,
            'receipt_no' => self::generateReceiptNumber(),
            'mpesa_receipt_number' => $mpesaReceiptNumber,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'receipt_data' => $receiptData,
            'issued_at' => now(),
        ]);

        Log::info('Manual receipt created', [
            'receipt_id' => $receipt->id,
            'receipt_no' => $receipt->receipt_no,
            'booking_id' => $booking->id,
            'amount' => $transaction->amount,
            'mpesa_receipt_number' => $mpesaReceiptNumber,
        ]);

        // Audit log receipt generation
        try {
            AuditService::logReceiptGenerated($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to log receipt audit', ['error' => $e->getMessage()]);
        }

        // Queue receipt notification email
        try {
            $emailService = new EmailService();
            $emailService->queueReceiptNotification($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to queue receipt email', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $receipt;
    }

    /**
     * Create receipt for automated C2B payment.
     *
     * Called after successful C2B confirmation webhook.
     *
     * @param BookingTransaction $transaction
     * @param string $mpesaReceiptNumber
     * @return Receipt
     * @throws \Exception
     */
    public function createC2bReceipt(BookingTransaction $transaction, string $mpesaReceiptNumber): Receipt
    {
        $booking = $transaction->booking;
        $paymentIntent = $transaction->paymentIntent;

        $receiptData = $this->buildReceiptData(
            $booking,
            $paymentIntent,
            $transaction,
            'C2B',
            $mpesaReceiptNumber
        );

        $receipt = Receipt::create([
            'booking_id' => $booking->id,
            'payment_intent_id' => $paymentIntent->id,
            'receipt_no' => self::generateReceiptNumber(),
            'mpesa_receipt_number' => $mpesaReceiptNumber,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'receipt_data' => $receiptData,
            'issued_at' => now(),
        ]);

        Log::info('C2B receipt created', [
            'receipt_id' => $receipt->id,
            'receipt_no' => $receipt->receipt_no,
            'booking_id' => $booking->id,
            'amount' => $transaction->amount,
            'mpesa_receipt_number' => $mpesaReceiptNumber,
        ]);

        try {
            AuditService::logReceiptGenerated($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to log C2B receipt audit', ['error' => $e->getMessage()]);
        }

        try {
            $emailService = new EmailService();
            $emailService->queueReceiptNotification($receipt);
        } catch (\Exception $e) {
            Log::error('Failed to queue C2B receipt email', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $receipt;
    }

    /**
     * Build comprehensive receipt data snapshot.
     * 
     * Captures all relevant payment and booking information
     * at the time of receipt generation for historical accuracy.
     * 
     * @param Booking $booking
     * @param PaymentIntent $paymentIntent
     * @param BookingTransaction $transaction
     * @param string $paymentMethod STK_PUSH or MANUAL_ENTRY
     * @param string|null $mpesaReceiptNumber
     * @return array Receipt data snapshot
     */
    private function buildReceiptData(
        Booking $booking,
        PaymentIntent $paymentIntent,
        BookingTransaction $transaction,
        string $paymentMethod,
        ?string $mpesaReceiptNumber = null
    ): array {
        // Get guest information
        $guest = $booking->guest;

        // Get property information
        $property = $booking->property;

        // Calculate remaining balance
        $totalPaid = $booking->amount_paid;
        $remainingBalance = $booking->amount_due;

        return [
            'receipt_info' => [
                'type' => $paymentMethod,
                'generated_at' => now()->toIso8601String(),
                'issued_by_system' => true,
            ],
            'payment_info' => [
                'amount' => (float) $transaction->amount,
                'currency' => $transaction->currency,
                'payment_method' => $paymentMethod,
                'mpesa_receipt_number' => $mpesaReceiptNumber,
                'booking_transaction_id' => $transaction->id,
                'payment_intent_id' => $paymentIntent->id,
            ],
            'booking_info' => [
                'id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'status' => $booking->status,
                'check_in' => $booking->check_in?->format('Y-m-d'),
                'check_out' => $booking->check_out?->format('Y-m-d'),
                'nights' => $booking->nights,
                'total_amount' => (float) $booking->total_amount,
                'amount_paid_before' => (float) ($totalPaid - $transaction->amount), // Amount paid before this transaction
                'amount_paid_after' => (float) $totalPaid,
                'remaining_balance' => (float) $remainingBalance,
            ],
            'guest_info' => [
                'id' => $guest->id,
                'name' => $guest->name,
                'email' => $guest->email,
                'phone' => $guest->phone,
            ],
            'property_info' => [
                'id' => $property->id ?? null,
                'name' => $property->name ?? null,
                'location' => $property->location ?? null,
            ],
            'meta' => [
                'transaction_source' => $transaction->source,
                'payment_request_amount' => (float) $paymentIntent->amount,
                'payment_request_currency' => $paymentIntent->currency,
            ],
        ];
    }

    /**
     * Get receipt by receipt number.
     * 
     * @param string $receiptNo Receipt number (e.g., RCP-2026-00001)
     * @return Receipt|null
     */
    public function getReceiptByNumber(string $receiptNo): ?Receipt
    {
        return Receipt::where('receipt_no', $receiptNo)->first();
    }

    /**
     * Get all receipts for a booking.
     * 
     * @param Booking $booking
     * @return array List of receipts with data
     */
    public function getBookingReceipts(Booking $booking): array
    {
        $receipts = Receipt::where('booking_id', $booking->id)
            ->orderBy('issued_at', 'desc')
            ->get();

        return [
            'booking_id' => $booking->id,
            'booking_ref' => $booking->booking_ref,
            'total_receipts' => $receipts->count(),
            'receipts' => $receipts->map(fn ($receipt) => [
                'receipt_id' => $receipt->id,
                'receipt_no' => $receipt->receipt_no,
                'amount' => (float) $receipt->amount,
                'currency' => $receipt->currency,
                'mpesa_receipt_number' => $receipt->mpesa_receipt_number,
                'issued_at' => $receipt->issued_at,
                'payment_method' => $receipt->receipt_data['receipt_info']['type'] ?? null,
            ])->toArray(),
        ];
    }

    /**
     * Get receipt with full details (for display/printing).
     * 
     * @param Receipt $receipt
     * @return array Receipt with all details
     */
    public function getReceiptDetails(Receipt $receipt): array
    {
        return [
            'receipt_id' => $receipt->id,
            'receipt_no' => $receipt->receipt_no,
            'issued_at' => $receipt->issued_at,
            'amount' => (float) $receipt->amount,
            'currency' => $receipt->currency,
            'booking_ref' => $receipt->booking->booking_ref,
            'guest_name' => $receipt->booking->guest->name,
            'guest_email' => $receipt->booking->guest->email,
            'payment_method' => $receipt->receipt_data['receipt_info']['type'] ?? null,
            'mpesa_receipt_number' => $receipt->mpesa_receipt_number,
            'full_data' => $receipt->receipt_data,
        ];
    }

    /**
     * Check if receipt exists for a booking transaction.
     * 
     * Ensures one receipt per transaction (idempotency).
     * 
     * @param BookingTransaction $transaction
     * @return bool
     */
    public function receiptExists(BookingTransaction $transaction): bool
    {
        return Receipt::where('booking_id', $transaction->booking_id)
            ->where('payment_intent_id', $transaction->payment_intent_id)
            ->where('amount', $transaction->amount)
            ->whereDate('issued_at', now()->toDateString())
            ->exists();
    }
}
