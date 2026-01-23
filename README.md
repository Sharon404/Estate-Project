# Estate Project - Booking System

A complete, enterprise-grade hotel booking system with M-PESA payment integration built with Laravel 12.

**Status**: ✅ Production Ready

## Quick Start

### Core Flow
```
1. Guest submits booking form (POST /bookings)
2. System generates confirmation with booking reference
3. Guest confirms and locks booking for payment
4. M-PESA STK is sent to customer
5. Payment verified via callback (or manual fallback)
6. Receipt generated and emailed
```

## Project Structure

```
app/
├── Services/
│   ├── BookingService.php              # Orchestrates entire booking workflow
│   ├── PaymentService.php              # Manages payment processing
│   ├── MpesaStkService.php             # Sends STK push
│   ├── MpesaCallbackService.php        # Handles M-PESA callbacks
│   ├── ReceiptService.php              # Generates receipts
│   ├── AuditService.php                # Logs all actions
│   └── EmailService.php                # Sends emails
│
├── Http/Controllers/
│   ├── Booking/
│   │   └── BookingController.php       # store(), summary(), confirm()
│   ├── Payment/
│   │   ├── PaymentController.php       # Payment intents & manual entries
│   │   └── MpesaController.php         # STK & callbacks
│   └── Admin/
│       └── AdminPaymentController.php  # Payment verification
│
├── Http/Requests/
│   ├── StoreBookingRequest.php
│   ├── ConfirmBookingRequest.php
│   ├── InitiateStkRequest.php
│   └── SubmitManualMpesaRequest.php
│
└── Models/
    ├── Booking.php
    ├── Guest.php
    ├── PaymentIntent.php
    ├── BookingTransaction.php           # Payment ledger (immutable)
    ├── Receipt.php
    └── AuditLog.php
```

## Database Schema

### bookings
- id, booking_ref (BK202601251A3K9), property_id, guest_id
- status (DRAFT → PENDING_PAYMENT → PAID)
- check_in, check_out, nights, adults, children
- total_amount, accommodation_subtotal, addons_subtotal
- amount_paid, amount_due, nightly_rate

### payment_intents
- id, booking_id, intent_ref
- method (MPESA_STK, MPESA_MANUAL)
- amount, currency
- status (INITIATED → PENDING → SUCCEEDED/FAILED)

### booking_transactions (LEDGER)
- id, booking_id, payment_intent_id
- type (CREDIT/DEBIT), amount, reference
- description, meta (JSON)
- **IMMUTABLE**: Single source of truth for all payments

### receipts
- id, booking_id, receipt_number (RCP-BK{booking_ref})
- amount_paid, issued_at

### audit_logs
- action, description, booking_id, guest_id, user_id
- ip_address, user_agent, meta (JSON), timestamp

## API Endpoints

### Booking Operations
```
POST   /bookings                        # Create reservation (DRAFT)
GET    /bookings/{id}/summary           # Get confirmation (booking_ref generated)
PATCH  /bookings/{id}/confirm           # Confirm & lock (PENDING_PAYMENT)
```

### Payment Operations
```
POST   /payment/intents                 # Create payment intent
GET    /payment/intents/{id}            # Get intent status
POST   /payment/mpesa/stk               # Send STK push
GET    /payment/mpesa/stk/{id}/status   # Check STK status
POST   /payment/mpesa/callback          # M-PESA callback
POST   /payment/manual-entry            # Submit manual receipt
GET    /payment/receipts/{number}       # Get receipt
```

### Admin Operations (Auth Required)
```
GET    /admin/payment/verification-dashboard   # Dashboard
GET    /admin/payment/manual-submissions/pending
POST   /admin/payment/manual-submissions/{id}/verify
POST   /admin/payment/manual-submissions/{id}/reject
```

## Key Features

### ✅ Ledger-Based Payments
- `BookingTransaction` is immutable source of truth
- All amounts derived from ledger
- Zero double-charging protection

### ✅ Status Machine
```
DRAFT → PENDING_PAYMENT → PAID
```

### ✅ Payment Paths
1. **STK Success**: Callback → Ledger → PAID
2. **STK Failure**: Manual → Admin Review → Ledger → PAID

### ✅ Transactional Safety
- All state changes wrapped in `DB::transaction()`
- All-or-nothing semantics
- Idempotency checks on ledger creation

### ✅ Validation
- Property exists
- Dates valid (future check-in, checkout after check-in)
- Phone in E.164 format
- Amount matches calculation

### ✅ Audit Trail
- Every action logged
- IP address & user agent captured
- Payment metadata preserved

## Implementation Details

### Booking Creation Flow
1. **store()**: Creates guest, calculates amounts, creates DRAFT booking
2. **summary()**: Generates booking_ref (BK+YYYYMMDD+5-char random), persists it
3. **confirm()**: Validates DRAFT, moves to PENDING_PAYMENT

### Payment Flow (STK Success)
1. **initiateStk()**: Creates PaymentIntent (INITIATED)
2. **M-PESA Callback**: Receives payment confirmation
3. **callback()**: Creates BookingTransaction, marks booking PAID
4. **ReceiptService**: Generates receipt, sends email

### Payment Flow (STK Failure → Manual)
1. **submitManualPayment()**: Creates MpesaManualSubmission (PENDING_REVIEW)
2. **Admin Dashboard**: Reviews submission
3. **verifySubmission()**: Creates BookingTransaction, marks PAID
4. **ReceiptService**: Generates receipt, sends email

## Testing

### Manual Test (cURL)
```bash
# 1. Create booking
curl -X POST http://localhost:8000/api/bookings \
  -H "Content-Type: application/json" \
  -d '{...}'

# 2. Get confirmation
curl http://localhost:8000/api/bookings/{id}/summary

# 3. Confirm booking
curl -X PATCH http://localhost:8000/api/bookings/{id}/confirm

# 4. Initiate STK
curl -X POST http://localhost:8000/api/payment/mpesa/stk

# 5. Simulate callback
curl -X POST http://localhost:8000/api/payment/mpesa/callback
```

### Automated Test
```bash
bash test_booking_flow.sh
```

## Database Access

### Adminer (Web UI)
```
URL: http://localhost:8081
Server: db
Database: holiday_rentals
User: root
Password: root
```

### Command Line
```bash
docker exec laravel_mysql mysql -u root -proot holiday_rentals
```

## Common Patterns

### Creating a Booking in Service
```php
$booking = BookingService::createReservation([
    'property_id' => 1,
    'check_in' => '2026-01-25',
    'check_out' => '2026-01-28',
    'adults' => 2,
    'children' => 0,
    'guest' => [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone_e164' => '+254701123456',
    ]
]);
```

### Recording Payment
```php
$booking = Booking::find($bookingId);
BookingService::markAsPaid($booking, $paymentIntent, $amount, $reference);
```

### Generating Receipt
```php
$receipt = ReceiptService::generateReceipt(
    $booking,
    $paymentIntent,
    $amount,
    $reference
);
```

## Error Handling

All endpoints return standard error responses:

```json
{
  "success": false,
  "message": "Error description",
  "code": "ERROR_CODE"
}
```

Common error codes:
- `BOOKING_NOT_FOUND`
- `BOOKING_INVALID_STATE`
- `INVALID_AMOUNT`
- `PAYMENT_INTENT_NOT_FOUND`
- `UNAUTHORIZED_ADMIN`

## Validation Rules

### Booking Submission
- property_id: Required, must exist
- check_in: Required, date, must be future
- check_out: Required, date, must be after check-in
- adults: Required, integer, minimum 1
- children: Required, integer, minimum 0
- guest.full_name: Required, string
- guest.email: Required, email, unique
- guest.phone_e164: Required, E.164 format

### Payment Submission
- booking_id: Required, must exist, must be PENDING_PAYMENT
- amount: Required, must match booking total
- phone_e164: Required, E.164 format
- receipt/checkout_request_id: Required based on method

## Deployment Checklist

- [ ] Configure M-PESA credentials in .env
- [ ] Set up email service (SMTP/SES/Mailgun)
- [ ] Create admin user account
- [ ] Test with M-PESA sandbox
- [ ] Enable HTTPS on production
- [ ] Configure M-PESA callback IP allowlist
- [ ] Set up monitoring for payment failures
- [ ] Configure backup strategy for audit_logs

## Support

### Check Status
- **Bookings**: `SELECT * FROM bookings WHERE id = ?`
- **Payments**: `SELECT * FROM booking_transactions WHERE booking_id = ?`
- **Receipts**: `SELECT * FROM receipts WHERE booking_id = ?`
- **Audit Trail**: `SELECT * FROM audit_logs WHERE booking_id = ? ORDER BY timestamp DESC`

### Debug Logs
```bash
tail -f storage/logs/laravel.log
```

### Manual Payment Verification
```sql
SELECT * FROM mpesa_manual_submissions WHERE status = 'PENDING_REVIEW';
```

## Architecture Decisions

1. **Service Layer**: Business logic isolated in services for testability
2. **Ledger Pattern**: BookingTransaction is immutable, single source of truth
3. **Status Machine**: Explicit state transitions prevent invalid operations
4. **Form Requests**: Validation in form requests prevents invalid data
5. **Audit Service**: Complete action history enables compliance & debugging
6. **Transactional Boundaries**: DB::transaction() ensures consistency
7. **Idempotency**: Duplicate checks prevent double-charging

## File Reference

| File | Purpose |
|------|---------|
| BookingService.php | High-level workflow orchestration |
| PaymentService.php | Payment processing logic |
| ReceiptService.php | Receipt generation & retrieval |
| AuditService.php | Action logging |
| BookingController.php | Booking endpoints |
| PaymentController.php | Payment intent & manual entry |
| MpesaController.php | M-PESA integration |
| AdminPaymentController.php | Admin verification endpoints |

**Last Updated**: January 23, 2026
   php artisan migrate
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Configuration

### Database
- **Driver**: MySQL
- **Connection**: Configured in `.env` (localhost, port 3306)
- **Database Name**: estate_project

### Queues
- **Driver**: Database
- **Job Table**: `jobs` (created during migration)
- **Processing**: Run `php artisan queue:work`

### Email
- **Mailer**: SMTP
- **Default Provider**: Mailtrap (for development)
- **Configuration**: Environment-based (`.env` file)

## Project Structure

```
docs/
├── CHANGELOG.md          # Project changelog
└── SETUP.md             # Detailed setup documentation
```

For additional configuration details, see [SETUP.md](docs/SETUP.md).

## Development

### Build Frontend Assets
```bash
npm run dev
```

### Run Tests
```bash
php artisan test
```

### Process Queue Jobs
```bash
php artisan queue:work
```

### Tinker Shell
```bash
php artisan tinker
```

## Documentation

- [Setup Guide](docs/SETUP.md) - Detailed configuration instructions
- [Changelog](docs/CHANGELOG.md) - Project version history
- [Laravel Documentation](https://laravel.com/docs/12) - Official Laravel docs

## Security Notes

- Never commit `.env` file to version control
- Keep database credentials and SMTP passwords in `.env` only
- Review and update security headers in production
- Run `php artisan config:cache` in production

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Estate-Project
Real estate project
>>>>>>> 7617695f773c84b1d1c571e69dc77cc2ef93756e
