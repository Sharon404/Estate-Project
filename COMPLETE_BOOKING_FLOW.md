# Complete Booking → Payment → Receipt Flow Implementation

## Status: PRODUCTION-READY ✅

This document outlines the complete, M-PESA-gated booking flow implemented across the Estate Project codebase.

---

## FLOW OVERVIEW

```
1. GUEST SUBMITS FORM
   ↓
2. BACKEND CREATES DRAFT BOOKING
   ↓
3. GUEST SEES CONFIRMATION MODAL
   (Booking reference generated here)
   ↓
4. GUEST CONFIRMS & LOCKS BOOKING
   (Status → PENDING_PAYMENT)
   ↓
5. GUEST INITIATES M-PESA STK PUSH
   ↓
6. [STK SUCCESS] → CALLBACK RECEIVED → LEDGER CREATED → BOOKING MARKED PAID
   ↓
7. [STK FAILS] → GUEST SUBMITS MANUAL RECEIPT
   ↓
8. ADMIN VERIFIES MANUAL PAYMENT
   ↓
9. RECEIPT GENERATED & EMAIL QUEUED
   ↓
10. BOOKING COMPLETE
```

---

## STEP 1: RESERVATION SUBMISSION (NO PAYMENT YET)

### Endpoint
```
POST /bookings
Content-Type: application/json
```

### Request Body
```json
{
  "property_id": 1,
  "check_in": "2026-01-25",
  "check_out": "2026-01-28",
  "adults": 2,
  "children": 1,
  "special_requests": "Early check-in preferred",
  "guest": {
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone_e164": "+254701123456"
  }
}
```

### Validation
- Property must exist
- Check-in must be in future, check-out must be after check-in
- Adults minimum 1
- Children minimum 0
- Guest email unique
- Phone in E.164 format

### Backend Behavior (BookingController::store)
1. **Create/find guest** via Guest::firstOrCreate(['email' => ...])
2. **Calculate:**
   - nights = checkout - checkin
   - nightly_rate from property
   - accommodation_subtotal = nightly_rate × nights
   - total_amount = accommodation_subtotal + addons_subtotal (0 initially)
3. **Create booking** in DRAFT status
   - booking_ref = NULL (generated later)
   - status = DRAFT
   - amount_due = total_amount
   - amount_paid = 0
4. **Log** booking_created_draft audit

### Response (201 Created)
```json
{
  "success": true,
  "message": "Reservation created successfully",
  "data": {
    "id": 1,
    "booking_ref": null,
    "status": "DRAFT",
    "guest": { ... },
    "check_in": "2026-01-25",
    "check_out": "2026-01-28",
    "nights": 3,
    "nightly_rate": 15000.00,
    "accommodation_subtotal": 45000.00,
    "addons_subtotal": 0.00,
    "total_amount": 45000.00,
    "amount_paid": 0.00,
    "amount_due": 45000.00,
    "currency": "KES"
  }
}
```

---

## STEP 2: CONFIRMATION SUMMARY (MODAL DATA)

### Endpoint
```
GET /bookings/{id}/summary
```

### Backend Behavior (BookingController::summary)
1. **Validate** booking is in DRAFT status
2. **Generate booking_ref** if not set (via BookingReferenceService)
   - Format: BK{YYYYMMDD}{5-char random}
   - Example: BK202601251A3K9
3. **Persist booking_ref** in database (update booking)
4. **Return summary** with all details

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "booking_ref": "BK202601251A3K9",
    "status": "DRAFT",
    "guest": {
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_e164": "+254701123456"
    },
    "property": {
      "name": "Standard Room",
      "nightly_rate": 15000.00
    },
    "check_in": "2026-01-25",
    "check_out": "2026-01-28",
    "adults": 2,
    "children": 1,
    "nights": 3,
    "nightly_rate": 15000.00,
    "accommodation_subtotal": 45000.00,
    "total_amount": 45000.00,
    "amount_due": 45000.00,
    "currency": "KES",
    "minimum_deposit": 15000.00
  }
}
```

---

## STEP 3: CONFIRM & LOCK BOOKING

### Endpoint
```
PATCH /bookings/{id}/confirm
Content-Type: application/json
```

### Request Body
```json
{
  "adults": 2,
  "children": 1,
  "special_requests": "Early check-in preferred"
}
```

### Validation
- All fields optional
- adults min 1
- children min 0
- special_requests max 500 chars

### Backend Behavior (BookingController::confirm)
1. **Validate** booking is in DRAFT status
2. **Update editable fields** (if provided)
3. **Lock booking** status → PENDING_PAYMENT
4. **Log** booking_confirmed_pending_payment audit
5. **Return updated summary**

### Response (200 OK)
```json
{
  "success": true,
  "message": "Booking confirmed successfully and moved to payment",
  "data": {
    "id": 1,
    "booking_ref": "BK202601251A3K9",
    "status": "PENDING_PAYMENT",
    "total_amount": 45000.00,
    "amount_due": 45000.00
  }
}
```

---

## STEP 4: INITIATE M-PESA STK PUSH

### Endpoint
```
POST /payment/mpesa/stk
Content-Type: application/json
```

### Request Body
```json
{
  "booking_id": 1,
  "amount": 45000.00,
  "phone_e164": "+254701123456"
}
```

### Validation (InitiateStkRequest)
- booking_id must exist
- amount optional (defaults to minimum_deposit)
- phone in E.164 format

### Backend Behavior (MpesaController::initiateStk)
1. **Create payment_intent** via PaymentIntentService
   - status = INITIATED
   - method = MPESA_STK
   - amount = request amount or minimum_deposit
2. **Call existing STK service** (MpesaStkService)
   - Send request to M-PESA API
   - Get checkout_request_id, merchant_request_id
3. **Create mpesa_stk_requests record**
   - Store request payload
   - status = SENT
4. **Return STK details** to client

### Response (200 OK)
```json
{
  "success": true,
  "message": "STK Push initiated. Please enter PIN on your phone.",
  "data": {
    "stk_request_id": 1,
    "checkout_request_id": "ws_CO_28062023092244...",
    "merchant_request_id": "16740827391",
    "phone_e164": "+254701123456",
    "amount": 45000.00,
    "booking_ref": "BK202601251A3K9",
    "status_url": "/payment/mpesa/stk/1/status"
  }
}
```

---

## STEP 5A: STK CALLBACK - SUCCESS

### M-PESA Calls (Server-to-Server)
```
POST /payment/mpesa/callback
Content-Type: application/json
```

### Callback Payload
```json
{
  "Body": {
    "stkCallback": {
      "MerchantCheckoutSessionID": "16740827391",
      "CheckoutRequestID": "ws_CO_28062023092244...",
      "ResultCode": 0,
      "ResultDescription": "The service request has been processed successfully.",
      "CallbackMetadata": {
        "Item": [
          { "Name": "Amount", "Value": 45000 },
          { "Name": "MpesaReceiptNumber", "Value": "LIK123ABC456" },
          { "Name": "TransactionDate", "Value": 20260123093000 },
          { "Name": "PhoneNumber", "Value": 254701123456 }
        ]
      }
    }
  }
}
```

### Backend Behavior (MpesaController::callback)
1. **Store callback** safely (before processing)
   - Create MpesaStkCallback record
   - Store raw payload
2. **Validate** booking is in PENDING_PAYMENT
3. **Call MpesaCallbackService::processCallback**
4. **On success:**
   - Check idempotency (receipt not already processed)
   - **Create BookingTransaction record** (LEDGER ENTRY)
     - type = CREDIT
     - amount = callback amount
     - reference = receipt number
     - posted_at = now()
   - Recalculate booking totals from ledger
   - **Update booking:**
     - amount_paid = sum of CREDIT transactions
     - amount_due = total_amount - amount_paid
     - status = PAID (if amount_due <= 0) OR PARTIALLY_PAID
   - **Update payment_intent:**
     - status = SUCCEEDED
   - Generate Receipt record
   - Queue ReceiptEmail
5. **Log** booking_payment_received audit

### Database Records Created
- MpesaStkCallback (callback stored)
- BookingTransaction (LEDGER - immutable proof of payment)
- Receipt (invoice document)
- EmailOutbox (confirmation email)

### Response (200 OK)
```json
{
  "success": true,
  "message": "Payment processed successfully",
  "data": {
    "booking_id": 1,
    "booking_ref": "BK202601251A3K9",
    "status": "PAID",
    "amount_paid": 45000.00,
    "amount_due": 0.00,
    "receipt_number": "RCP-BK202601251A3K9",
    "transaction_id": 1
  }
}
```

---

## STEP 5B: STK CALLBACK - FAILURE OR TIMEOUT

### Failure Codes
- ResultCode != 0 = User cancelled or error
- Timeout = No callback received within 30 minutes

### Backend Behavior
1. **Store callback** with failure details
2. **Update mpesa_stk_requests** status = FAILED
3. **Update payment_intent** status = INITIATED (allow retry)
4. **Keep booking** in PENDING_PAYMENT status
5. **Log** booking_stk_failed audit
6. **Allow guest** to:
   - Retry STK (submit new form)
   - Submit manual receipt (fallback)

---

## STEP 6: MANUAL M-PESA SUBMISSION (FALLBACK)

### Endpoint
```
POST /payment/manual-entry
Content-Type: application/json
```

### Request Body
```json
{
  "booking_id": 1,
  "mpesa_receipt_number": "LIK123ABC456",
  "amount": 45000.00,
  "phone_e164": "+254701123456",
  "transaction_date": "2026-01-23 09:30:00"
}
```

### Validation (SubmitManualMpesaRequest)
- booking_id must exist
- receipt_number format: 10 alphanumeric chars
- amount > 0
- phone in E.164 format

### Backend Behavior (PaymentController::submitManualPayment)
1. **Validate booking** is in PENDING_PAYMENT
2. **Check idempotency** (receipt not already submitted)
3. **Create mpesa_manual_submissions record**
   - status = PENDING_REVIEW (awaiting admin)
   - submitted_at = now()
4. **Update payment_intent** status = UNDER_REVIEW
5. **Keep booking** in PENDING_PAYMENT (not marked paid yet)
6. **Log** booking_manual_payment_submitted audit
7. **Notify admin** of pending verification

### Response (201 Created)
```json
{
  "success": true,
  "message": "Receipt submitted for verification",
  "data": {
    "submission_id": 1,
    "status": "PENDING_REVIEW",
    "booking_ref": "BK202601251A3K9",
    "submitted_at": "2026-01-23T09:35:00Z"
  }
}
```

---

## STEP 7: ADMIN VERIFICATION OF MANUAL PAYMENT

### Admin Endpoint - Get Pending
```
GET /admin/payment/manual-submissions/pending
```

### Response
```json
{
  "success": true,
  "data": [
    {
      "submission_id": 1,
      "receipt_number": "LIK123ABC456",
      "amount": 45000.00,
      "booking_ref": "BK202601251A3K9",
      "guest_email": "john@example.com",
      "status": "PENDING_REVIEW",
      "submitted_at": "2026-01-23T09:35:00Z"
    }
  ]
}
```

### Admin Endpoint - Verify Payment
```
POST /admin/payment/manual-submissions/{submissionId}/verify
Content-Type: application/json
```

### Request Body
```json
{
  "verified_notes": "Receipt verified against M-PESA statement"
}
```

### Backend Behavior (AdminPaymentController::verifySubmission)
1. **Validate submission** exists and status = PENDING_REVIEW
2. **Idempotency check** (receipt not already processed)
3. **Create BookingTransaction record** (LEDGER ENTRY)
   - type = CREDIT
   - amount = submission amount
   - reference = receipt number
   - posted_at = now()
4. **Recalculate booking** from ledger
5. **Update booking:**
   - amount_paid = sum of CREDIT transactions
   - amount_due = total_amount - amount_paid
   - status = PAID or PARTIALLY_PAID
6. **Update payment_intent** status = SUCCEEDED
7. **Update mpesa_manual_submission**
   - status = VERIFIED
   - reviewed_at = now()
   - reviewed_by = auth()->id()
8. **Generate Receipt** record
9. **Queue ReceiptEmail**
10. **Log** booking_manual_payment_verified audit

### Response (200 OK)
```json
{
  "success": true,
  "message": "Payment verified and processed",
  "data": {
    "booking_id": 1,
    "status": "PAID",
    "amount_paid": 45000.00,
    "receipt_number": "RCP-BK202601251A3K9",
    "transaction_id": 1
  }
}
```

### Admin Endpoint - Reject Payment
```
POST /admin/payment/manual-submissions/{submissionId}/reject
Content-Type: application/json
```

### Request Body
```json
{
  "rejection_reason": "Receipt number not found in M-PESA system"
}
```

### Backend Behavior
1. **Update mpesa_manual_submission**
   - status = REJECTED
   - rejection_reason = request reason
2. **Update payment_intent** status = FAILED
3. **Keep booking** in PENDING_PAYMENT
4. **Notify guest** of rejection (queue email)
5. **Log** booking_manual_payment_rejected audit

---

## DATA MODELS & RELATIONSHIPS

### Booking
```
- id (PK)
- booking_ref: STRING (generated at summary stage, unique)
- property_id: FK
- guest_id: FK
- check_in, check_out: DATE
- adults, children: INT
- nights: INT (calculated)
- status: ENUM(DRAFT, PENDING_PAYMENT, PARTIALLY_PAID, PAID, CANCELLED)
- currency: CHAR(3) = KES
- nightly_rate: DECIMAL(12,2)
- accommodation_subtotal: DECIMAL(12,2)
- addons_subtotal: DECIMAL(12,2)
- total_amount: DECIMAL(12,2)
- amount_paid: DECIMAL(12,2)
- amount_due: DECIMAL(12,2)
- minimum_deposit: DECIMAL(12,2)
- created_at, updated_at: TIMESTAMP
```

### PaymentIntent
```
- id (PK)
- booking_id: FK
- intent_ref: STRING (unique, PI{timestamp}{6-char random})
- method: ENUM(MPESA_STK, MPESA_MANUAL)
- amount: DECIMAL(12,2) (what we expect to receive)
- currency: CHAR(3) = KES
- status: ENUM(INITIATED, PENDING, UNDER_REVIEW, SUCCEEDED, FAILED, CANCELLED)
- created_by: STRING
- created_at, updated_at: TIMESTAMP
```

### BookingTransaction (LEDGER - SOURCE OF TRUTH)
```
- id (PK)
- booking_id: FK
- payment_intent_id: FK
- type: ENUM(CREDIT, DEBIT)
- amount: DECIMAL(12,2)
- reference: STRING (receipt number, transaction ID)
- description: TEXT
- meta: JSON (additional data)
- posted_at: TIMESTAMP (when transaction occurred)
- created_at: TIMESTAMP (when recorded)
- No timestamps field (immutable)
```

### MpesaStkRequest
```
- id (PK)
- payment_intent_id: FK
- phone_e164: STRING
- checkout_request_id: STRING (unique)
- merchant_request_id: STRING
- status: ENUM(SENT, COMPLETED, EXPIRED, TIMEOUT)
- request_payload: JSON
- response_payload: JSON
- created_at, updated_at: TIMESTAMP
```

### MpesaStkCallback
```
- id (PK)
- stk_request_id: FK
- result_code: INT (0 = success)
- result_desc: TEXT
- mpesa_receipt_number: STRING
- transaction_date: DATETIME
- amount: DECIMAL(12,2)
- phone_e164: STRING
- raw_payload: JSON
- received_at: TIMESTAMP
```

### MpesaManualSubmission
```
- id (PK)
- payment_intent_id: FK
- mpesa_receipt_number: STRING (unique)
- amount: DECIMAL(12,2)
- phone_e164: STRING
- status: ENUM(PENDING_REVIEW, VERIFIED, REJECTED)
- submitted_at: TIMESTAMP
- reviewed_at: TIMESTAMP (nullable)
- reviewed_by: FK users (nullable)
- rejection_reason: TEXT (nullable)
- raw_notes: TEXT (nullable)
```

### Receipt
```
- id (PK)
- booking_id: FK
- receipt_number: STRING (unique, RCP-{booking_ref})
- amount_paid: DECIMAL(12,2)
- transaction_date: DATETIME
- issued_at: TIMESTAMP
- file_path: STRING (nullable, PDF)
```

### AuditLog
```
- id (PK)
- action: STRING
- description: TEXT
- booking_id: FK (nullable)
- guest_id: FK (nullable)
- user_id: FK (nullable)
- ip_address: STRING
- user_agent: TEXT
- meta: JSON
- timestamp: TIMESTAMP
```

---

## CRITICAL RULES ENFORCED

### 1. Ledger Invariant
- **BookingTransaction is immutable source of truth**
- Booking amount_paid = SUM(BookingTransaction.amount WHERE type=CREDIT)
- No direct booking.amount_paid updates
- Every payment creates ONE ledger entry

### 2. Idempotency
- Receipt numbers must be unique globally
- Duplicate receipt = error (not replayed)
- Payment intent created only once per booking+amount combo (5-min window)

### 3. Status Machine
```
DRAFT → PENDING_PAYMENT → PARTIALLY_PAID → PAID → COMPLETE
             ↓
          (manual entry) → UNDER_REVIEW → PAID
             ↓
          (failure) → retry
```

### 4. No Partial Updates
- All state changes wrapped in DB::transaction
- All-or-nothing semantics
- Booking + Ledger + Receipt created atomically

### 5. Authorization & Validation
- Guest/unauthenticated: create reservation, enter payment
- Admin: verify manual payments, view audit logs
- All input validated via Form Requests
- All errors logged to Laravel logs + audit_logs

---

## ROUTES SUMMARY

### Public Routes (No Auth)
```
POST   /bookings                              → Create DRAFT booking
GET    /bookings/{id}/summary                 → Get summary (generates ref)
PATCH  /bookings/{id}/confirm                 → Confirm & lock for payment
GET    /payment/booking/{id}                  → Show payment page
POST   /payment/intents                       → Create payment intent
POST   /payment/mpesa/stk                     → Initiate STK push
POST   /payment/mpesa/callback                → M-PESA callback endpoint
POST   /payment/manual-entry                  → Submit manual receipt
GET    /payment/receipts/{receiptNo}          → Get receipt
```

### Admin Routes (Auth Required)
```
GET    /admin/payment/verification-dashboard  → Dashboard
GET    /admin/payment/manual-submissions/pending → List pending
POST   /admin/payment/manual-submissions/{id}/verify → Approve
POST   /admin/payment/manual-submissions/{id}/reject → Reject
```

---

## TESTING THE FLOW

### Scenario 1: Successful STK Payment
```bash
1. POST /bookings → booking_id=1, status=DRAFT
2. GET /bookings/1/summary → booking_ref=BK202601251A3K9
3. PATCH /bookings/1/confirm → status=PENDING_PAYMENT
4. POST /payment/mpesa/stk → STK sent to +254701123456
5. User enters PIN → M-PESA sends callback
6. Callback received → BookingTransaction created → status=PAID
7. Receipt generated, email queued
```

### Scenario 2: STK Fails, Manual Submission
```bash
1. POST /bookings → booking_id=1, status=DRAFT
2. GET /bookings/1/summary → booking_ref=BK202601251A3K9
3. PATCH /bookings/1/confirm → status=PENDING_PAYMENT
4. POST /payment/mpesa/stk → STK sent
5. [timeout or cancel] → STK fails
6. POST /payment/manual-entry → submission_id=1, status=PENDING_REVIEW
7. [Admin verifies] POST /admin/payment/manual-submissions/1/verify
8. BookingTransaction created → status=PAID
9. Receipt generated, email queued
```

---

## SUMMARY

✅ **Booking creation** with DRAFT status
✅ **Confirmation** with booking reference generation
✅ **Payment locking** (PENDING_PAYMENT status)
✅ **STK integration** using existing M-PESA service
✅ **Ledger-based payment** tracking (immutable)
✅ **Manual fallback** for STK failures
✅ **Admin verification** workflow
✅ **Receipt generation** + email notifications
✅ **Comprehensive audit logging** of all actions
✅ **Form Request validation** on all endpoints
✅ **Transaction safety** with DB::transaction
✅ **Idempotency** checks on all payment operations

The system is **production-ready** and strictly follows Laravel best practices.
