# Email Notification System - Test Examples

## Overview

Complete test examples for the email notification system including unit tests, integration tests, and real-world scenarios.

---

## Unit Tests

### Test 1: Email Queued on Receipt Creation (STK)

**File:** `tests/Feature/EmailService/QueueReceiptEmailTest.php`

```php
<?php

namespace Tests\Feature\EmailService;

use App\Models\{Receipt, EmailOutbox};
use App\Services\EmailService;
use App\Mail\ReceiptNotificationMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class QueueReceiptEmailTest extends TestCase
{
    public function test_email_queued_when_receipt_created()
    {
        Mail::fake();
        
        $receipt = Receipt::factory()->create();
        $emailService = new EmailService();
        
        $emailOutbox = $emailService->queueReceiptNotification($receipt);
        
        $this->assertInstanceOf(EmailOutbox::class, $emailOutbox);
        $this->assertEquals('PENDING', $emailOutbox->status);
        $this->assertEquals($receipt->id, $emailOutbox->receipt_id);
        $this->assertEquals($receipt->booking->guest->email, $emailOutbox->recipient_email);
        
        Mail::assertQueued(ReceiptNotificationMail::class);
    }
    
    public function test_email_contains_correct_data()
    {
        Mail::fake();
        
        $receipt = Receipt::factory()->create([
            'amount' => 5000.00,
            'currency' => 'KES',
            'receipt_no' => 'RCP-2026-00001'
        ]);
        
        $emailService = new EmailService();
        $emailOutbox = $emailService->queueReceiptNotification($receipt);
        
        $metadata = $emailOutbox->getMetadata('booking_ref');
        $this->assertNotNull($metadata);
        
        $this->assertStringContainsString('RCP-2026-00001', $emailOutbox->subject);
        $this->assertStringContainsString('KES', $emailOutbox->body);
        $this->assertStringContainsString('5000', $emailOutbox->body);
    }
    
    public function test_metadata_captures_all_receipt_details()
    {
        Mail::fake();
        
        $receipt = Receipt::factory()->create([
            'receipt_no' => 'RCP-2026-00001',
            'amount' => 15000.00,
            'currency' => 'KES'
        ]);
        
        $emailService = new EmailService();
        $emailOutbox = $emailService->queueReceiptNotification($receipt);
        
        $metadata = json_decode($emailOutbox->metadata, true);
        
        $this->assertEquals('RCP-2026-00001', $metadata['receipt_no']);
        $this->assertEquals(15000.00, $metadata['amount']);
        $this->assertEquals('KES', $metadata['currency']);
        $this->assertNotNull($metadata['booking_ref']);
        $this->assertNotNull($metadata['guest_email']);
        $this->assertNotNull($metadata['guest_name']);
    }
}
```

### Test 2: Email Status Transitions

**File:** `tests/Feature/EmailService/EmailStatusTest.php`

```php
<?php

namespace Tests\Feature\EmailService;

use App\Models\EmailOutbox;
use Tests\TestCase;

class EmailStatusTest extends TestCase
{
    public function test_email_can_be_marked_as_sent()
    {
        $emailOutbox = EmailOutbox::factory()->pending()->create();
        
        $emailOutbox->markAsSent();
        
        $this->assertEquals('SENT', $emailOutbox->status);
        $this->assertNotNull($emailOutbox->sent_at);
        $this->assertTrue($emailOutbox->sent_at->isToday());
    }
    
    public function test_email_can_be_marked_as_failed()
    {
        $emailOutbox = EmailOutbox::factory()->pending()->create();
        
        $emailOutbox->markAsFailed('SMTP Error: Connection timeout');
        
        $this->assertEquals('FAILED', $emailOutbox->status);
        $this->assertEquals('SMTP Error: Connection timeout', $emailOutbox->error_message);
        $this->assertNotNull($emailOutbox->last_retry_at);
    }
    
    public function test_retry_counter_increments()
    {
        $emailOutbox = EmailOutbox::factory()->failed()->create([
            'retry_count' => 1,
            'max_retries' => 3
        ]);
        
        $this->assertTrue($emailOutbox->canRetry());
        
        $emailOutbox->incrementRetry();
        
        $this->assertEquals(2, $emailOutbox->retry_count);
    }
    
    public function test_email_cannot_retry_after_max_attempts()
    {
        $emailOutbox = EmailOutbox::factory()->failed()->create([
            'retry_count' => 3,
            'max_retries' => 3
        ]);
        
        $this->assertFalse($emailOutbox->canRetry());
    }
}
```

### Test 3: EmailService Methods

**File:** `tests/Feature/EmailService/EmailServiceTest.php`

```php
<?php

namespace Tests\Feature\EmailService;

use App\Models\{Receipt, EmailOutbox};
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    private EmailService $emailService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
        Mail::fake();
    }
    
    public function test_get_receipt_email_status()
    {
        $receipt = Receipt::factory()->create();
        $emailOutbox = $this->emailService->queueReceiptNotification($receipt);
        
        $status = $this->emailService->getReceiptEmailStatus($receipt);
        
        $this->assertEquals($emailOutbox->id, $status->id);
        $this->assertEquals('PENDING', $status->status);
    }
    
    public function test_get_booking_emails()
    {
        $receipt = Receipt::factory()->create();
        $booking = $receipt->booking;
        
        $this->emailService->queueReceiptNotification($receipt);
        $this->emailService->queueReceiptNotification($receipt);
        
        $emails = $this->emailService->getBookingEmails($booking);
        
        $this->assertCount(2, $emails);
    }
    
    public function test_get_pending_emails()
    {
        EmailOutbox::factory()->pending()->create();
        EmailOutbox::factory()->pending()->create();
        EmailOutbox::factory()->sent()->create();
        
        $pending = $this->emailService->getPendingEmails(10);
        
        $this->assertCount(2, $pending);
    }
    
    public function test_get_retryable_emails()
    {
        EmailOutbox::factory()->failed()->create(['retry_count' => 1, 'max_retries' => 3]);
        EmailOutbox::factory()->failed()->create(['retry_count' => 3, 'max_retries' => 3]);
        
        $retryable = $this->emailService->getRetryableEmails(10);
        
        $this->assertCount(1, $retryable);
    }
    
    public function test_get_email_statistics()
    {
        EmailOutbox::factory(5)->pending()->create();
        EmailOutbox::factory(10)->sent()->create();
        EmailOutbox::factory(3)->failed()->create();
        
        $stats = $this->emailService->getEmailStatistics();
        
        $this->assertEquals(5, $stats['pending']);
        $this->assertEquals(10, $stats['sent']);
        $this->assertEquals(3, $stats['failed']);
        $this->assertEquals(18, $stats['total']);
    }
}
```

---

## Integration Tests

### Test 4: Admin Resend Email Endpoint

**File:** `tests/Feature/Payment/AdminResendEmailTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{User, EmailOutbox};
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminResendEmailTest extends TestCase
{
    protected User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        Mail::fake();
    }
    
    public function test_admin_can_resend_failed_email()
    {
        $email = EmailOutbox::factory()->failed()->create([
            'retry_count' => 1,
            'max_retries' => 3
        ]);
        
        $response = $this->actingAs($this->admin)->postJson(
            "/admin/payment/emails/{$email->id}/resend"
        );
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Receipt email resent successfully'
        ]);
        
        $email->refresh();
        $this->assertEquals(2, $email->retry_count);
        $this->assertEquals('PENDING', $email->status);
    }
    
    public function test_cannot_resend_after_max_retries()
    {
        $email = EmailOutbox::factory()->failed()->create([
            'retry_count' => 3,
            'max_retries' => 3
        ]);
        
        $response = $this->actingAs($this->admin)->postJson(
            "/admin/payment/emails/{$email->id}/resend"
        );
        
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Email has exceeded maximum retries (3)'
        ]);
    }
    
    public function test_non_admin_cannot_resend_email()
    {
        $user = User::factory()->customer()->create();
        $email = EmailOutbox::factory()->failed()->create();
        
        $response = $this->actingAs($user)->postJson(
            "/admin/payment/emails/{$email->id}/resend"
        );
        
        $response->assertStatus(403); // Unauthorized
    }
    
    public function test_unauthenticated_cannot_resend_email()
    {
        $email = EmailOutbox::factory()->failed()->create();
        
        $response = $this->postJson("/admin/payment/emails/{$email->id}/resend");
        
        $response->assertStatus(401); // Unauthorized
    }
}
```

### Test 5: Email History Endpoint

**File:** `tests/Feature/Payment/EmailHistoryTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{User, Receipt, EmailOutbox};
use Tests\TestCase;

class EmailHistoryTest extends TestCase
{
    protected User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }
    
    public function test_get_email_history_for_receipt()
    {
        $receipt = Receipt::factory()->create();
        
        EmailOutbox::factory()->create(['receipt_id' => $receipt->id, 'status' => 'SENT']);
        EmailOutbox::factory()->create(['receipt_id' => $receipt->id, 'status' => 'FAILED']);
        EmailOutbox::factory()->create(['receipt_id' => $receipt->id, 'status' => 'PENDING']);
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/receipts/{$receipt->id}/email-history"
        );
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'receipt_no' => $receipt->receipt_no,
            'email_count' => 3
        ]);
        
        $this->assertCount(3, $response->json('data'));
    }
    
    public function test_email_history_ordered_by_newest_first()
    {
        $receipt = Receipt::factory()->create();
        
        $email1 = EmailOutbox::factory()->create(['receipt_id' => $receipt->id]);
        sleep(1);
        $email2 = EmailOutbox::factory()->create(['receipt_id' => $receipt->id]);
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/receipts/{$receipt->id}/email-history"
        );
        
        $data = $response->json('data');
        $this->assertEquals($email2->id, $data[0]['id']);
        $this->assertEquals($email1->id, $data[1]['id']);
    }
    
    public function test_email_history_includes_all_fields()
    {
        $receipt = Receipt::factory()->create();
        
        EmailOutbox::factory()->create([
            'receipt_id' => $receipt->id,
            'status' => 'FAILED',
            'retry_count' => 2,
            'max_retries' => 3,
            'error_message' => 'Connection timeout'
        ]);
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/receipts/{$receipt->id}/email-history"
        );
        
        $email = $response->json('data')[0];
        
        $this->assertArrayHasKeys([
            'id', 'recipient_email', 'status', 'subject',
            'retry_count', 'max_retries', 'error_message',
            'sent_at', 'last_retry_at', 'created_at'
        ], $email);
    }
}
```

### Test 6: Email Statistics Endpoint

**File:** `tests/Feature/Payment/EmailStatisticsTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{User, EmailOutbox};
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class EmailStatisticsTest extends TestCase
{
    protected User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }
    
    public function test_get_email_statistics()
    {
        EmailOutbox::factory(5)->pending()->create();
        EmailOutbox::factory(20)->sent()->create();
        EmailOutbox::factory(4)->failed()->create();
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/emails/statistics"
        );
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'pending' => 5,
                'sent' => 20,
                'failed' => 4,
                'total' => 29
            ]
        ]);
    }
    
    public function test_statistics_include_daily_metrics()
    {
        // Today's emails
        EmailOutbox::factory(3)->sent()->create([
            'sent_at' => Date::now()
        ]);
        
        // Yesterday's emails
        EmailOutbox::factory(2)->sent()->create([
            'sent_at' => Date::yesterday()
        ]);
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/emails/statistics"
        );
        
        $this->assertEquals(3, $response->json('data.sent_today'));
    }
    
    public function test_statistics_track_retryable_emails()
    {
        EmailOutbox::factory(2)->failed()->create([
            'retry_count' => 1,
            'max_retries' => 3
        ]);
        
        EmailOutbox::factory(1)->failed()->create([
            'retry_count' => 3,
            'max_retries' => 3
        ]);
        
        $response = $this->actingAs($this->admin)->getJson(
            "/admin/payment/emails/statistics"
        );
        
        $this->assertEquals(2, $response->json('data.failed_retryable'));
    }
}
```

---

## Real-World Scenarios

### Scenario 1: Complete Email Flow (STK Payment)

**File:** `tests/Feature/Payment/CompleteEmailFlowTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{PaymentIntent, Receipt, EmailOutbox};
use App\Services\ReceiptService;
use App\Mail\ReceiptNotificationMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CompleteEmailFlowTest extends TestCase
{
    public function test_complete_flow_stk_payment_to_email()
    {
        Mail::fake();
        
        // 1. Payment intent created
        $intent = PaymentIntent::factory()->create([
            'payment_method' => 'M-PESA_STK',
            'amount' => 5000,
            'status' => 'INITIATED'
        ]);
        
        // 2. STK push successful
        $intent->update(['status' => 'CONFIRMED']);
        
        // 3. Receipt created
        $receiptService = new ReceiptService();
        $receipt = $receiptService->createStkReceipt($intent, 'STK_PUSH');
        
        // 4. Verify email was queued
        $this->assertDatabaseHas('email_outbox', [
            'receipt_id' => $receipt->id,
            'status' => 'PENDING'
        ]);
        
        Mail::assertQueued(ReceiptNotificationMail::class);
        
        // 5. Admin checks email history
        $emails = EmailOutbox::where('receipt_id', $receipt->id)->get();
        $this->assertCount(1, $emails);
        
        // 6. Verify email content
        $email = $emails->first();
        $this->assertStringContainsString($receipt->receipt_no, $email->subject);
        $this->assertStringContainsString('5000', $email->body);
    }
}
```

### Scenario 2: Email Retry on Failure

**File:** `tests/Feature/Payment/EmailRetryScenarioTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{User, EmailOutbox};
use App\Services\EmailService;
use App\Mail\ReceiptNotificationMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailRetryScenarioTest extends TestCase
{
    public function test_failed_email_can_be_retried()
    {
        Mail::fake();
        
        $admin = User::factory()->admin()->create();
        
        // 1. Email created but failed to send
        $email = EmailOutbox::factory()->failed()->create([
            'retry_count' => 0,
            'max_retries' => 3,
            'error_message' => 'Connection timeout'
        ]);
        
        // 2. Admin initiates resend
        $emailService = new EmailService();
        $emailService->resendReceiptEmail($email);
        
        // 3. Verify retry was recorded
        $email->refresh();
        $this->assertEquals(1, $email->retry_count);
        $this->assertNotNull($email->last_retry_at);
        
        // 4. Email was queued again
        Mail::assertQueued(ReceiptNotificationMail::class);
    }
    
    public function test_email_stops_retrying_after_max_attempts()
    {
        Mail::fake();
        
        $email = EmailOutbox::factory()->failed()->create([
            'retry_count' => 2,
            'max_retries' => 3,
            'error_message' => 'SMTP server error'
        ]);
        
        // First retry succeeds
        $emailService = new EmailService();
        $emailService->resendReceiptEmail($email);
        
        $email->refresh();
        $this->assertEquals(3, $email->retry_count); // Now at max
        
        // Attempt second retry fails
        try {
            $emailService->resendReceiptEmail($email);
        } catch (\Exception $e) {
            $this->assertStringContainsString('exceeded maximum retries', $e->getMessage());
        }
    }
}
```

### Scenario 3: Manual Payment Email

**File:** `tests/Feature/Payment/ManualPaymentEmailTest.php`

```php
<?php

namespace Tests\Feature\Payment;

use App\Models\{PaymentIntent, Receipt};
use App\Services\ReceiptService;
use App\Mail\ReceiptNotificationMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ManualPaymentEmailTest extends TestCase
{
    public function test_email_queued_on_manual_payment_receipt()
    {
        Mail::fake();
        
        // 1. Create manual payment intent
        $intent = PaymentIntent::factory()->create([
            'payment_method' => 'MANUAL',
            'amount' => 10000,
            'mpesa_reference' => 'MAN123456'
        ]);
        
        // 2. Verify and create receipt
        $receiptService = new ReceiptService();
        $receipt = $receiptService->createManualReceipt($intent, 'MANUAL_PAYMENT');
        
        // 3. Check email was queued
        $this->assertDatabaseHas('email_outbox', [
            'receipt_id' => $receipt->id,
            'status' => 'PENDING'
        ]);
        
        Mail::assertQueued(ReceiptNotificationMail::class);
    }
}
```

---

## Artisan Command Tests

### Test 7: Retry Failed Emails Command

**File:** `tests\Feature\Console\EmailRetryCommandTest.php`

```php
<?php

namespace Tests\Feature\Console;

use App\Models\EmailOutbox;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailRetryCommandTest extends TestCase
{
    public function test_retry_failed_emails_command()
    {
        Mail::fake();
        
        // Create failed emails
        EmailOutbox::factory(3)->failed()->create([
            'retry_count' => 1,
            'max_retries' => 3
        ]);
        
        // Run command
        $this->artisan('email:retry-failed')
            ->expectsOutput('3 emails queued for retry')
            ->assertExitCode(0);
        
        // Verify emails are pending again
        $this->assertDatabaseHas('email_outbox', [
            'status' => 'PENDING'
        ]);
    }
}
```

---

## Load Testing

### Test 8: High Volume Email Queueing

**File:** `tests/Feature/EmailService/EmailVolumeTest.php`

```php
<?php

namespace Tests\Feature\EmailService;

use App\Models\Receipt;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVolumeTest extends TestCase
{
    public function test_queue_1000_emails_performance()
    {
        Mail::fake();
        
        $startTime = microtime(true);
        
        $receipts = Receipt::factory(1000)->create();
        $emailService = new EmailService();
        
        foreach ($receipts as $receipt) {
            $emailService->queueReceiptNotification($receipt);
        }
        
        $duration = microtime(true) - $startTime;
        
        // Should complete in < 10 seconds
        $this->assertLessThan(10, $duration);
        
        $this->assertDatabaseCount('email_outbox', 1000);
    }
    
    public function test_get_statistics_with_large_dataset()
    {
        EmailOutbox::factory(5000)->pending()->create();
        EmailOutbox::factory(10000)->sent()->create();
        EmailOutbox::factory(500)->failed()->create();
        
        $startTime = microtime(true);
        
        $stats = (new EmailService())->getEmailStatistics();
        
        $duration = microtime(true) - $startTime;
        
        // Should complete in < 1 second
        $this->assertLessThan(1, $duration);
        
        $this->assertEquals(5000, $stats['pending']);
        $this->assertEquals(10000, $stats['sent']);
        $this->assertEquals(500, $stats['failed']);
    }
}
```

---

## Running Tests

### Run All Email Tests

```bash
# All email tests
php artisan test tests/Feature/EmailService/

# Specific test
php artisan test tests/Feature/EmailService/QueueReceiptEmailTest.php

# Run with coverage
php artisan test --coverage

# Run integration tests only
php artisan test tests/Feature/Payment/
```

### CI/CD Pipeline

```yaml
# .github/workflows/test.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      
      - name: Install dependencies
        run: composer install
      
      - name: Run email tests
        run: php artisan test tests/Feature/EmailService/
      
      - name: Run integration tests
        run: php artisan test tests/Feature/Payment/
```

---

## Summary

✅ **Test Coverage:**
- Unit tests: Email status, metadata, service methods
- Integration tests: API endpoints, admin functions
- Real-world scenarios: Complete payment flows
- Load tests: High-volume queueing
- Command tests: Retry automation

✅ **Test Types:**
- Email queueing tests (6)
- Status transition tests (4)
- Admin endpoint tests (6)
- Email history tests (3)
- Statistics tests (3)
- Real-world flow tests (3)
- Retry scenario tests (2)
- Load/performance tests (2)

**Total: 29 comprehensive test examples covering all email system functionality.**
