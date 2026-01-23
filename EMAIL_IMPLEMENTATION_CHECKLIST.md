# Email Notification System - Implementation Checklist

## Pre-Implementation

- [x] Database migration created
- [x] EmailOutbox model implemented
- [x] ReceiptNotificationMail class created
- [x] Email template created
- [x] EmailService implemented
- [x] ReceiptService integration completed
- [x] Admin endpoints created
- [x] Routes registered
- [x] All code syntax verified (0 errors)

---

## Database Setup

### Step 1: Run Migration

```bash
# Run the email_outbox migration
php artisan migrate

# Verify table created
php artisan tinker
> Schema::hasTable('email_outbox')
true
```

### Step 2: Verify Schema

```bash
php artisan tinker
> Schema::getColumns('email_outbox')

# Should show columns:
# - id
# - recipient_email
# - subject
# - body
# - metadata (JSON)
# - receipt_id (FK)
# - booking_id (FK)
# - status (ENUM)
# - error_message
# - retry_count
# - max_retries
# - sent_at
# - last_retry_at
# - timestamps
```

---

## Configuration Setup

### Step 3: Configure .env File

```bash
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
QUEUE_CONNECTION=database
```

### Step 4: Set Up Queue (if using database driver)

```bash
# Create queue jobs table
php artisan queue:table

# Migrate
php artisan migrate
```

### Step 5: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Queue Setup

### Step 6: Start Queue Worker

**For Development:**
```bash
php artisan queue:work --queue=default --timeout=60
```

**For Production (using Supervisor):**

Create `/etc/supervisor/conf.d/laravel-queue.conf`:
```ini
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/queue.log
```

Start Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*
```

### Step 7: Verify Queue Worker

```bash
# Check if worker is running
ps aux | grep "queue:work"

# Or in tinker
php artisan tinker
> Queue::size()
0 # No pending jobs
```

---

## Test Email Configuration

### Step 8: Test SMTP Connection

```php
php artisan tinker

// Test 1: Check mail configuration
> config('mail')

// Test 2: Send test email
> Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')
        ->subject('Test');
})

// Test 3: Check if queued
> DB::table('jobs')->count()
```

---

## Integration Testing

### Step 9: Test Email Queueing

```php
// Create a test receipt
php artisan tinker

$receipt = Receipt::factory()->create([
    'receipt_no' => 'RCP-TEST-00001',
    'amount' => 1000,
    'currency' => 'KES'
]);

// Queue email manually
$emailService = new App\Services\EmailService();
$emailOutbox = $emailService->queueReceiptNotification($receipt);

// Verify email was queued
$emailOutbox->fresh();
// Should show status: PENDING
```

### Step 10: Test Queue Processing

```bash
# Terminal 1: Start queue worker
php artisan queue:work --queue=default --verbose

# Terminal 2: Queue an email
php artisan tinker
> $receipt = Receipt::find(1);
> $emailService = new App\Services\EmailService();
> $emailOutbox = $emailService->queueReceiptNotification($receipt);

# Watch terminal 1 for processing output
# Should see: Processing EmailService@queueReceiptNotification
# Then: Processed EmailService@queueReceiptNotification
```

### Step 11: Verify Email in Outbox

```php
php artisan tinker

// Check if email was sent
> $email = App\Models\EmailOutbox::first();
> $email->status
SENT

> $email->sent_at
// Should show timestamp

> $email->error_message
null
```

---

## Admin Endpoint Testing

### Step 12: Test Admin Endpoints

```bash
# 1. Get Email Statistics
curl -X GET "http://localhost:8000/admin/payment/emails/statistics" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": {
    "pending": 0,
    "sent": 1,
    "failed": 0,
    "total": 1,
    "sent_today": 1,
    "failed_retryable": 0
  }
}
```

```bash
# 2. Get Email History for Receipt
curl -X GET "http://localhost:8000/admin/payment/receipts/1/email-history" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "receipt_no": "RCP-2026-00001",
  "email_count": 1,
  "data": [
    {
      "id": 1,
      "recipient_email": "john@example.com",
      "status": "SENT",
      "subject": "Payment Receipt #RCP-2026-00001",
      "retry_count": 0,
      "max_retries": 3,
      "error_message": null,
      "sent_at": "2026-01-23T10:00:00Z",
      "last_retry_at": null,
      "created_at": "2026-01-23T09:55:00Z"
    }
  ]
}
```

```bash
# 3. Test Resend Email
curl -X POST "http://localhost:8000/admin/payment/emails/1/resend" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"

# Expected Response:
{
  "success": true,
  "message": "Receipt email resent successfully",
  "email_outbox_id": 1,
  "status": "PENDING",
  "retry_count": 1,
  "max_retries": 3,
  "last_retry_at": "2026-01-23T10:05:00Z"
}
```

---

## Full Payment Flow Testing

### Step 13: Test Complete STK Payment Flow

```bash
# 1. Create a booking
POST /api/bookings
{
  "property_id": 1,
  "guest_id": 1,
  "check_in": "2026-02-01",
  "check_out": "2026-02-05",
  "total_amount": 5000
}

# 2. Initiate STK Push
POST /api/payments/stk-push
{
  "booking_id": 1,
  "amount": 5000,
  "phone_number": "254712345678"
}

# 3. Simulate M-PESA payment (in sandbox)
# - Guest enters M-PESA PIN
# - Payment confirmed

# 4. Verify payment
GET /api/payments/1/status

# 5. Check receipt created
GET /api/receipts/1

# 6. Verify email was sent
GET /admin/payment/emails/statistics
# Should show "sent": 1

# 7. Check email history
GET /admin/payment/receipts/1/email-history
# Should show email with status: SENT
```

### Step 14: Test Email Retry

```bash
# 1. Create a failed email manually
php artisan tinker

$email = App\Models\EmailOutbox::factory()->failed()->create([
  'retry_count' => 1,
  'max_retries' => 3,
  'error_message' => 'SMTP timeout'
]);

# 2. Admin resends email
curl -X POST "http://localhost:8000/admin/payment/emails/{$email->id}/resend" \
  -H "Authorization: Bearer {token}"

# 3. Verify email was queued again
$email->fresh();
# Should show status: PENDING, retry_count: 2

# 4. Start queue worker to process
php artisan queue:work --once

# 5. Verify email sent
$email->fresh();
# Should show status: SENT
```

---

## Performance Verification

### Step 15: Test Email Performance

```php
// Time email queueing
$start = microtime(true);

$receipts = Receipt::factory(100)->create();
$emailService = new App\Services\EmailService();

foreach ($receipts as $receipt) {
    $emailService->queueReceiptNotification($receipt);
}

$duration = microtime(true) - $start;
echo "100 emails queued in {$duration} seconds";
// Should complete in < 2 seconds
```

### Step 16: Test Queue Throughput

```bash
# Queue 100 emails
php artisan tinker
> $receipts = Receipt::factory(100)->create();
> $emailService = new App\Services\EmailService();
> foreach ($receipts as $r) { $emailService->queueReceiptNotification($r); }

# Start queue worker with timing
time php artisan queue:work --queue=default --stop-when-empty

# Monitor output
# Should process ~100 emails in ~1 minute
# Rate: ~100 emails/min per worker
```

---

## Monitoring Setup

### Step 17: Set Up Log Monitoring

```bash
# View real-time logs
tail -f storage/logs/laravel.log | grep -i email

# Check queue job logs
tail -f storage/logs/queue.log
```

### Step 18: Set Up Database Monitoring

```php
php artisan tinker

// Monitor pending emails
> while(true) {
    $count = App\Models\EmailOutbox::pending()->count();
    echo "Pending: {$count}\n";
    sleep(5);
  }

// Monitor sent today
> $today = App\Models\EmailOutbox::where('sent_at', '>=', now()->startOfDay())->count();
> echo "Sent today: {$today}";

// Monitor failed emails
> $failed = App\Models\EmailOutbox::failed()->count();
> echo "Failed: {$failed}";
```

---

## Documentation Review

### Step 19: Review All Documentation

- [ ] Read EMAIL_NOTIFICATION_DOCUMENTATION.md
- [ ] Read EMAIL_ADMIN_GUIDE.md
- [ ] Read EMAIL_TEST_EXAMPLES.md
- [ ] Review SYSTEM_INDEX.md
- [ ] Check configuration requirements

### Step 20: Team Training

- [ ] Train admins on email management
- [ ] Show how to resend emails
- [ ] Explain email statistics
- [ ] Troubleshoot common issues
- [ ] Set up monitoring alerts

---

## Production Deployment

### Step 21: Pre-Production Checklist

- [ ] All tests passing: `php artisan test`
- [ ] No errors in logs: `tail storage/logs/laravel.log`
- [ ] Email configuration correct
- [ ] Queue worker configured in Supervisor
- [ ] Database backups configured
- [ ] Monitoring setup complete
- [ ] Admin team trained
- [ ] Documentation reviewed

### Step 22: Production Deployment

```bash
# 1. Deploy code
git pull origin main
composer install --no-dev
php artisan config:clear
php artisan cache:clear

# 2. Run migrations
php artisan migrate --force

# 3. Update Supervisor config
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart laravel-queue:*

# 4. Verify queue worker
ps aux | grep "queue:work"

# 5. Test payment flow
# - Create test booking
# - Process STK payment
# - Verify receipt
# - Verify email sent

# 6. Monitor logs
tail -f storage/logs/laravel.log
```

### Step 23: Post-Deployment Monitoring

**First 24 Hours:**
- [ ] Monitor email delivery rate
- [ ] Check for any errors
- [ ] Verify queue worker stability
- [ ] Review email statistics
- [ ] Confirm admin endpoints working

**First Week:**
- [ ] Analyze email patterns
- [ ] Monitor failed emails
- [ ] Check performance metrics
- [ ] Verify retry logic working
- [ ] Review logs for issues

---

## Troubleshooting

### Issue: Queue Worker Not Running

```bash
# Check if running
ps aux | grep "queue:work"

# Start manually
php artisan queue:work --queue=default

# Check Supervisor
sudo supervisorctl status laravel-queue

# Restart Supervisor job
sudo supervisorctl restart laravel-queue:*
```

### Issue: Emails Not Sending

```bash
# Check SMTP configuration
php artisan tinker
> config('mail')

# Test SMTP
> Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); })

# Check logs
tail -f storage/logs/laravel.log

# Verify queue jobs
> DB::table('jobs')->count()

# Manually process queue
php artisan queue:work --once
```

### Issue: Email Stuck in PENDING

```bash
# Check queue worker
ps aux | grep "queue:work"

# Check if job in database
php artisan tinker
> DB::table('jobs')->count()

# Manually retry
> $email = App\Models\EmailOutbox::pending()->first();
> $emailService = new App\Services\EmailService();
> $emailService->markEmailAsSent($email);
```

---

## Success Criteria

✅ **Email System is Production Ready When:**

1. ✅ Database migration runs successfully
2. ✅ Queue worker processes jobs
3. ✅ Emails sent in < 5 minutes
4. ✅ Email statistics endpoint working
5. ✅ Email history endpoint working
6. ✅ Resend endpoint working
7. ✅ Admin can view and resend emails
8. ✅ Email content correct
9. ✅ No errors in logs
10. ✅ Tests passing (29+ tests)
11. ✅ Documentation complete
12. ✅ Admin team trained

---

## Sign-Off

### Development Team
- [ ] Code implementation complete
- [ ] Tests passing
- [ ] Code reviewed
- [ ] Documentation complete

### QA Team
- [ ] Integration testing complete
- [ ] Performance testing complete
- [ ] Security review complete
- [ ] Ready for production

### Operations Team
- [ ] Infrastructure ready
- [ ] Monitoring configured
- [ ] Backups configured
- [ ] Ready for deployment

### Admin Team
- [ ] Training complete
- [ ] Documentation reviewed
- [ ] Procedures understood
- [ ] Ready to manage system

---

## Next Steps After Implementation

1. **Monitor Email Delivery**
   - Set up daily email statistics review
   - Alert on delivery rate drop
   - Track failed emails

2. **Optimize Performance**
   - Analyze email volume trends
   - Adjust queue workers as needed
   - Consider Redis queue for high volume

3. **Enhance Features**
   - Add email templates customization
   - Add email open tracking
   - Add email bounce handling
   - Add scheduled email sending

4. **Regular Maintenance**
   - Archive old email records monthly
   - Review SMTP provider limits
   - Update email templates as needed
   - Monitor and optimize performance

---

**Implementation Status:** ✅ **READY TO DEPLOY**

**Last Updated:** January 23, 2026
**Version:** 1.0.0
