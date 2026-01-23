# Email Notification System - Admin Guide

## Overview

Complete guide for administrators managing the email notification system, including monitoring, troubleshooting, and resending emails.

---

## Dashboard & Monitoring

### Email Statistics Dashboard

**URL:** `/admin/payment/emails/statistics`

**Purpose:** Monitor overall email delivery health and system status.

**Information Provided:**
```json
{
  "pending": 5,        // Emails waiting to be sent
  "sent": 142,         // Successfully delivered emails
  "failed": 3,         // Failed emails
  "total": 150,        // Total emails in system
  "sent_today": 12,    // Emails sent in last 24 hours
  "failed_retryable": 2 // Failed emails that can be retried
}
```

**How to Use:**
1. Access the admin dashboard
2. Navigate to **Payments > Email Statistics**
3. Review metrics:
   - **Green Zone:** sent > 95%, pending < 10
   - **Yellow Zone:** sent 80-95%, pending 10-20
   - **Red Zone:** sent < 80%, pending > 20

**What to Monitor:**
- **Pending Count:** Should not grow indefinitely (queue worker running?)
- **Sent Today:** Confirms email delivery is working
- **Failed Retryable:** Emails eligible for manual retry
- **Failed:** Emails that exceeded retry limit

---

## Email History & Resend

### View Email History for a Receipt

**Access:** Click on receipt → **Email History** tab

**Shows:**
- All email send attempts for a receipt
- Current status (PENDING, SENT, FAILED)
- Retry count and max retries
- Error messages if failed
- Timestamps (sent, last retry, created)

**Email History Example:**
```
Receipt #RCP-2026-00001 - Email History
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Email ID  | To           | Status  | Retries | Error       | Sent At
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
5         | john@ex.com  | SENT    | 0/3     | —           | ✓ 10:15 AM
3         | john@ex.com  | FAILED  | 1/3     | Timeout     | ✗ 9:45 AM
1         | john@ex.com  | PENDING | 0/3     | —           | — (queued)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Resend Failed Email

**When to Resend:**
1. Email failed to send (FAILED status)
2. Retry count < Max retries (usually 3)
3. Network was temporarily down
4. SMTP credentials were incorrect (now fixed)

**How to Resend:**
1. View receipt → **Email History** tab
2. Click **Resend** button on failed email
3. System will:
   - Increment retry counter
   - Queue email again
   - Update status to PENDING
   - Send email via queue worker

**Response after Resend:**
```
✓ Success: Email resent successfully
  Status: PENDING
  Retry Count: 2/3
  Last Retry: 2026-01-23 10:20 AM
```

**Cannot Resend When:**
- Email already sent (SENT status)
- Max retries exceeded (retry_count >= max_retries)
- Email deleted from system

---

## Troubleshooting Email Issues

### Problem: Emails Not Being Sent (Status: PENDING)

**Symptoms:**
- Email history shows PENDING status
- Email count keeps increasing
- Old emails never move to SENT or FAILED

**Root Cause:** Queue worker not running

**Solution:**
1. Check queue worker status:
   ```bash
   ps aux | grep "queue:work"
   ```

2. If not running, start it:
   ```bash
   php artisan queue:work --queue=default
   ```

3. For production, use Supervisor (see below)

4. Monitor queue:
   ```bash
   php artisan queue:work --queue=default --verbose
   ```

**Prevention:**
- Use process manager (Supervisor, systemd)
- Set up monitoring alerts
- Check logs daily

### Problem: Emails Failing with SMTP Errors

**Symptoms:**
- Error message: "SMTP Error: Connection timeout"
- Error message: "Authentication failed"
- Status: FAILED

**Root Cause:** Email configuration issue

**Check Configuration:**
1. Verify `.env` file:
   ```
   MAIL_MAILER=smtp              ✓ Should be 'smtp'
   MAIL_HOST=smtp.mailtrap.io    ✓ Correct host?
   MAIL_PORT=587                 ✓ Correct port?
   MAIL_USERNAME=xxx             ✓ Correct username?
   MAIL_PASSWORD=xxx             ✓ Correct password?
   MAIL_ENCRYPTION=tls           ✓ tls or ssl?
   ```

2. Test SMTP connection:
   ```php
   // In tinker
   php artisan tinker
   
   Mail::raw('Test', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

3. Check mail logs:
   ```bash
   tail -f storage/logs/laravel.log | grep -i mail
   ```

**Solution:**
1. Correct email configuration
2. Clear config cache: `php artisan config:clear`
3. Manually resend via admin panel
4. Monitor for new failures

### Problem: Same Email Sent Multiple Times

**Symptoms:**
- Guest receives same receipt email 2-3 times
- EmailOutbox shows multiple SENT records

**Root Cause:** Queue job processed multiple times

**Prevention:**
1. Ensure database driver: `QUEUE_CONNECTION=database`
2. Avoid running multiple queue workers on same job
3. Set job timeout properly

**Fix:**
1. Review email_outbox records
2. Mark duplicates as reviewed (add note)
3. No action needed (duplicates already delivered)

### Problem: Email Content Missing Information

**Symptoms:**
- Receipt number not in email
- Amount not displayed
- Guest name missing

**Root Cause:** Template issue or data missing

**Check:**
1. View email body in email_outbox table
2. Verify data in receipt and booking records
3. Check template file: `resources/views/emails/receipt-notification.blade.php`

**Solution:**
1. Verify receipt data is complete:
   ```sql
   SELECT receipt_no, amount, currency FROM receipts WHERE id = X;
   ```

2. Verify booking data:
   ```sql
   SELECT booking_ref FROM bookings WHERE id = X;
   ```

3. Verify guest data:
   ```sql
   SELECT email, name FROM users WHERE id = X AND type = 'guest';
   ```

### Problem: Email Queue Growing Rapidly

**Symptoms:**
- Pending email count keeps increasing
- Queue worker can't keep up
- Performance degradation

**Root Cause:** High email volume or slow SMTP

**Diagnosis:**
```bash
# Count pending emails
SELECT COUNT(*) FROM email_outbox WHERE status = 'PENDING';

# Check queue growth rate
SELECT COUNT(*) FROM email_outbox 
WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) 
  AND status = 'PENDING';
```

**Solution:**
1. **Increase queue workers:**
   ```bash
   php artisan queue:work --queue=default &
   php artisan queue:work --queue=default &
   php artisan queue:work --queue=default &
   ```

2. **Use Supervisor:**
   ```ini
   [program:laravel-queue]
   numprocs=4  # Run 4 workers
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/artisan queue:work
   ```

3. **Optimize SMTP:**
   - Check SMTP provider limits
   - Consider batch sending
   - Increase timeout values

4. **Archive old emails:**
   ```bash
   php artisan email:archive --older-than=30-days
   ```

---

## Resend Email Patterns

### Pattern 1: Resend All Failed Emails for a Guest

**Scenario:** Guest says they didn't receive email

**Steps:**
1. Find booking: `Search > Bookings > Find guest`
2. View all payments for booking
3. For each receipt:
   - Click **Email History**
   - Find FAILED emails
   - Click **Resend** on each

**Bulk Action (CLI):**
```php
// In tinker
$booking = Booking::find(123);
$emailService = new EmailService();

foreach ($booking->receipts as $receipt) {
    $email = EmailOutbox::where('receipt_id', $receipt->id)
        ->where('status', 'FAILED')
        ->first();
    
    if ($email && $email->canRetry()) {
        $emailService->resendReceiptEmail($email);
    }
}
```

### Pattern 2: Retry All Failed Emails Today

**Scenario:** Email service was down, now it's back online

**Steps:**
1. Go to **Admin > Payment > Email Statistics**
2. Note "failed_retryable" count
3. Run:
   ```bash
   php artisan email:retry-failed --created-today
   ```

**Or manually:**
1. Filter: Email History → Status: FAILED, Created: Today
2. Bulk action: **Resend All**

### Pattern 3: Custom Email to Guest (Not Receipt)

**Scenario:** Need to send custom message to guest about payment

**Method:**
1. Go to guest profile
2. Click **Send Email**
3. Choose template or write custom
4. Email is queued like receipt emails

---

## Email Configuration

### SMTP Providers

#### Option 1: Mailtrap (Development)

Best for testing. Free tier available.

**.env configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username_here
MAIL_PASSWORD=your_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@estatemanagement.com"
MAIL_FROM_NAME="Estate Management"
```

#### Option 2: SendGrid (Production)

Reliable, scalable, good tracking.

**.env configuration:**
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your_api_key_here
MAIL_FROM_ADDRESS="noreply@estatemanagement.com"
MAIL_FROM_NAME="Estate Management"
```

#### Option 3: AWS SES (Production)

Cost-effective for high volume.

**.env configuration:**
```env
MAIL_MAILER=ses
AWS_REGION=us-east-1
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
MAIL_FROM_ADDRESS="noreply@estatemanagement.com"
MAIL_FROM_NAME="Estate Management"
```

### Queue Configuration

#### Option 1: Database Queue (Recommended)

Simple, no additional setup needed.

**.env configuration:**
```env
QUEUE_CONNECTION=database
```

Run migration:
```bash
php artisan queue:table
php artisan migrate
```

#### Option 2: Redis Queue (High Volume)

Fast, requires Redis server.

**.env configuration:**
```env
QUEUE_CONNECTION=redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Option 3: Synchronous Queue (Development)

Sends immediately, no queuing.

**.env configuration:**
```env
QUEUE_CONNECTION=sync
```

---

## Monitoring & Alerts

### Set Up Email Alerts

**When to Alert:**
1. Failed email count > 10
2. Pending emails > 100
3. Queue worker not responding
4. SMTP authentication failure

**Implementation (Cron Job):**
```php
// app/Console/Commands/CheckEmailHealth.php
artisan schedule:call('email:health-check', [
    '--alert-failed' => 10,
    '--alert-pending' => 100
]);
```

**In scheduler (app/Console/Kernel.php):**
```php
$schedule->command('email:health-check')
    ->everyTenMinutes()
    ->sendOutputTo(storage_path('logs/email-health.log'));
```

### Monitor Queue Growth

**Daily Check:**
```bash
# Count pending emails
php artisan tinker
> EmailOutbox::pending()->count()
5 # Should be small (< 10)

# Check sent count
> EmailOutbox::sent()->count()
142 # Should be increasing

# Check failed retryable
> EmailOutbox::failed()->where('retry_count', '<', 3)->count()
2 # Should be low
```

---

## Performance Optimization

### Optimize Email Queue

**Current Performance:**
- Can send ~100 emails/minute per worker
- Database queue suitable for < 1000/day
- Redis queue recommended for > 5000/day

**Optimization Tips:**

1. **Use Batching:**
   ```php
   Mail::batch($mailers)
       ->dispatch();
   ```

2. **Set Job Timeout:**
   ```php
   $job->timeout = 30; // seconds
   ```

3. **Use Connection Pooling:**
   - Configure SMTP connection pooling
   - Reduces reconnect overhead

4. **Archive Old Records:**
   ```bash
   php artisan email:archive --older-than=30-days
   ```

### Database Optimization

**Add Missing Indexes (if not created):**
```sql
ALTER TABLE email_outbox ADD INDEX idx_status_created 
  (status, created_at);

ALTER TABLE email_outbox ADD INDEX idx_receipt_id 
  (receipt_id);
```

**Partition Large Tables:**
```sql
-- For tables with > 1M records
ALTER TABLE email_outbox 
PARTITION BY RANGE(YEAR(created_at)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p2026 VALUES LESS THAN (2027)
);
```

---

## Regular Maintenance

### Daily Tasks
- [ ] Check email statistics
- [ ] Review failed emails
- [ ] Verify queue worker running
- [ ] Check logs for errors

### Weekly Tasks
- [ ] Review email delivery rate
- [ ] Resend retryable emails
- [ ] Check SMTP provider limits
- [ ] Verify email configuration

### Monthly Tasks
- [ ] Analyze email patterns
- [ ] Review template effectiveness
- [ ] Archive old email records
- [ ] Update SMTP credentials if needed
- [ ] Review and update documentation

### Quarterly Tasks
- [ ] Review email service reliability
- [ ] Plan capacity upgrades
- [ ] Test disaster recovery
- [ ] Review alternative providers
- [ ] Update email templates

---

## API Endpoints Quick Reference

### For Administrators

#### Get Email Statistics
```bash
curl -X GET "https://api.estatemanagement.com/admin/payment/emails/statistics" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

#### Resend Email
```bash
curl -X POST "https://api.estatemanagement.com/admin/payment/emails/5/resend" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"
```

#### Get Email History
```bash
curl -X GET "https://api.estatemanagement.com/admin/payment/receipts/1/email-history" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## FAQ

**Q: How long does it take for email to arrive?**
A: Usually 1-5 minutes after payment. Depends on queue worker and SMTP provider.

**Q: Can guests resend their receipt email?**
A: Currently admin-only. Can add self-service feature if needed.

**Q: What if email is too large?**
A: System automatically truncates body. Consider disabling attachments if size is issue.

**Q: How long are emails kept in database?**
A: By default, indefinitely. Archive after 30 days for performance.

**Q: Can I customize the email template?**
A: Yes! Edit `resources/views/emails/receipt-notification.blade.php`

**Q: What happens if SMTP fails permanently?**
A: Email stays FAILED. Admin can resend up to 3 times.

**Q: Can I send test email?**
A: Yes, use Artisan command: `php artisan email:send-test --to=test@example.com`

---

## Support & Escalation

### Troubleshooting Checklist

- [ ] Verify queue worker is running
- [ ] Check SMTP configuration in .env
- [ ] Review email_outbox table for records
- [ ] Check laravel.log for errors
- [ ] Verify database connection
- [ ] Test SMTP credentials manually
- [ ] Check email provider status page
- [ ] Review recent configuration changes

### Who to Contact

- **Email Delivery Issues:** DevOps team
- **Email Template Issues:** Frontend team
- **Database/Queue Issues:** Database team
- **SMTP Provider Issues:** Support of provider
- **General Issues:** System administrator

---

## Summary

✅ **Admin Capabilities:**
- Monitor email delivery statistics
- View email history for receipts
- Resend failed emails (3 retries)
- Troubleshoot delivery issues
- Optimize email configuration
- Set up monitoring and alerts
- Archive old records

**Key Numbers:**
- Max retries: 3
- Queue workers: 1+ (configurable)
- Processing speed: ~100 emails/min per worker
- Data retention: Indefinite (archive recommended)
- SLA: <5 min delivery time

**Status:** ✅ Production Ready
