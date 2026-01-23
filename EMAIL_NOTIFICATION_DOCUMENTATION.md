# Email Notification System - Documentation

## Overview

The Email Notification System automatically sends receipt confirmation emails to guests when payments are processed. Emails are queued using Laravel's queue system and tracked in the `email_outbox` table for reliability and auditability.

**Key Features:**
- ✅ Automatic email on receipt issuance
- ✅ Queued for reliable delivery
- ✅ Email tracking in database
- ✅ Admin resend capability
- ✅ Retry logic (3 attempts)
- ✅ Comprehensive email templates
- ✅ Status tracking (PENDING, SENT, FAILED)

---

## Architecture

### Flow

```
Receipt Created
    ↓
ReceiptService.createReceipt()
    ↓
EmailService.queueReceiptNotification()
    ├─ Render email template
    ├─ Create EmailOutbox record (status: PENDING)
    └─ Queue with Mail::queue()
    ↓
Laravel Queue Processor
    ├─ Dequeue email
    ├─ Send via SMTP
    └─ Update EmailOutbox (status: SENT or FAILED)
    ↓
(Optional) Admin Resend
    ├─ Admin clicks "Resend" in admin panel
    ├─ EmailService.resendReceiptEmail()
    └─ Email queued again (retry_count++)
```

### Components

#### 1. EmailOutbox Model
**File:** `app/Models/EmailOutbox.php`

Tracks all outgoing emails with status and retry information.

**Schema:**
```
├─ id (PK)
├─ recipient_email
├─ subject
├─ body (HTML)
├─ metadata (JSON - booking_ref, receipt_no, amount, etc)
├─ receipt_id (FK)
├─ booking_id (FK)
├─ status (PENDING, SENT, FAILED)
├─ error_message
├─ retry_count
├─ max_retries
├─ sent_at
├─ last_retry_at
└─ timestamps
```

**Scopes:**
- `pending()` - Get unsent emails
- `sent()` - Get successfully sent emails
- `failed()` - Get failed emails
- `retryable()` - Get emails that can be retried
- `forReceipt($id)` - Filter by receipt
- `forBooking($id)` - Filter by booking
- `recentFirst()` - Order by newest first

**Methods:**
- `markAsSent()` - Mark as delivered
- `markAsFailed($message)` - Mark as failed with error
- `incrementRetry()` - Increment retry counter
- `canRetry()` - Check if can be retried
- `getMetadata($key)` - Get metadata value

#### 2. EmailService
**File:** `app/Services/EmailService.php`

Handles email queueing, sending, and status tracking.

**Methods:**
- `queueReceiptNotification(Receipt)` - Queue email on receipt
- `resendReceiptEmail(EmailOutbox)` - Resend failed email
- `getReceiptEmailStatus(Receipt)` - Get latest email for receipt
- `getBookingEmails($booking)` - Get all emails for booking
- `markEmailAsSent(EmailOutbox)` - Update status to SENT
- `markEmailAsFailed(EmailOutbox, $error)` - Update status to FAILED
- `getPendingEmails($limit)` - Get unsent emails
- `getRetryableEmails($limit)` - Get failed emails to retry
- `getEmailStatistics()` - Get email statistics

#### 3. ReceiptNotificationMail
**File:** `app/Mail/ReceiptNotificationMail.php`

Mailable class for receipt emails. Implements queued mail.

**Data Passed to Template:**
- `receipt` - Receipt model
- `booking` - Booking model
- `guest` - Guest model
- `receiptNo` - Receipt number
- `amount` - Payment amount
- `currency` - Currency code
- `bookingRef` - Booking reference
- `guestName` - Full name of guest
- `issuedAt` - Formatted timestamp

#### 4. Email Template
**File:** `resources/views/emails/receipt-notification.blade.php`

Professional HTML email template with:
- Receipt confirmation header
- Guest greeting
- Receipt details (number, amount, booking ref)
- Booking information
- Booking dates and amounts
- CTA button to view full receipt
- Footer with support link

---

## Email Content

### Email Structure

**Subject:** `Payment Receipt #{RCP-2026-00001}`

**Body includes:**
- ✅ Greeting with guest name
- ✅ Success message
- ✅ Receipt number
- ✅ Payment amount (large, green text)
- ✅ Booking reference
- ✅ Issue date/time
- ✅ Booking details (check-in, check-out, nights)
- ✅ Amount breakdown (total, paid, remaining)
- ✅ CTA button to view full receipt online
- ✅ Support contact information

### Sample Email

```
Subject: Payment Receipt #RCP-2026-00001

═══════════════════════════════════════════════════════════════════

                        ✓ Payment Confirmed
                     Receipt #RCP-2026-00001

═══════════════════════════════════════════════════════════════════

Hello John Doe,

Thank you for your payment. Your receipt has been generated and is 
attached below.

┌─────────────────────────────────────────────────────────────┐
│ ✓ Your payment has been successfully processed and recorded. │
│   You can download or print this receipt for your records.   │
└─────────────────────────────────────────────────────────────┘

RECEIPT DETAILS:
─────────────────────────────────────────────────────────────
Booking Reference:              GS-2026-001
Receipt Number:                 RCP-2026-00001
Payment Amount:                 KES 5,000.00
Date Issued:                    Jan 22, 2026 14:30:00

BOOKING INFORMATION:
─────────────────────────────────────────────────────────────
Booking Reference:              GS-2026-001
Check-in Date:                  Feb 01, 2026
Check-out Date:                 Feb 05, 2026
Total Booking Amount:           KES 15,000.00
Amount Paid:                    KES 5,000.00
Remaining Balance:              KES 10,000.00

                    [View Full Receipt]

═══════════════════════════════════════════════════════════════════

This is an automated email. Please do not reply directly.

If you have questions about your booking or payment, please 
contact support.

© 2026 Estate Management System. All rights reserved.
```

---

## Database Schema

### email_outbox table

```sql
CREATE TABLE email_outbox (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    
    -- Email content
    recipient_email VARCHAR(255),
    subject VARCHAR(255),
    body LONGTEXT,
    metadata JSON,
    
    -- Linkage
    receipt_id BIGINT NULLABLE,
    booking_id BIGINT NULLABLE,
    
    -- Status
    status ENUM('PENDING', 'SENT', 'FAILED') DEFAULT 'PENDING',
    error_message TEXT NULLABLE,
    
    -- Retry logic
    retry_count INT DEFAULT 0,
    max_retries INT DEFAULT 3,
    
    -- Timestamps
    sent_at TIMESTAMP NULLABLE,
    last_retry_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX (recipient_email, status),
    INDEX (booking_id, status),
    INDEX (receipt_id, status),
    INDEX (status, created_at),
    
    -- Foreign keys
    FOREIGN KEY (receipt_id) REFERENCES receipts(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);
```

---

## Configuration

### Laravel Configuration

Ensure your `.env` file has proper mail configuration:

```env
# SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@estatemanagement.com"
MAIL_FROM_NAME="Estate Management"

# Queue Configuration
QUEUE_CONNECTION=database  # or redis, sync for immediate
```

### Queue Setup

If using `database` driver:

```bash
php artisan queue:table
php artisan migrate
```

To process queued jobs:

```bash
php artisan queue:work --queue=default
```

---

## API Endpoints

### Admin Email Management

#### Resend Receipt Email

**Endpoint:** `POST /admin/payment/emails/{emailOutboxId}/resend`

**Parameters:**
- `emailOutboxId` - ID of email_outbox record

**Authentication:** ✅ Required (admin)

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Receipt email resent successfully",
  "email_outbox_id": 5,
  "status": "PENDING",
  "retry_count": 2,
  "max_retries": 3,
  "last_retry_at": "2026-01-23T10:15:00Z"
}
```

**Response (Error - 400):**
```json
{
  "success": false,
  "message": "Email has exceeded maximum retries (3)",
  "retry_count": 3,
  "max_retries": 3
}
```

#### Get Receipt Email History

**Endpoint:** `GET /admin/payment/receipts/{receiptId}/email-history`

**Parameters:**
- `receiptId` - ID of receipt

**Authentication:** ✅ Required (admin)

**Response:**
```json
{
  "success": true,
  "receipt_no": "RCP-2026-00001",
  "email_count": 2,
  "data": [
    {
      "id": 5,
      "recipient_email": "john@example.com",
      "status": "PENDING",
      "subject": "Payment Receipt #RCP-2026-00001",
      "retry_count": 1,
      "max_retries": 3,
      "error_message": null,
      "sent_at": "2026-01-23T10:10:00Z",
      "last_retry_at": "2026-01-23T10:15:00Z",
      "created_at": "2026-01-23T10:00:00Z"
    }
  ]
}
```

#### Get Email Statistics

**Endpoint:** `GET /admin/payment/emails/statistics`

**Authentication:** ✅ Required (admin)

**Response:**
```json
{
  "success": true,
  "data": {
    "pending": 5,
    "sent": 142,
    "failed": 3,
    "total": 150,
    "sent_today": 12,
    "failed_retryable": 2
  }
}
```

---

## Usage Examples

### Automatic Email on Receipt Creation

When a receipt is created (STK or manual), email is automatically queued:

```php
// In ReceiptService.createStkReceipt()
$receipt = Receipt::create([...]);

// Email automatically queued:
$emailService = new EmailService();
$emailService->queueReceiptNotification($receipt);
```

### Admin Resend Email

```bash
# Resend email for email_outbox record with ID 5
curl -X POST http://localhost:8000/admin/payment/emails/5/resend \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"
```

### Get Email History

```bash
# Get all emails for receipt ID 1
curl -X GET http://localhost:8000/admin/payment/receipts/1/email-history \
  -H "Authorization: Bearer {token}"
```

### Get Email Statistics

```bash
# Get overall email delivery stats
curl -X GET http://localhost:8000/admin/payment/emails/statistics \
  -H "Authorization: Bearer {token}"
```

---

## Email Status Tracking

### Status Flow

```
Creation
    ↓
PENDING (queued, waiting to send)
    ├─ Success → SENT (delivered)
    │
    └─ Failure → FAILED (error_message captured)
        ├─ Retryable (retry_count < max_retries)
        │   └─ Admin clicks "Resend" → back to PENDING
        │
        └─ Max retries reached → stays FAILED
```

### Status Meanings

| Status | Meaning | Action |
|--------|---------|--------|
| PENDING | Queued, waiting to send | Monitor queue processing |
| SENT | Successfully delivered | Complete |
| FAILED | Failed to send | Resend or investigate error |

### Retry Logic

- **max_retries:** Default 3 attempts
- **retry_count:** Incremented each retry
- **last_retry_at:** Updated on each attempt
- **error_message:** Captured on failure

**Can only retry if:**
- `status = 'FAILED'` AND `retry_count < max_retries`

---

## Metadata Structure

Each email stores metadata (JSON) for tracking:

```json
{
  "receipt_no": "RCP-2026-00001",
  "booking_ref": "GS-2026-001",
  "amount": 5000.00,
  "currency": "KES",
  "guest_name": "John Doe",
  "guest_email": "john@example.com",
  "payment_method": "STK_PUSH"
}
```

---

## Queue Processing

### Running the Queue Worker

```bash
# Process jobs from default queue
php artisan queue:work --queue=default

# Process with specific timeout (stop after 60 sec)
php artisan queue:work --timeout=60

# Process and fail on max errors
php artisan queue:work --max-failures=3
```

### For Production

Use a process manager like Supervisor:

```ini
[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work database --sleep=3
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
```

---

## Troubleshooting

### Email Not Sent

**Check:**
1. Queue is running: `php artisan queue:work`
2. Email configuration in `.env`
3. EmailOutbox record exists with status=PENDING
4. Check error_message field in email_outbox

**Common Issues:**
- Queue connection misconfigured → Check QUEUE_CONNECTION in .env
- SMTP credentials wrong → Check MAIL_* variables
- Recipient email invalid → Check guest.email in database

### Retry Emails

```bash
# Manually retry failed emails (CLI)
php artisan email:retry-failed

# Or via admin endpoint
POST /admin/payment/emails/{id}/resend
```

### Debug Email Content

```php
// Test email rendering
$mailable = new ReceiptNotificationMail($receipt);
echo $mailable->render();

// Check stored email in database
$email = EmailOutbox::find(5);
echo $email->body; // See HTML
```

---

## Performance Considerations

### Database Queries
- Email_outbox is indexed on (status, receipt_id, booking_id)
- Scopes use indexed fields for fast filtering
- Pagination recommended for large result sets

### Queue Performance
- Default queue driver is recommended (configurable)
- Database queue slower than Redis
- Consider Redis for high-volume scenarios

### Email Volume
- Monitor pending emails: `EmailOutbox::pending()->count()`
- Set up alerts if pending exceeds threshold
- Process retryable emails regularly

---

## Security Considerations

### Email Content
- No sensitive data in email subject
- Payment details shown only in body
- Guest info limited to name and email
- Booking reference included for identification

### Access Control
- Email resend requires admin authentication
- Email history only visible to admin
- Statistics only visible to admin
- No guest access to email logs

### Data Privacy
- Email addresses stored in email_outbox (audit trail)
- Compliance with data retention policies
- Consider archiving old emails periodically

---

## Future Enhancements

1. **Email Templates**
   - Multiple template variations
   - Customizable email header/footer
   - Admin template editor

2. **Delivery Tracking**
   - Open tracking (pixel)
   - Click tracking on links
   - Bounce handling

3. **Scheduling**
   - Delay email send (batch sending)
   - Scheduled resends
   - Quiet hours configuration

4. **Analytics**
   - Delivery rate metrics
   - Open rate analysis
   - Bounce rate reporting

5. **Attachments**
   - PDF receipt attachment
   - Invoice attachment
   - Terms & conditions

6. **Integrations**
   - SendGrid integration
   - Mailgun integration
   - SMS notifications

---

## Testing

### Unit Test Example

```php
test('receipt_email_queued_on_creation', function () {
    $receipt = Receipt::factory()->create();
    
    Mail::fake();
    
    $emailService = new EmailService();
    $emailOutbox = $emailService->queueReceiptNotification($receipt);
    
    expect($emailOutbox)->toBeInstanceOf(EmailOutbox::class);
    expect($emailOutbox->status)->toBe('PENDING');
    expect($emailOutbox->receipt_id)->toBe($receipt->id);
    
    Mail::assertQueued(ReceiptNotificationMail::class);
});
```

### Integration Test Example

```php
test('admin_can_resend_email', function () {
    $emailOutbox = EmailOutbox::factory()->failed()->create();
    
    Mail::fake();
    
    $response = $this->post(
        "/admin/payment/emails/{$emailOutbox->id}/resend",
        [],
        ['Authorization' => 'Bearer ' . $token]
    );
    
    expect($response->status())->toBe(200);
    expect($response->json('success'))->toBeTrue();
    
    $emailOutbox->refresh();
    expect($emailOutbox->retry_count)->toBe(1);
});
```

---

## Summary

The Email Notification System provides:
- ✅ Automatic receipt emails on payment
- ✅ Reliable queued delivery
- ✅ Complete audit trail
- ✅ Admin resend capability
- ✅ Retry logic for failed emails
- ✅ Professional templates
- ✅ Status tracking

**Status:** ✅ Production Ready
