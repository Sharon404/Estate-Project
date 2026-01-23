<?php

namespace App\Mail;

use App\Models\Receipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ReceiptNotificationMail
 * 
 * Queued email sent when receipt is generated.
 * Includes booking reference, payment amount, and receipt number.
 */
class ReceiptNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;
    public $booking;
    public $guest;

    /**
     * Create a new message instance.
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
        $this->booking = $receipt->booking;
        $this->guest = $receipt->booking->guest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment Receipt #{$this->receipt->receipt_no}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.receipt-notification',
            with: [
                'receipt' => $this->receipt,
                'booking' => $this->booking,
                'guest' => $this->guest,
                'receiptNo' => $this->receipt->receipt_no,
                'amount' => $this->receipt->amount,
                'currency' => $this->receipt->currency,
                'bookingRef' => $this->booking->booking_ref,
                'guestName' => "{$this->guest->first_name} {$this->guest->last_name}",
                'issuedAt' => $this->receipt->issued_at->format('M d, Y H:i:s'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
