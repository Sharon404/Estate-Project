# M-PESA STK Push Integration

Complete implementation of M-PESA Daraja API integration for hotel booking payments.

## Architecture

```
Booking Flow:
1. Guest creates booking (POST /bookings) → DRAFT
2. Guest confirms booking (PATCH /bookings/{id}/confirm) → PENDING_PAYMENT
3. Guest initiates payment:
   a. Create payment intent (POST /payment/intents) → INITIATED
   b. Send STK Push (POST /payment/mpesa/stk) → PENDING (intent status)
   c. Poll status (GET /payment/mpesa/stk/{id}/status)
4. M-PESA Callback (POST /payment/mpesa/callback):
   - Store callback FIRST
   - Create ledger entry (BookingTransaction)
   - Update PaymentIntent status
   - Update Booking amounts/status
```

## Payment Flow (STK Push)

### Step 1: Create Payment Intent

**Endpoint:** `POST /payment/intents`

**Request:**
```json
{
  "booking_id": 1,
  "amount": 5000  // optional, defaults to minimum_deposit
}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment intent created successfully",
  "data": {
    "payment_intent_id": 1,
    "booking_id": 1,
    "amount": 5000,
    "currency": "KES",
    "status": "INITIATED",
    "created_at": "2026-01-22T10:30:00Z"
  }
}
```

**Business Logic:**
- Creates PaymentIntent in INITIATED status
- Validates booking is PENDING_PAYMENT or PARTIALLY_PAID
- Amount cannot exceed amount_due
- Amount defaults to minimum_deposit if not provided

### Step 2: Initiate STK Push

**Endpoint:** `POST /payment/mpesa/stk`

**Request:**
```json
{
  "payment_intent_id": 1,
  "phone_e164": "+254712345678"
}
```

**Response:**
```json
{
  "success": true,
  "message": "STK Push initiated successfully. Please enter PIN on your phone.",
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

**Business Logic:**
- Validates PaymentIntent is INITIATED
- Builds STK Push request payload:
  - Business shortcode
  - Amount
  - Customer phone
  - Callback URL
  - Account reference (booking ref)
- Calls M-PESA Daraja API
- Stores request + response payloads (full audit trail)
- Updates PaymentIntent status to PENDING
- Creates MpesaStkRequest record

**Note:** PaymentIntent status changes to PENDING at this point, NOT after callback.

### Step 3: Poll Payment Status (Client-Side)

**Endpoint:** `GET /payment/mpesa/stk/{stkRequestId}/status`

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

**Client Polling Strategy:**
1. After STK initiated, start polling every 1-2 seconds
2. Stop polling when:
   - has_callback = true (payment completed/failed)
   - has_callback = false for 5 minutes (timeout)
3. Final status determined by callback processing

### Step 4: M-PESA Callback Handler

**Endpoint:** `POST /payment/mpesa/callback`

**Payload (from M-PESA):**
```json
{
  "Body": {
    "stkCallback": {
      "MerchantRequestID": "xxxx",
      "CheckoutRequestID": "ws_CO_DMZ_xxxx",
      "ResultCode": 0,
      "ResultDescription": "The service request has been accepted successfully",
      "CallbackMetadata": {
        "Item": [
          {"Name": "Amount", "Value": 5000},
          {"Name": "MpesaReceiptNumber", "Value": "LHD6660331"},
          {"Name": "TransactionDate", "Value": "20260122103015"},
          {"Name": "PhoneNumber", "Value": "254712345678"}
        ]
      }
    }
  }
}
```

## Callback Processing - NON-NEGOTIABLE SEQUENCE

The callback processing follows a strict, immutable sequence to ensure data integrity:

### If Payment Successful (ResultCode = 0):

**1. STORE CALLBACK FIRST (Immutable Audit Trail)**
```
MpesaStkCallback {
  stk_request_id: 1,
  result_code: 0,
  result_desc: "...",
  mpesa_receipt_number: "LHD6660331",
  transaction_date: "2026-01-22 10:30:15",
  amount: 5000,
  phone_e164: "+254712345678",
  raw_payload: {...}
}
```

**2. CREATE LEDGER ENTRY (Source of Truth)**
```
BookingTransaction {
  booking_id: 1,
  payment_intent_id: 1,
  type: "PAYMENT",
  source: "MPESA_STK",
  external_ref: "LHD6660331",  ← M-PESA receipt (idempotency key)
  amount: 5000,
  currency: "KES",
  meta: {
    stk_request_id: 1,
    phone_e164: "+254712345678",
    transaction_date: "2026-01-22 10:30:15"
  }
}
```

**IDEMPOTENCY:** If BookingTransaction.external_ref already exists, throw error (prevents duplicate payments).

**3. UPDATE PAYMENT INTENT**
```
PaymentIntent {
  status: "SUCCEEDED"
}
```

**4. CALCULATE AND UPDATE BOOKING**
```
All Transactions Sum:
  amount_paid = SUM(BookingTransaction.amount WHERE type='PAYMENT')

amount_due = total_amount - amount_paid

status = {
  "PAID" if amount_due <= 0,
  "PARTIALLY_PAID" if amount_due > 0
}
```

**5. UPDATE STK REQUEST**
```
MpesaStkRequest {
  status: "SUCCESS"
}
```

### If Payment Failed (ResultCode ≠ 0):

**1. STORE CALLBACK FIRST**

**2. UPDATE PAYMENT INTENT**
```
PaymentIntent {
  status: "FAILED"
}
```

**3. UPDATE STK REQUEST**
```
MpesaStkRequest {
  status: "FAILED"
}
```

**4. BOOKING UNCHANGED**
- No ledger entry created
- Booking state remains the same
- Guest can retry with new PaymentIntent + STK

## API Endpoints Summary

### Payment Intent Management
```
POST   /payment/intents                    Create payment intent
GET    /payment/intents/{id}               Get payment intent
GET    /payment/bookings/{id}/options      Get payment options
GET    /payment/bookings/{id}/history      Get payment history
```

### M-PESA STK Push
```
POST   /payment/mpesa/stk                  Initiate STK Push
GET    /payment/mpesa/stk/{id}/status      Poll payment status
POST   /payment/mpesa/callback             M-PESA callback (webhook)
```

## Configuration (.env)

```bash
# M-PESA Credentials (from Daraja)
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_PASSKEY=your_passkey
MPESA_BUSINESS_SHORTCODE=174379

# API URLs
MPESA_AUTH_URL=https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials
MPESA_STK_PUSH_URL=https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest

# Callback URL (must be publicly accessible to M-PESA)
MPESA_CALLBACK_URL=https://yourdomain.com/payment/mpesa/callback

# Testing
MPESA_MOCK_MODE=true
MPESA_MOCK_ACCESS_TOKEN=mock_token_12345
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Payment/
│   │       ├── MpesaController.php      # STK & callback handlers
│   │       └── PaymentController.php    # Payment intent management
│   └── Requests/
│       └── InitiateStkRequest.php       # STK validation
├── Models/
│   ├── PaymentIntent.php                # Payment records
│   ├── MpesaStkRequest.php              # STK requests
│   ├── MpesaStkCallback.php             # STK callbacks
│   └── BookingTransaction.php           # Ledger entries
├── Services/
│   ├── PaymentService.php               # Payment orchestration
│   ├── MpesaStkService.php              # STK API interaction
│   └── MpesaCallbackService.php         # Callback processing
└── Jobs/
    └── ProcessMpesaCallback.php         # Async callback processing (future)

routes/
└── web.php                              # Payment routes

config/
└── mpesa.php                            # M-PESA configuration

database/migrations/
├── create_payment_intents_table.php
├── create_mpesa_stk_requests_table.php
├── create_mpesa_stk_callbacks_table.php
└── create_booking_transactions_table.php
```

## Key Design Decisions

### 1. Do NOT Update Booking During STK
- Booking state changes ONLY when callback is processed
- STK request changes indicate M-PESA status, not final payment status
- Prevents inconsistency if callback is delayed or lost

### 2. Store Callback BEFORE Processing
- Ensures raw M-PESA data is preserved even if processing fails
- Audit trail for debugging
- Can retry processing logic independently

### 3. Ledger-First Accounting
- BookingTransaction is source of truth for financial state
- Booking amounts calculated FROM ledger, not directly from callback
- Immutable payment records
- Idempotent: duplicate receipts are detected via external_ref

### 4. PaymentIntent as State Machine
```
INITIATED → PENDING → SUCCEEDED
                  ↓
                FAILED
```

### 5. Booking as Derived State
```
Booking.amount_paid = SUM(BookingTransaction.amount WHERE type='PAYMENT')
Booking.amount_due = total_amount - amount_paid
Booking.status = PAID | PARTIALLY_PAID | PENDING_PAYMENT
```

## Testing

### Sandbox Mode
```bash
# In .env
MPESA_MOCK_MODE=true
MPESA_STK_PUSH_URL=https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest
```

### Test Flow
```bash
# 1. Create booking
curl -X POST http://localhost:8001/bookings \
  -H "Content-Type: application/json" \
  -d '{...}'

# 2. Confirm booking
curl -X PATCH http://localhost:8001/bookings/1/confirm

# 3. Create payment intent
curl -X POST http://localhost:8001/payment/intents \
  -H "Content-Type: application/json" \
  -d '{"booking_id": 1, "amount": 5000}'

# 4. Initiate STK
curl -X POST http://localhost:8001/payment/mpesa/stk \
  -H "Content-Type: application/json" \
  -d '{"payment_intent_id": 1, "phone_e164": "+254712345678"}'

# 5. Poll status
curl http://localhost:8001/payment/mpesa/stk/1/status

# 6. Simulate callback (from M-PESA server)
curl -X POST http://localhost:8001/payment/mpesa/callback \
  -H "Content-Type: application/json" \
  -d '{...callback payload...}'
```

## Error Handling

### Common Errors

| Error | Code | Cause | Solution |
|-------|------|-------|----------|
| "Booking must be PENDING_PAYMENT" | 400 | Wrong booking status | Confirm booking first |
| "Payment amount exceeds amount due" | 400 | Amount too high | Check booking.amount_due |
| "Cannot initiate STK for PENDING intent" | 400 | Intent already used | Create new intent |
| "M-PESA API Error" | 400 | API call failed | Check credentials, retry |
| "Duplicate payment detected" | 400 | Receipt already processed | Contact support |
| "CheckoutRequestID not found" | 400 | Invalid callback | Verify M-PESA webhook |

## Monitoring

### Logging
- All M-PESA API calls logged
- All callbacks logged with receipt number
- All errors logged with context
- Check: `storage/logs/laravel.log`

### Status Dashboard
```sql
-- Pending payments
SELECT * FROM payment_intents WHERE status = 'PENDING';

-- Successful payments
SELECT * FROM booking_transactions WHERE type = 'PAYMENT' ORDER BY created_at DESC;

-- Failed intents
SELECT * FROM payment_intents WHERE status = 'FAILED';

-- Booking payment status
SELECT booking_ref, amount_paid, amount_due, status 
FROM bookings 
WHERE status IN ('PENDING_PAYMENT', 'PARTIALLY_PAID');
```

## Future Enhancements

1. **Async Callback Processing**
   - Queue callback processing jobs
   - Retry failed callbacks
   - Webhook verification

2. **Payment Confirmation SMS**
   - Send SMS to guest after successful payment
   - Include receipt number and booking reference

3. **Refunds**
   - Handle M-PESA refunds
   - Reverse ledger entries

4. **Partial Payments**
   - Allow deposit then balance payment
   - Track payment installments

5. **Payment Dashboard**
   - Real-time payment status
   - Revenue reports
   - Payment analytics
