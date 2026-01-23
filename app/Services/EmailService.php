<?php

namespace App\Services;

use App\Mail\ReceiptNotificationMail;
use App\Models\EmailOutbox;
use App\Models\Receipt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * EmailService
 * 
 * Handles email notifications for receipts.
 * Queues emails and tracks them in email_outbox table.
 */
class EmailService
{
    /**
     * Queue receipt notification email.
     * 
     * Creates email_outbox record and queues the email.
     * Email is sent asynchronously via Laravel queue.
     */
    public function queueReceiptNotification(Receipt $receipt): EmailOutbox
    {
        $booking = $receipt->booking;
        $guest = $booking->guest;
        
        // Build email data
        $subject = "Payment Receipt #{$receipt->receipt_no}";
        $recipientEmail = $guest->email;
        $metadata = [
            'receipt_no' => $receipt->receipt_no,
            'booking_ref' => $booking->booking_ref,
            'amount' => $receipt->amount,
            'currency' => $receipt->currency,
            'guest_name' => "{$guest->first_name} {$guest->last_name}",
            'guest_email' => $guest->email,
            'payment_method' => $receipt->receipt_data['receipt_info']['type'] ?? 'UNKNOWN',
        ];

        try {
            // Render email content
            $mailable = new ReceiptNotificationMail($receipt);
            $body = $mailable->render();

            // Create outbox record
            $emailOutbox = EmailOutbox::create([
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'body' => $body,
                'metadata' => $metadata,
                'receipt_id' => $receipt->id,
                'booking_id' => $booking->id,
                'status' => 'PENDING',
                'retry_count' => 0,
                'max_retries' => 3,
            ]);

            // Queue the email
            Mail::to($recipientEmail)
                ->queue(new ReceiptNotificationMail($receipt));

            Log::info('Receipt email queued', [
                'receipt_no' => $receipt->receipt_no,
                'recipient_email' => $recipientEmail,
                'outbox_id' => $emailOutbox->id,
            ]);

            return $emailOutbox;
        } catch (\Exception $e) {
            Log::error('Failed to queue receipt email', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Resend receipt notification email.
     * 
     * Used by admin to resend email if guest didn't receive it.
     * Updates retry count and last_retry_at timestamp.
     */
    public function resendReceiptEmail(EmailOutbox $emailOutbox): void
    {
        try {
            $receipt = $emailOutbox->receipt;
            
            // Check if can retry
            if (!$emailOutbox->canRetry()) {
                throw new \Exception(
                    "Email has exceeded max retries ({$emailOutbox->max_retries})"
                );
            }

            // Queue the email
            Mail::to($emailOutbox->recipient_email)
                ->queue(new ReceiptNotificationMail($receipt));

            // Increment retry counter
            $emailOutbox->incrementRetry();

            Log::info('Receipt email resent', [
                'outbox_id' => $emailOutbox->id,
                'receipt_no' => $receipt->receipt_no,
                'recipient_email' => $emailOutbox->recipient_email,
                'retry_count' => $emailOutbox->retry_count,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend receipt email', [
                'outbox_id' => $emailOutbox->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get email status for a receipt.
     * 
     * Returns the latest email_outbox record for a receipt.
     */
    public function getReceiptEmailStatus(Receipt $receipt): ?EmailOutbox
    {
        return EmailOutbox::where('receipt_id', $receipt->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get all emails for a booking.
     * 
     * Returns all email_outbox records for a booking.
     */
    public function getBookingEmails($booking)
    {
        return EmailOutbox::where('booking_id', $booking->id)
            ->recentFirst()
            ->get();
    }

    /**
     * Mark email as sent.
     * Called by queue job after successful send.
     */
    public function markEmailAsSent(EmailOutbox $emailOutbox): void
    {
        $emailOutbox->markAsSent();
        
        Log::info('Email marked as sent', [
            'outbox_id' => $emailOutbox->id,
            'recipient_email' => $emailOutbox->recipient_email,
        ]);
    }

    /**
     * Mark email as failed.
     * Called by queue job after failed send attempt.
     */
    public function markEmailAsFailed(EmailOutbox $emailOutbox, ?string $errorMessage = null): void
    {
        $emailOutbox->markAsFailed($errorMessage);
        
        Log::error('Email marked as failed', [
            'outbox_id' => $emailOutbox->id,
            'recipient_email' => $emailOutbox->recipient_email,
            'error' => $errorMessage,
        ]);
    }

    /**
     * Get pending emails (to be sent).
     */
    public function getPendingEmails(int $limit = 10)
    {
        return EmailOutbox::pending()
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get failed emails that can be retried.
     */
    public function getRetryableEmails(int $limit = 10)
    {
        return EmailOutbox::retryable()
            ->orderBy('last_retry_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get email statistics.
     */
    public function getEmailStatistics(): array
    {
        return [
            'pending' => EmailOutbox::pending()->count(),
            'sent' => EmailOutbox::sent()->count(),
            'failed' => EmailOutbox::failed()->count(),
            'total' => EmailOutbox::count(),
            'sent_today' => EmailOutbox::sent()
                ->whereDate('sent_at', now()->toDateString())
                ->count(),
            'failed_retryable' => EmailOutbox::retryable()->count(),
        ];
    }
}
