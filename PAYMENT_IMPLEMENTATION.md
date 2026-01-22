# M-PESA STK Push Integration - Implementation Summary

## ✅ Completed Implementation

### 1. Core Services
- ✅ **PaymentService** - Orchestrates entire payment flow
  - Create payment intent
  - Initiate payment
  - Get payment status
  - Payment options & history

- ✅ **MpesaStkService** - M-PESA API interaction
  - Build STK Push request
  - Call Daraja API
  - Store request + response payloads
  - Generate OAuth tokens & passwords

- ✅ **MpesaCallbackService** - Callback processing
  - Store callback FIRST (immutable)
  - Create ledger entries (BookingTransaction)
  - Update payment intent status
  - Update booking amounts & status
  - Idempotency checking (duplicate receipt detection)

### 2. Controllers
- ✅ **PaymentController** - Payment intent endpoints
  - POST /payment/intents - Create intent
  - GET /payment/intents/{id} - Get intent details
  - GET /payment/bookings/{id}/options - Payment options
  - GET /payment/bookings/{id}/history - Payment history

- ✅ **MpesaController** - M-PESA endpoints
  - POST /payment/mpesa/stk - Initiate STK Push
  - GET /payment/mpesa/stk/{id}/status - Poll status
  - POST /payment/mpesa/callback - M-PESA webhook

### 3. Models
- ✅ **PaymentIntent** - Payment tracking
  - Tracks: booking, amount, currency, status
  - Status: INITIATED → PENDING → SUCCEEDED/FAILED
  - Relations: booking, mpesaStkRequests, receipts

- ✅ **MpesaStkRequest** - STK requests
  - Stores: request & response payloads
  - Status: REQUESTED → ACCEPTED → SUCCESS/FAILED
  - Full audit trail of M-PESA interaction

- ✅ **MpesaStkCallback** - Callback records
  - Stores: raw payload, result code, receipt number
  - Immutable - never updated after creation
  - Source of truth for transaction data

- ✅ **BookingTransaction** - Ledger entries
  - Tracks: all payments by booking
  - Idempotent key: external_ref (M-PESA receipt)
  - Source of truth for financial state

### 4. Routes
- ✅ Payment intent routes (POST, GET)
- ✅ M-PESA STK routes (POST, GET)
- ✅ M-PESA callback route (POST webhook)

### 5. Configuration
- ✅ M-PESA credentials (consumer_key, secret, passkey)
- ✅ API URLs (auth, STK push)
- ✅ Business shortcode
- ✅ Mock mode for testing
- ✅ Callback URL configuration

## 🔄 Payment Flow

### Client Request Flow
```
1. Guest navigates to booking confirmation
2. Clicks "Pay Now"
3. Frontend calls:
   POST /payment/intents
   → Creates PaymentIntent (INITIATED)

4. Frontend requests payment details
5. Guest enters phone number
6. Frontend calls:
   POST /payment/mpesa/stk
   → Sends STK Push to M-PESA
   → PaymentIntent → PENDING
   → Returns checkout_request_id

7. Frontend starts polling:
   GET /payment/mpesa/stk/{id}/status
   → Checks for callback (has_callback flag)

8. Guest receives STK on phone
9. Guest enters M-PESA PIN

10. M-PESA server sends callback:
    POST /payment/mpesa/callback
    → Stored FIRST (immutable)
    → Processes: ledger → intent → booking
```

### Server Processing
```
Callback Handler (MpesaController):
├─ 1. Store callback (MpesaStkCallback) ✓
├─ 2. Find STK request by checkout_request_id ✓
├─ 3. Call MpesaCallbackService::processCallback
│  ├─ Check result code (0 = success)
│  ├─ If SUCCESS:
│  │  ├─ Check idempotency (receipt already processed?)
│  │  ├─ Create BookingTransaction (ledger entry)
│  │  ├─ Update PaymentIntent → SUCCEEDED
│  │  ├─ Calculate: amount_paid = SUM(transactions)
│  │  ├─ Calculate: amount_due = total - amount_paid
│  │  ├─ Update Booking: amounts + status
│  │  └─ Update MpesaStkRequest → SUCCESS
│  └─ If FAILED:
│     ├─ Update PaymentIntent → FAILED
│     ├─ Update MpesaStkRequest → FAILED
│     └─ Leave Booking unchanged
└─ 4. Return success to M-PESA (they'll stop retrying)
```

## 📊 Database State Machine

### PaymentIntent Status
```
INITIATED ──┬──→ PENDING ──┬──→ SUCCEEDED (callback success)
            │              └──→ FAILED (callback failure)
            │
            └──→ FAILED (STK API error)
```

### Booking Status
```
DRAFT ──→ PENDING_PAYMENT ──┬──→ PARTIALLY_PAID (partial payment)
                             └──→ PAID (amount_due ≤ 0)
```

### MpesaStkRequest Status
```
REQUESTED ──→ ACCEPTED ──┬──→ SUCCESS (callback success)
              (from API)  └──→ FAILED (callback failure)
```

## 🔐 Key Guarantees

### 1. Idempotency
- Duplicate payments prevented by external_ref (M-PESA receipt number)
- Exception thrown if receipt already processed
- Safe for M-PESA to retry callbacks

### 2. Immutability
- Callbacks never deleted or modified
- BookingTransactions never deleted (only added)
- Full audit trail preserved

### 3. Atomicity
- All updates in single transaction
- If any step fails, entire callback processing fails
- M-PESA will retry (idempotency prevents duplicates)

### 4. Source of Truth
- BookingTransaction is source of truth
- Booking amounts CALCULATED from ledger
- Not directly updated from callback

## 📝 API Examples

### Create Payment Intent
```bash
curl -X POST http://localhost:8001/payment/intents \
  -H "Content-Type: application/json" \
  -d {
    "booking_id": 1,
    "amount": 5000
  }
```

**Response:**
```json
{
  "success": true,
  "data": {
    "payment_intent_id": 1,
    "booking_id": 1,
    "amount": 5000,
    "currency": "KES",
    "status": "INITIATED"
  }
}
```

### Initiate STK Push
```bash
curl -X POST http://localhost:8001/payment/mpesa/stk \
  -H "Content-Type: application/json" \
  -d {
    "payment_intent_id": 1,
    "phone_e164": "+254712345678"
  }
```

**Response:**
```json
{
  "success": true,
  "message": "STK Push initiated successfully...",
  "data": {
    "stk_request_id": 1,
    "checkout_request_id": "ws_CO_DMZ_xxxxx",
    "merchant_request_id": "29115-xxxx-xxxx",
    "phone_e164": "+254712345678",
    "amount": 5000,
    "status": "REQUESTED"
  }
}
```

### Poll Payment Status
```bash
curl http://localhost:8001/payment/mpesa/stk/1/status
```

**Response:**
```json
{
  "success": true,
  "data": {
    "stk_request_id": 1,
    "stk_status": "ACCEPTED",
    "payment_intent_status": "PENDING",
    "booking_status": "PENDING_PAYMENT",
    "amount_paid": 0,
    "amount_due": 5000,
    "has_callback": false,
    "last_callback_at": null
  }
}
```

### After Callback (Success)
```json
{
  "stk_status": "SUCCESS",
  "payment_intent_status": "SUCCEEDED",
  "booking_status": "PAID",
  "amount_paid": 5000,
  "amount_due": 0,
  "has_callback": true,
  "last_callback_at": "2026-01-22T10:35:00Z"
}
```

## 🧪 Testing Checklist

- [ ] Create booking successfully
- [ ] Confirm booking moves to PENDING_PAYMENT
- [ ] Create payment intent (deposit amount)
- [ ] Verify intent status = INITIATED
- [ ] Initiate STK with valid phone
- [ ] Verify intent status changes to PENDING
- [ ] Poll status before callback
- [ ] Receive M-PESA STK prompt
- [ ] Enter PIN and complete payment
- [ ] Wait for callback
- [ ] Poll status after callback
- [ ] Verify intent status = SUCCEEDED
- [ ] Verify booking status = PAID
- [ ] Verify amount_paid updated
- [ ] Verify booking_transaction created
- [ ] Attempt duplicate payment (should fail)
- [ ] Get payment history (should show transaction)

## 📞 Support & Debugging

### Common Issues

**"Cannot create payment intent for booking in X status"**
- Booking must be PENDING_PAYMENT or PARTIALLY_PAID
- Check: GET /bookings/{id}/summary

**"Payment amount exceeds amount due"**
- Check remaining balance: GET /payment/bookings/{id}/options
- Create intent with valid amount

**"Cannot initiate STK for payment intent in X status"**
- Intent already used or failed
- Create new intent

**"M-PESA API Error: Invalid credentials"**
- Check config/mpesa.php credentials
- Verify MPESA_CONSUMER_KEY and MPESA_CONSUMER_SECRET
- Check test mode enabled if using sandbox

**"Duplicate payment detected"**
- Receipt number already processed
- This is correct behavior (prevents double-charging)
- Customer already paid

### Debug Logging
```bash
# Check M-PESA logs
tail -f storage/logs/laravel.log | grep -i mpesa

# Check specific payment
SELECT * FROM payment_intents WHERE id = 1\G
SELECT * FROM mpesa_stk_requests WHERE payment_intent_id = 1\G
SELECT * FROM mpesa_stk_callbacks WHERE stk_request_id = 1\G
SELECT * FROM booking_transactions WHERE payment_intent_id = 1\G
SELECT booking_ref, amount_paid, amount_due, status FROM bookings WHERE id = 1\G
```

## 🚀 Production Deployment

### Pre-Deployment Checklist
- [ ] Switch MPESA_MOCK_MODE to false
- [ ] Update MPESA_CONSUMER_KEY (production)
- [ ] Update MPESA_CONSUMER_SECRET (production)
- [ ] Update MPESA_PASSKEY (production)
- [ ] Update MPESA_CALLBACK_URL to production domain
- [ ] Run database migrations
- [ ] Test complete flow with real M-PESA credentials
- [ ] Set up monitoring/alerts for failed payments
- [ ] Configure backup callback URL (if needed)
- [ ] Document payment troubleshooting for support team

### Monitoring
```bash
# Monitor pending payments
SELECT COUNT(*) FROM payment_intents WHERE status = 'PENDING';

# Monitor successful payments
SELECT SUM(amount) FROM booking_transactions WHERE type = 'PAYMENT';

# Monitor failed payments
SELECT COUNT(*) FROM payment_intents WHERE status = 'FAILED';
```

## 📚 Related Documentation
- See [MPESA_INTEGRATION.md](./MPESA_INTEGRATION.md) for detailed API documentation
- See [Booking Flow](./BOOKING_FLOW.md) for complete booking lifecycle
- See M-PESA Daraja API: https://developer.safaricom.co.ke/docs
