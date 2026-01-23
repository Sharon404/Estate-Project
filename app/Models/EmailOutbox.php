<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * EmailOutbox
 * 
 * Tracks all outgoing emails for audit trail and resend capability.
 * Every receipt email is logged here for tracking and retries.
 */
class EmailOutbox extends Model
{
    protected $table = 'email_outbox';
    
    protected $fillable = [
        'recipient_email',
        'subject',
        'body',
        'metadata',
        'receipt_id',
        'booking_id',
        'status',
        'error_message',
        'retry_count',
        'max_retries',
        'sent_at',
        'last_retry_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'last_retry_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the receipt associated with this email.
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Get the booking associated with this email.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope: Get pending emails.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    /**
     * Scope: Get sent emails.
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'SENT');
    }

    /**
     * Scope: Get failed emails.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'FAILED');
    }

    /**
     * Scope: Get emails that can be retried.
     * (FAILED status AND retry_count < max_retries)
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'FAILED')
            ->whereRaw('retry_count < max_retries');
    }

    /**
     * Scope: Get emails for a specific receipt.
     */
    public function scopeForReceipt($query, $receiptId)
    {
        return $query->where('receipt_id', $receiptId);
    }

    /**
     * Scope: Get emails for a specific booking.
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: Get emails created after a certain date.
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Mark email as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'SENT',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark email as failed with optional error message.
     */
    public function markAsFailed(?string $errorMessage = null): void
    {
        $this->update([
            'status' => 'FAILED',
            'error_message' => $errorMessage,
            'last_retry_at' => now(),
        ]);
    }

    /**
     * Increment retry count.
     */
    public function incrementRetry(): void
    {
        $this->increment('retry_count');
        $this->update(['last_retry_at' => now()]);
    }

    /**
     * Check if this email can be retried.
     */
    public function canRetry(): bool
    {
        return $this->status === 'FAILED' && $this->retry_count < $this->max_retries;
    }

    /**
     * Get metadata value by key.
     */
    public function getMetadata(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->metadata;
        }

        return $this->metadata[$key] ?? null;
    }
}
