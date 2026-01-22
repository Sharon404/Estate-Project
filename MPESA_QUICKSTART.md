# M-PESA Payment Integration - Quick Start Guide

## 🚀 Getting Started

### 1. Environment Setup

Add to your `.env`:
```bash
# M-PESA Daraja Credentials (from https://developer.safaricom.co.ke)
MPESA_CONSUMER_KEY=your_consumer_key_here
MPESA_CONSUMER_SECRET=your_consumer_secret_here
MPESA_PASSKEY=your_passkey_here
MPESA_BUSINESS_SHORTCODE=174379

# For Testing
MPESA_MOCK_MODE=true
MPESA_MOCK_ACCESS_TOKEN=mock_token_12345

# Callback URL (must be publicly accessible)
MPESA_CALLBACK_URL=https://yourdomain.com/payment/mpesa/callback
```

### 2. Run Migrations
```bash
php artisan migrate
```

This creates:
- `payment_intents` table
- `mpesa_stk_requests` table
- `mpesa_stk_callbacks` table
- `booking_transactions` table

### 3. Test the Flow

#### Option A: Using cURL

```bash
# 1. Create booking
BOOKING=$(curl -X POST http://localhost:8001/bookings \
  -H "Content-Type: application/json" \
  -d '{
    "property_id": 1,
    "check_in": "2026-02-01",
    "check_out": "2026-02-05",
    "adults": 2,
    "children": 1,
    "guest": {
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_e164": "+254712345678"
    }
  }' | jq -r '.data.id')

echo "Booking ID: $BOOKING"

# 2. Confirm booking
curl -X PATCH http://localhost:8001/bookings/$BOOKING/confirm \
  -H "Content-Type: application/json" \
  -d '{
    "special_requests": "High floor preferred"
  }' | jq

# 3. Create payment intent
INTENT=$(curl -X POST http://localhost:8001/payment/intents \
  -H "Content-Type: application/json" \
  -d "{
    \"booking_id\": $BOOKING,
    \"amount\": 5000
  }" | jq -r '.data.payment_intent_id')

echo "Payment Intent ID: $INTENT"

# 4. Get payment options
curl http://localhost:8001/payment/bookings/$BOOKING/options | jq

# 5. Initiate STK
STK=$(curl -X POST http://localhost:8001/payment/mpesa/stk \
  -H "Content-Type: application/json" \
  -d "{
    \"payment_intent_id\": $INTENT,
    \"phone_e164\": \"+254712345678\"
  }" | jq -r '.data.stk_request_id')

echo "STK Request ID: $STK"

# 6. Poll status
curl http://localhost:8001/payment/mpesa/stk/$STK/status | jq

# 7. Get payment history
curl http://localhost:8001/payment/bookings/$BOOKING/history | jq
```

#### Option B: Using Postman

Import [payment-api.postman_collection.json](./postman/payment-api.postman_collection.json):

```bash
# Create environment variables:
- base_url: http://localhost:8001
- booking_id: (set after creating booking)
- payment_intent_id: (set after creating intent)
- stk_request_id: (set after initiating STK)
```

#### Option C: Using the Test Script

```bash
# Make executable
chmod +x tests/integration/test-mpesa-flow.sh

# Run
./tests/integration/test-mpesa-flow.sh
```

## 💻 Frontend Integration

### Payment Page Flow

```html
<!-- 1. Display booking details -->
<h1>Booking Confirmation</h1>
<p>Booking Ref: <span id="booking-ref">BHKXXXXX</span></p>
<p>Amount Due: <span id="amount-due">5000</span> KES</p>

<!-- 2. Payment options -->
<div id="payment-options">
  <button onclick="payDeposit()">Pay Deposit</button>
  <button onclick="payFull()">Pay Full Amount</button>
</div>

<!-- 3. Phone input & payment button -->
<div id="payment-form" style="display:none;">
  <input type="text" id="phone" placeholder="+254712345678" />
  <button onclick="initiatePayment()">Pay with M-PESA</button>
</div>

<!-- 4. Status polling -->
<div id="payment-status" style="display:none;">
  <p>Waiting for payment...</p>
  <div id="status-updates"></div>
</div>

<script>
// Get booking ID from URL or session
const bookingId = new URLSearchParams(window.location.search).get('booking_id');

// 1. Fetch payment options
async function loadPaymentOptions() {
  const response = await fetch(`/payment/bookings/${bookingId}/options`);
  const data = await response.json();
  document.getElementById('amount-due').textContent = data.data.amount_due;
}

// 2. Create payment intent
async function createPaymentIntent(amount) {
  const response = await fetch('/payment/intents', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      booking_id: bookingId,
      amount: amount
    })
  });
  
  const data = await response.json();
  if (!data.success) {
    alert('Failed to create payment intent: ' + data.error);
    return null;
  }
  
  return data.data.payment_intent_id;
}

// 3. Initiate STK
async function initiatePayment() {
  const phone = document.getElementById('phone').value;
  const intentId = window.currentIntentId; // Set by payDeposit/payFull
  
  const response = await fetch('/payment/mpesa/stk', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      payment_intent_id: intentId,
      phone_e164: phone
    })
  });
  
  const data = await response.json();
  if (!data.success) {
    alert('Failed to initiate payment: ' + data.error);
    return;
  }
  
  window.stkRequestId = data.data.stk_request_id;
  
  // Show "Enter PIN" message
  document.getElementById('payment-form').style.display = 'none';
  document.getElementById('payment-status').style.display = 'block';
  
  // Start polling
  pollPaymentStatus();
}

// 4. Poll status
async function pollPaymentStatus() {
  const interval = setInterval(async () => {
    const response = await fetch(`/payment/mpesa/stk/${window.stkRequestId}/status`);
    const data = await response.json();
    
    const statusDiv = document.getElementById('status-updates');
    statusDiv.innerHTML = `
      <p>STK Status: ${data.data.stk_status}</p>
      <p>Booking Status: ${data.data.booking_status}</p>
      <p>Amount Paid: ${data.data.amount_paid} KES</p>
      <p>Amount Due: ${data.data.amount_due} KES</p>
    `;
    
    // Stop polling when callback received
    if (data.data.has_callback) {
      clearInterval(interval);
      
      if (data.data.booking_status === 'PAID') {
        alert('Payment successful!');
        window.location.href = `/bookings/${bookingId}/confirmation`;
      } else {
        alert('Payment failed. Please try again.');
        location.reload();
      }
    }
  }, 2000); // Poll every 2 seconds
}

// Button handlers
async function payDeposit() {
  const intentId = await createPaymentIntent(5000); // deposit amount
  if (intentId) {
    window.currentIntentId = intentId;
    document.getElementById('payment-form').style.display = 'block';
  }
}

async function payFull() {
  const intentId = await createPaymentIntent(20000); // full amount
  if (intentId) {
    window.currentIntentId = intentId;
    document.getElementById('payment-form').style.display = 'block';
  }
}

// Load on page load
loadPaymentOptions();
</script>
```

## 📱 Testing with M-PESA Simulator

### Sandbox Credentials

Get from: https://developer.safaricom.co.ke/test_credentials

```
Consumer Key: [your test key]
Consumer Secret: [your test secret]
Business Shortcode: 174379
Passkey: bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919
Test MSISDN: 254712345678
```

### Simulate STK Push

M-PESA provides a web interface to simulate callbacks:
https://sandbox.safaricom.co.ke/mpesademo

1. Enter Checkout Request ID
2. Select "Confirm"
3. Callback sent to your URL

## 🐛 Debugging

### Check Request/Response Payloads

```sql
-- View latest STK request
SELECT * FROM mpesa_stk_requests 
ORDER BY created_at DESC 
LIMIT 1\G

-- View payload
SELECT 
  id,
  phone_e164,
  checkout_request_id,
  request_payload,
  response_payload,
  status,
  created_at
FROM mpesa_stk_requests 
WHERE id = 1\G

-- View callback
SELECT 
  id,
  result_code,
  result_desc,
  mpesa_receipt_number,
  raw_payload,
  created_at
FROM mpesa_stk_callbacks 
WHERE stk_request_id = 1\G
```

### Check Logs

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Filter for M-PESA
tail -f storage/logs/laravel.log | grep -i mpesa

# Filter for payment
tail -f storage/logs/laravel.log | grep -i payment
```

### Verify Booking State

```sql
SELECT 
  booking_ref,
  status,
  total_amount,
  amount_paid,
  amount_due,
  minimum_deposit,
  created_at,
  updated_at
FROM bookings 
WHERE id = 1\G
```

### Check Ledger

```sql
SELECT 
  id,
  booking_id,
  type,
  source,
  external_ref,
  amount,
  currency,
  created_at
FROM booking_transactions 
WHERE booking_id = 1
ORDER BY created_at DESC\G
```

## 🚨 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Invalid credentials" | Check MPESA_CONSUMER_KEY, MPESA_CONSUMER_SECRET in .env |
| STK not appearing on phone | Check MPESA_MOCK_MODE=false for production |
| Callback not received | Verify MPESA_CALLBACK_URL is publicly accessible |
| "Booking not found" | Confirm booking first with PATCH /bookings/{id}/confirm |
| "Payment amount exceeds amount due" | Check amount ≤ booking.amount_due |
| "Duplicate payment detected" | This is correct - receipt already processed |

## 📚 Next Steps

1. **Read Full Documentation:** [MPESA_INTEGRATION.md](./MPESA_INTEGRATION.md)
2. **Understand Flow:** [PAYMENT_IMPLEMENTATION.md](./PAYMENT_IMPLEMENTATION.md)
3. **Deploy to Production:** Update .env with real credentials
4. **Monitor Payments:** Set up logging & alerts
5. **Handle Edge Cases:** Implement retry logic, refunds, etc.

## 🆘 Support

For issues:
1. Check logs: `storage/logs/laravel.log`
2. Review payload data in database
3. Verify M-PESA credentials
4. Test with cURL examples above
5. Check M-PESA Daraja API docs

## 📞 M-PESA Resources

- **Daraja API Docs:** https://developer.safaricom.co.ke/docs
- **Test Credentials:** https://developer.safaricom.co.ke/test_credentials
- **STK Push Guide:** https://developer.safaricom.co.ke/docs
- **Support:** developer@safaricom.co.ke
