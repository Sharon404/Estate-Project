# M-PESA C2B (Paybill) Setup for Production

## Issue
C2B URL registration via API returns 401 Unauthorized in production environment.

## Root Cause
Production M-PESA shortcodes typically **do not allow API-based URL registration**. URLs must be configured manually through the Safaricom Business Portal.

## Solution: Manual C2B URL Registration

### Step 1: Login to Safaricom Business Portal
1. Go to https://org.ke.m-pesa.com/
2. Login with your business credentials
3. Navigate to **"C2B Configuration"** or **"API Configuration"**

### Step 2: Register the Following URLs

**Shortcode:** `4002327`

**Validation URL:**
```
https://dierdre-nondialyzing-asthmatically.ngrok-free.dev/api/payment/c2b/validate
```

**Confirmation URL:**
```
https://dierdre-nondialyzing-asthmatically.ngrok-free.dev/api/payment/c2b/confirm
```

**Response Type:** `Completed`

### Step 3: Update URLs for Production Domain

⚠️ **IMPORTANT**: When deploying to production, replace the ngrok URL with your actual domain:

**Validation URL:**
```
https://yourdomain.com/api/payment/c2b/validate
```

**Confirmation URL:**
```
https://yourdomain.com/api/payment/c2b/confirm
```

## How C2B Payments Work

1. **Customer Action**: Customer sends money to Paybill `4002327` with Account Number = Booking Reference (e.g., `BOOK-B3D3TKA8`)

2. **Safaricom Validation**: Safaricom calls your **Validation URL** with transaction details

3. **Your System Response**: Your system validates:
   - Booking exists
   - Booking is payable (not CANCELLED/EXPIRED)
   - Amount is valid
   - Returns `ResultCode: 0` (accept) or `1` (reject)

4. **Safaricom Confirmation**: If validated, Safaricom calls your **Confirmation URL** with final transaction details

5. **Your System Processing**: Your system:
   - Creates BookingTransaction with source='MPESA_C2B'
   - Creates PaymentIntent with method='MPESA_C2B'
   - Updates booking amounts and status
   - Generates receipt
   - Sends email notification

## Testing C2B Flow

### Test with cURL (simulate Safaricom callback)

**Validation:**
```bash
curl -X POST https://dierdre-nondialyzing-asthmatically.ngrok-free.dev/api/payment/c2b/validate \
  -H "Content-Type: application/json" \
  -d '{
    "TransactionType": "Pay Bill",
    "TransID": "TEST123456",
    "TransTime": "20260127140000",
    "TransAmount": "1.00",
    "BusinessShortCode": "4002327",
    "BillRefNumber": "BOOK-B3D3TKA8",
    "MSISDN": "254700000000",
    "FirstName": "Test",
    "LastName": "User"
  }'
```

**Expected Response:**
```json
{"ResultCode":0,"ResultDesc":"Accepted"}
```

**Confirmation:**
```bash
curl -X POST https://dierdre-nondialyzing-asthmatically.ngrok-free.dev/api/payment/c2b/confirm \
  -H "Content-Type: application/json" \
  -d '{
    "TransactionType": "Pay Bill",
    "TransID": "TEST123456",
    "TransTime": "20260127140000",
    "TransAmount": "1.00",
    "BusinessShortCode": "4002327",
    "BillRefNumber": "BOOK-B3D3TKA8",
    "MSISDN": "254700000000",
    "FirstName": "Test",
    "LastName": "User"
  }'
```

**Expected Response:**
```json
{"ResultCode":0,"ResultDesc":"Received"}
```

After confirmation, check:
1. Database: `booking_transactions` table should have new entry with source='MPESA_C2B'
2. Booking status should update to 'PAID' (if fully paid) or 'PARTIALLY_PAID'
3. Receipt should be generated
4. Email should be queued

## Verifying C2B Configuration

Run this command to check current configuration:
```bash
php artisan tinker
```

Then execute:
```php
// Check if C2B service exists
$service = app(\App\Services\MpesaC2BService::class);

// Show current callback URLs
echo "Validation URL: " . config('mpesa.c2b_validation_url') . "\n";
echo "Confirmation URL: " . config('mpesa.c2b_confirmation_url') . "\n";
echo "Shortcode: " . config('mpesa.business_shortcode') . "\n";
```

## Troubleshooting

### Issue: "Booking not found" in validation
- **Cause**: Customer entered wrong Account Number
- **Solution**: Educate customers to use exact Booking Reference (e.g., BOOK-B3D3TKA8)

### Issue: "Booking is not payable"
- **Cause**: Booking status is CANCELLED or EXPIRED
- **Solution**: Customer needs to create new booking

### Issue: No confirmation callback received
- **Cause**: URLs not registered in Safaricom portal, or ngrok tunnel expired
- **Solution**:
  1. Verify URLs registered in Safaricom portal
  2. Check ngrok tunnel is active: `curl https://dierdre-nondialyzing-asthmatically.ngrok-free.dev/api/payment/c2b/confirm`
  3. Check Laravel logs: `tail -f storage/logs/laravel.log | grep C2B`

### Issue: Transaction created but booking not updated
- **Cause**: Error in confirm() processing
- **Solution**: Check Laravel logs for "C2B confirmation processing failed" errors

## Current Status

✅ **Working:**
- STK Push (MPESA_STK) fully functional in production
- C2B validation endpoint responding correctly
- C2B confirmation endpoint processing correctly
- Transaction creation, receipt generation, email notifications

❌ **Not Working:**
- C2B URL registration via API (returns 401 - **normal for production**)

🔧 **Action Required:**
- Manually register C2B URLs in Safaricom Business Portal for shortcode 4002327
- Test C2B flow with actual payment once URLs are registered
- Update URLs when moving from ngrok to production domain

## Notes

- STK Push does NOT require manual URL registration (API-based registration works)
- C2B is more manual in production for security reasons
- Always use HTTPS for callback URLs (Safaricom rejects HTTP)
- Callback URLs must be publicly accessible (no localhost, no IP addresses)
- ngrok URLs work for testing but expire after some time - use production domain for live environment
