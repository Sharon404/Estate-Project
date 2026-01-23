# Quick Testing Guide - Frontend to Payment Flow

## Prerequisites
- Laravel dev server running: `php artisan serve`
- Database migrated: `php artisan migrate`
- At least one property exists in database

## Step-by-Step Test

### 1. Start Fresh
```bash
# Terminal 1 - Start Laravel server
cd ~/Desktop/Estate\ Project
php artisan serve

# Terminal 2 - Monitor logs (optional but helpful)
tail -f storage/logs/laravel.log
```

### 2. Visit Reservation Page
- Open browser: `http://localhost:8000/reservation`
- Should see form with:
  - Date picker
  - Guest count (Adult/Children/Rooms)
  - Room selection dropdown (prices in KES)
  - Name, Email, Phone inputs
  - Message textarea
  - Submit button

### 3. Fill Out Form
- **Date Picker:** Click and select check-in and check-out dates
  - Example: Jan 25, 2026 → Jan 26, 2026
- **Adults:** Keep as 1 or change
- **Children:** Keep as 0 or change
- **Rooms:** Keep as 1 or change
- **Room Type:** Select one from dropdown (all show KES prices)
- **Name:** Enter any name
- **Email:** Enter valid email
- **Phone:** Enter phone number
- **Message:** Leave blank or add optional message

### 4. Submit Form
- Click **"Submit Form"** button
- Button should change to **"Sending..."**
- Wait 2-3 seconds

### What Should Happen Next (Check Each)

✅ **Form Success Message**
- See: "Your reservation has been sent successfully"
- Form disappears
- Message displays for ~2 seconds

✅ **Automatic Redirect**
- Page redirects to: `/payment/booking/123` (number varies)
- Should see payment page with:
  - "Complete Your Payment" header
  - Booking details (property, dates, amount)
  - Amount shown in KES
  - Phone input field
  - "Pay Now" button

### 5. Check Backend Logs (Terminal 2)
Look for messages like:
```
[2026-01-23 XX:XX:XX] local.INFO: Booking created: BK-XXXX
[2026-01-23 XX:XX:XX] local.INFO: Guest created: email@test.com
```

### 6. Check Database
```bash
# Open another terminal
php artisan tinker

# Inside tinker:
Booking::latest()->first();  // Should show your booking

# Or via MySQL:
mysql> SELECT * FROM bookings ORDER BY id DESC LIMIT 1;
```

---

## If Something Goes Wrong

### Problem: Form stuck at "Sending"

**Check Console (F12 → Console tab):**
- Look for red error messages
- Note the error text

**Common Issues:**

1. **CSRF Token Error**
   - Message: "419 Page Expired" or "CSRF token mismatch"
   - Fix: Hard refresh page (Ctrl+Shift+R)
   - Verify: `<meta name="csrf-token">` is in page source

2. **405 Method Not Allowed**
   - Message: "POST /booking/submit 405"
   - Fix: Check routes file:
     ```bash
     php artisan route:list | grep booking.submit
     # Should show: POST /booking/submit
     ```

3. **404 Not Found**
   - Message: "POST /booking/submit 404"
   - Fix: 
     ```bash
     php artisan route:list
     # Verify the route exists
     # If not, restart Laravel: Ctrl+C and run again
     ```

4. **500 Server Error**
   - Message: "POST /booking/submit 500"
   - Fix: Check logs:
     ```bash
     tail -f storage/logs/laravel.log
     # Look for error details
     ```

### Problem: Form shows blank page after "Sending"

**Check Laravel Logs:**
```bash
tail -f storage/logs/laravel.log
```

**Common Issues:**

1. **Guest Model not found**
   - Error: "Class 'App\Models\Guest' not found"
   - Fix: Make sure guest migration exists and ran

2. **Booking Model not found**
   - Error: "Class 'App\Models\Booking' not found"
   - Fix: Check `app/Models/Booking.php` exists

3. **Property table empty**
   - Error: "No properties available"
   - Fix: Create at least one property:
     ```bash
     php artisan tinker
     App\Models\Property::create(['name' => 'Test Property', ...]);
     ```

### Problem: Prices showing in USD instead of KES

- Check file: `resources/views/frontend/reservation.blade.php`
- Look for prices: Should say "KES 10,900" not "$109"
- If still showing USD: Hard refresh page

### Problem: Can't see payment page

**Check Route:**
```bash
php artisan route:list | grep payment.show
# Should show: GET /payment/booking/{booking}
```

**Check Controller:**
```bash
# File should exist:
ls app/Http/Controllers/Payment/PaymentController.php

# Method should exist:
grep -n "showPaymentPage" app/Http/Controllers/Payment/PaymentController.php
```

---

## Success Indicators

### Visual Indicators
✅ Form has KES prices
✅ Form submits without error
✅ See success message
✅ Redirected to payment page
✅ Payment page shows KES amounts
✅ No error messages

### Database Indicators
✅ New guest created in `guests` table
✅ New booking created in `bookings` table
✅ Booking has `PENDING_PAYMENT` status
✅ Booking has correct dates and amounts

### Log Indicators
✅ No error messages in `storage/logs/laravel.log`
✅ See booking creation log
✅ See guest creation log

---

## Payment Page Testing (Optional)

After successful booking creation:

1. **STK Push Test**
   - On payment page, enter phone: 0712345678
   - Click "Pay Now"
   - Should show "Sending M-PESA prompt to your phone..."
   - (Will time out in test environment)

2. **Manual Fallback Test**
   - After STK times out
   - See till number: *138#
   - See 6 payment steps
   - Can see receipt form

---

## Troubleshooting Checklist

- [ ] Laravel running: `php artisan serve`
- [ ] Database migrated: `php artisan migrate`
- [ ] CSRF token in layout: `<meta name="csrf-token">`
- [ ] Route exists: `php artisan route:list | grep booking.submit`
- [ ] Controller exists: `app/Http/Controllers/Booking/BookingSubmissionController.php`
- [ ] Models exist: `Guest`, `Booking`, `Property`
- [ ] At least one property in database
- [ ] Check browser console for JS errors (F12)
- [ ] Check Laravel logs: `tail -f storage/logs/laravel.log`

---

## Quick Database Check

```bash
php artisan tinker

# Check properties exist
App\Models\Property::count();
# Should return > 0

# Check guests (after booking)
App\Models\Guest::latest()->first();
# Should show created guest

# Check bookings (after booking)
App\Models\Booking::latest()->first();
# Should show created booking with:
# - booking_ref: BK-XXXXX
# - status: PENDING_PAYMENT
# - currency: KES
# - total_amount: > 0
```

---

## Expected Test Duration

- **Setup:** 2 minutes
- **Fill form:** 1 minute
- **Submit:** 30 seconds
- **Payment page:** Should see immediately
- **Total:** ~4 minutes

## Success Criteria

- [ ] Form submits without error
- [ ] Redirected to payment page
- [ ] Payment page shows booking details in KES
- [ ] Database has new booking
- [ ] No errors in logs

If all checks pass ✅ → **System is working!**
