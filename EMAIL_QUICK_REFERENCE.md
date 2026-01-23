# Email Notification System - Quick Reference

## 🚀 Quick Start

### Installation (5 minutes)

```bash
# 1. Run migration
php artisan migrate

# 2. Configure .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
QUEUE_CONNECTION=database

# 3. Start queue worker
php artisan queue:work

# ✅ Done! Emails now queue automatically on receipt creation
```

---

## 📧 Email Flow

```
Payment Success
    ↓
Receipt Created
    ↓
EmailService::queueReceiptNotification()
    ├─ Create EmailOutbox record
    ├─ Render email template
    └─ Queue with Mail::queue()
    ↓
Queue Worker
    ├─ Pick up email job
    ├─ Send via SMTP
    └─ Update status (SENT or FAILED)
    ↓
Guest Receives Email ✓
```

---

## 🔧 Key Components

### EmailService (Main Engine)
```php
// Queue email on receipt
$emailService = new EmailService();
$emailService->queueReceiptNotification($receipt);

// Admin resend
$emailService->resendReceiptEmail($emailOutbox);

// Get statistics
$stats = $emailService->getEmailStatistics();
```

### EmailOutbox Model (Database)
```php
// Find emails
EmailOutbox::pending()->get();      // Waiting to send
EmailOutbox::sent()->get();         // Successfully sent
EmailOutbox::failed()->get();       // Failed emails
EmailOutbox::forReceipt($id)->get(); // For specific receipt

// Check status
$email->canRetry();      // Can try again?
$email->markAsSent();    // Mark as delivered
$email->markAsFailed($error); // Mark as failed
```

### Email Template (Blade)
**File:** `resources/views/emails/receipt-notification.blade.php`

Sends:
- Booking reference
- Receipt number
- Payment amount
- Issue date
- Check-in/out dates
- Amount breakdown

---

## 🎯 Admin Tasks

### View Email Statistics
```
GET /admin/payment/emails/statistics
```
**Shows:** pending, sent, failed, total, sent_today, failed_retryable

### View Email History for Receipt
```
GET /admin/payment/receipts/{id}/email-history
```
**Shows:** All email attempts for receipt

### Resend Failed Email
```
POST /admin/payment/emails/{id}/resend
```
**Does:** Queue email again (max 3 retries)

---

## 📊 Database Schema (Quick)

### email_outbox Table
| Column | Type | Purpose |
|--------|------|---------|
| id | INT | Primary key |
| recipient_email | VARCHAR | Guest email |
| subject | VARCHAR | Email subject |
| body | LONGTEXT | Email HTML |
| metadata | JSON | Receipt details |
| receipt_id | INT FK | Link to receipt |
| status | ENUM | PENDING/SENT/FAILED |
| retry_count | INT | Number of retries |
| max_retries | INT | Max allowed (3) |
| sent_at | TIMESTAMP | When sent |
| last_retry_at | TIMESTAMP | Last attempt |
| created_at | TIMESTAMP | When queued |

---

## 🐛 Troubleshooting

### Emails Not Sending?

**Check 1:** Queue worker running?
```bash
ps aux | grep "queue:work"
# If not: php artisan queue:work
```

**Check 2:** Email in database?
```bash
php artisan tinker
> EmailOutbox::count()
```

**Check 3:** SMTP configured?
```env
MAIL_MAILER=smtp
MAIL_HOST=xxx
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
```

### Email Stuck in PENDING?

**Solution:** Start queue worker
```bash
php artisan queue:work --queue=default
```

### Email Failed - How to Retry?

**Via Admin Panel:**
1. View receipt → Email History
2. Click "Resend" on failed email
3. Email requeued

**Via CLI:**
```bash
php artisan email:retry-failed
```

---

## ⚙️ Configuration

### .env Variables
```env
# Email Service
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@domain.com"

# Queue
QUEUE_CONNECTION=database
```

### Queue Drivers
- **database** (recommended for small volume)
- **redis** (better for high volume)
- **sync** (instant, no queuing)

---

## 📈 Performance

### Email Throughput
- **Per worker:** ~100 emails/minute
- **Multiple workers:** Scale linearly
- **Database queue:** Fine for <1000/day
- **Redis queue:** Better for >5000/day

### Optimization Tips
1. Run multiple queue workers
2. Use Redis for high volume
3. Archive old emails monthly
4. Add database indexes (already done)

---

## 🧪 Quick Testing

### Test Email Queueing
```php
php artisan tinker

// Create receipt
$receipt = Receipt::factory()->create();

// Queue email
$emailService = new EmailService();
$email = $emailService->queueReceiptNotification($receipt);

// Check it's queued
$email->status // PENDING
```

### Test Queue Processing
```bash
# Terminal 1
php artisan queue:work --verbose

# Terminal 2 (in tinker)
$receipt = Receipt::factory()->create();
$emailService = new EmailService();
$emailService->queueReceiptNotification($receipt);

# Watch Terminal 1 - should process it
```

### Test Admin Endpoints
```bash
# Statistics
curl http://localhost:8000/admin/payment/emails/statistics

# Email History
curl http://localhost:8000/admin/payment/receipts/1/email-history

# Resend
curl -X POST http://localhost:8000/admin/payment/emails/1/resend
```

---

## 📋 Monitoring Checklist

### Daily
- [ ] Queue worker running: `ps aux | grep queue:work`
- [ ] No pending backlog: `EmailOutbox::pending()->count()`
- [ ] Sent today > 0: `EmailOutbox::sent()->whereDatetoday()->count()`
- [ ] Check logs: `tail -f storage/logs/laravel.log`

### Weekly
- [ ] Email delivery rate: `EmailOutbox::getEmailStatistics()`
- [ ] Failed emails: `EmailOutbox::failed()->count()`
- [ ] Retryable count: `EmailOutbox::failed()->where('retry_count', '<', 3)->count()`
- [ ] Queue size: `DB::table('jobs')->count()`

### Monthly
- [ ] Archive old records: `php artisan email:archive --older-than=30-days`
- [ ] Review performance: Check sent/failed rate
- [ ] Test disaster recovery
- [ ] Update SMTP credentials if needed

---

## 🎯 Email Content

### What's In the Email?

✅ **Receipt Confirmation**
- "✓ Payment Confirmed"
- Receipt number (e.g., RCP-2026-00001)

✅ **Booking Details**
- Booking reference
- Check-in date
- Check-out date
- Number of nights

✅ **Payment Details**
- Amount paid (KES 5,000.00)
- Total booking amount
- Remaining balance

✅ **Call to Action**
- "View Full Receipt" button
- Support contact link

### Email Status Meanings

| Status | Meaning | Action |
|--------|---------|--------|
| PENDING | Queued, waiting to send | Monitor queue |
| SENT | Successfully delivered | None |
| FAILED | Failed to send | Can retry (max 3x) |

---

## 🔐 Security Notes

- ✅ No sensitive data in subject
- ✅ Payment details only in body
- ✅ Guest email verified before sending
- ✅ Admin-only access to resend
- ✅ Audit trail in database
- ✅ SMTP credentials in .env (not in code)

---

## 📞 Common Tasks

### "Guest didn't receive email"

1. Check email history:
```bash
curl "http://localhost:8000/admin/payment/receipts/{id}/email-history"
```

2. View status:
   - SENT = Delivered
   - FAILED = Try resend
   - PENDING = Wait for queue worker

3. Resend if failed:
```bash
curl -X POST "http://localhost:8000/admin/payment/emails/{id}/resend"
```

### "Email queue is growing"

1. Check queue worker:
```bash
ps aux | grep "queue:work"
```

2. Count pending:
```bash
php artisan tinker
> EmailOutbox::pending()->count()
```

3. Start more workers:
```bash
php artisan queue:work &
php artisan queue:work &
php artisan queue:work &
```

### "Email content is wrong"

1. Check template: `resources/views/emails/receipt-notification.blade.php`
2. Edit and save
3. Clear cache: `php artisan cache:clear`
4. Resend previous emails

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| EMAIL_NOTIFICATION_DOCUMENTATION.md | Complete reference |
| EMAIL_ADMIN_GUIDE.md | Admin tasks |
| EMAIL_TEST_EXAMPLES.md | Test cases (29+) |
| EMAIL_IMPLEMENTATION_CHECKLIST.md | Setup steps |
| SYSTEM_INDEX.md | Overall system map |

---

## 🆘 Emergency Procedures

### Queue Worker Crashed

```bash
# Kill any stuck processes
pkill -f "queue:work"

# Start fresh
php artisan queue:work --queue=default
```

### High Email Failure Rate

```bash
# 1. Check SMTP config
php artisan tinker
> config('mail')

# 2. Test SMTP
> Mail::raw('Test', function($m) { $m->to('test@example.com'); })

# 3. Check logs
tail -f storage/logs/laravel.log

# 4. Review failed emails
> EmailOutbox::failed()->count()

# 5. Temporarily pause new sends
# Set QUEUE_CONNECTION=sync in .env
# Clear cache: php artisan config:clear
# Investigate issue
# Revert to QUEUE_CONNECTION=database
```

### Database Running Out of Space

```bash
# Archive old emails
php artisan email:archive --older-than=30-days

# Count remaining
php artisan tinker
> EmailOutbox::count()
```

---

## ✨ Key Features at a Glance

✅ **Automatic** - Queues on receipt creation  
✅ **Reliable** - Database-backed queue  
✅ **Traceable** - All emails logged  
✅ **Retryable** - Admin can resend (3x)  
✅ **Monitored** - Statistics endpoint  
✅ **Professional** - Beautiful HTML template  
✅ **Scalable** - Multiple workers supported  
✅ **Secure** - Admin-only resend  

---

## 📊 System at a Glance

```
Email System Overview:
├─ Database: email_outbox table
├─ Model: EmailOutbox with 6 scopes
├─ Service: EmailService (9 methods)
├─ Mailable: ReceiptNotificationMail
├─ Template: Blade email template
├─ Admin Endpoints: 3 endpoints
├─ Queue: Laravel queue system
├─ Retry Logic: Max 3 attempts
└─ Status: ✅ Production Ready
```

---

## 🚀 Getting Started (30 seconds)

```bash
# 1. Migration
php artisan migrate

# 2. Configure .env
# Add: MAIL_MAILER, MAIL_HOST, QUEUE_CONNECTION

# 3. Start queue
php artisan queue:work

# ✅ Emails now work!
```

---

**Status:** ✅ Production Ready  
**Last Updated:** January 23, 2026  
**Version:** 1.0.0
