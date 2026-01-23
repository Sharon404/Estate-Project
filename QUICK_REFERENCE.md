# Quick Reference - Frontend to Payment Flow

## URLs
| Page | URL | Purpose |
|------|-----|---------|
| Reservation Form | `/reservation` | Guest enters booking details |
| Payment Page | `/payment/booking/{id}` | Guest pays via M-PESA |
| Admin Dashboard | `/admin/payment/verification-dashboard` | Admin verifies manual payments |

## API Endpoints
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/booking/submit` | Submit reservation form |
| POST | `/payment/intents` | Create payment intent |
| POST | `/payment/mpesa/stk` | Send STK push |
| POST | `/payment/manual-entry` | Submit manual receipt |
| POST | `/admin/payment/manual-submissions/{id}/verify` | Admin verify |
| POST | `/admin/payment/manual-submissions/{id}/reject` | Admin reject |

## Database Tables
| Table | Purpose |
|-------|---------|
| `guests` | Guest information |
| `bookings` | Booking details & status |
| `properties` | Room/property catalog |
| `payment_intents` | Payment tracking |
| `mpesa_manual_submissions` | Manual payment receipts |
| `booking_transactions` | Completed payments |
| `receipts` | Generated PDFs |
| `audit_logs` | Action history |

## Files Modified
| File | Change |
|------|--------|
| routes/web.php | Added booking submission route |
| FrontendController.php | Added reservation() method |
| reservation.blade.php | Fixed form action & prices |
| layouts/app.blade.php | Added CSRF token |
| validation-reservation.js | Complete rewrite for AJAX |
| BookingSubmissionController.php | NEW - Handles form submission |

## Form Fields
- **name** (required)
- **email** (required, valid format)
- **phone** (required)
- **checkin** (required, date)
- **checkout** (required, date, after checkin)
- **adult** (required, integer)
- **children** (required, integer)
- **room_count** (required, integer)
- **room_type** (required, string)
- **message** (optional)

## Booking Status
| Status | Meaning |
|--------|---------|
| PENDING_PAYMENT | Created, waiting for payment |
| PARTIALLY_PAID | Some payment received |
| PAID | Fully paid |
| CANCELLED | Guest cancelled |
| EXPIRED | Booking expired |

## Error Codes
| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created (booking created) |
| 400 | Bad request (validation failed) |
| 404 | Not found |
| 405 | Method not allowed |
| 419 | CSRF token expired |
| 422 | Validation failed |
| 500 | Server error |

## Quick Test
```bash
# 1. Start server
php artisan serve

# 2. Visit
http://localhost:8000/reservation

# 3. Fill form & submit
# (should redirect to payment page)

# 4. Check database
php artisan tinker
Booking::latest()->first();

# 5. Check logs
tail -f storage/logs/laravel.log
```

## Troubleshooting
| Problem | Check |
|---------|-------|
| Form stuck | Browser console (F12) for errors |
| 404 error | `php artisan route:list \| grep booking` |
| 500 error | `tail -f storage/logs/laravel.log` |
| CSRF error | Refresh page, check meta tag in head |
| No redirect | Check JavaScript console errors |

## Key Features
✅ Form validation (frontend & backend)
✅ AJAX submission (no page reload)
✅ Automatic redirect to payment
✅ Error handling & messages
✅ CSRF protection
✅ Guest creation (auto)
✅ Booking creation (auto)
✅ Database persistence
✅ KES pricing throughout
✅ No more stuck "Sending" state

## Prices (KES)
| Room Type | Price/Night |
|-----------|------------|
| Standard | 11,900 |
| Deluxe | 12,900 |
| Premier | 13,900 |
| Family Suite | 14,900 |
| Luxury Suite | 17,900 |
| Presidential | 19,900 |

## Payment Methods
1. **STK Push** (automatic M-PESA prompt)
2. **Manual Entry** (guest enters till & receipt)

## Admin Actions
- View pending submissions
- Verify payment (approve)
- Reject payment (with reason)
- View submission details
- See statistics

## Environment Vars
```
MPESA_TILL_NUMBER=*138#
MPESA_COMPANY_NAME=Nairobi Homes
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## Deployment
```bash
git add .
git commit -m "Fix booking flow"
git push origin main
# On server:
git pull origin main
php artisan config:clear
```

## Success = 
✅ Form submits  
✅ Booking created  
✅ Redirected to payment  
✅ Payment page shows  
✅ No errors in logs
