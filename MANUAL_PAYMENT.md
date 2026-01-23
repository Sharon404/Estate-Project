# Manual M-PESA Payment Entry & Verification

## Overview

When STK Push fails or times out, guests can submit M-PESA receipts manually for admin verification. This creates an audit trail and prevents processing without proper verification.

## Flow

```
┌─────────────────────────────────────────────────────────┐
│ 1. GUEST INITIATES STK PUSH                             │
│    POST /payment/mpesa/stk                              │
└─────────────────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────────────────┐
│ 2a. STK SUCCEEDS → Callback received                    │
│    Normal flow (see MPESA_INTEGRATION.md)               │
└─────────────────────────────────────────────────────────┘
              OR
┌─────────────────────────────────────────────────────────┐
│ 2b. STK TIMES OUT / FAILS                               │
│    Guest sees: "Payment timed out"                      │
└─────────────────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────────────────┐
│ 3. GUEST SUBMITS MANUAL ENTRY                           │
│    POST /payment/manual-entry                           │
│    {                                                    │
│      "payment_intent_id": 1,                           │
│      "mpesa_receipt_number": "LIK123ABC456",           │
│      "amount": 5000,                                   │
│      "phone_e164": "+254712345678",                    │
│      "notes": "STK timed out"                          │
│    }                                                   │
│    ↓                                                   │
│    MpesaManualSubmission (SUBMITTED)                   │
└─────────────────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────────────────┐
│ 4. ADMIN REVIEWS SUBMISSION                             │
│    GET /admin/payment/manual-submissions/pending        │
│    GET /admin/payment/manual-submissions/{id}           │
│    ↓ Verify against M-PESA statement                    │
└─────────────────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────────────────┐
│ 5a. ADMIN APPROVES ✓                                    │
│    POST /admin/payment/manual-submissions/{id}/verify   │
│    ↓                                                   │
│    1. Create BookingTransaction (ledger)              │
│    2. Update PaymentIntent → SUCCEEDED                │
│    3. Update Booking amounts + status                 │
│    4. Mark submission → VERIFIED                      │
└─────────────────────────────────────────────────────────┘
              OR
┌─────────────────────────────────────────────────────────┐
│ 5b. ADMIN REJECTS ✗                                     │
│    POST /admin/payment/manual-submissions/{id}/reject   │
│    {                                                   │
│      "reason": "Receipt not found in statement"       │
│    }                                                   │
│    ↓                                                   │
│    Mark submission → REJECTED                         │
│    Guest can resubmit or try STK again               │
└─────────────────────────────────────────────────────────┘
```

## Database

### mpesa_manual_submissions Table

```sql
┌─────────────────────────────────────────┐
│ mpesa_manual_submissions                │
├─────────────────────────────────────────┤
│ id                          | int       │
│ payment_intent_id           | bigint    │
│ mpesa_receipt_number        | varchar   │ ← UNIQUE
│ phone_e164                  | varchar   │
│ amount                      | decimal   │
│ status                      | enum      │ ← SUBMITTED|VERIFIED|REJECTED
│ raw_notes                   | text      │
│ submitted_by_guest          | boolean   │
│ submitted_at                | timestamp │
│ reviewed_at                 | timestamp │
└─────────────────────────────────────────┘
```

### States

- **SUBMITTED**: Guest submitted, awaiting admin verification
- **VERIFIED**: Admin verified, payment processed
- **REJECTED**: Admin rejected, guest can resubmit

## API Endpoints

### Guest: Submit Manual Payment

```
POST /payment/manual-entry

Request:
{
  "payment_intent_id": 1,
  "mpesa_receipt_number": "LIK123ABC456",
  "amount": 5000.00,
  "phone_e164": "+254712345678",
  "notes": "STK timed out, received payment from M-PESA"
}

Response (201 Created):
{
  "success": true,
  "message": "Manual payment submitted for verification",
  "submission_id": 1,
  "receipt_number": "LIK123ABC456",
  "amount": 5000.00,
  "status": "SUBMITTED",
  "next_step": "Admin will verify within 24 hours. You will receive a confirmation email.",
  "submitted_at": "2026-01-23T14:30:00Z"
}

Errors:
- 400: Receipt already submitted or processed
- 400: Amount exceeds amount due
- 400: Invalid receipt format (must be 9-20 alphanumeric)
- 400: Payment intent in wrong status
```

### Admin: Get Pending Submissions

```
GET /admin/payment/manual-submissions/pending

Response:
{
  "success": true,
  "data": {
    "total_pending": 3,
    "submissions": [
      {
        "id": 1,
        "receipt_number": "LIK123ABC456",
        "amount": 5000.00,
        "phone_e164": "+254712345678",
        "booking_ref": "GS-2026-001",
        "guest_name": "John Doe",
        "guest_email": "john@example.com",
        "notes": "STK timed out",
        "submitted_at": "2026-01-23T14:30:00Z"
      }
    ]
  }
}
```

### Admin: Get Submission Details

```
GET /admin/payment/manual-submissions/{submissionId}

Response:
{
  "success": true,
  "data": {
    "submission_id": 1,
    "receipt_number": "LIK123ABC456",
    "amount": 5000.00,
    "phone_e164": "+254712345678",
    "status": "SUBMITTED",
    "submitted_at": "2026-01-23T14:30:00Z",
    "reviewed_at": null,
    "notes": "STK timed out",
    "payment_intent": {
      "id": 1,
      "status": "PENDING",
      "amount": 5000.00,
      "currency": "KES"
    },
    "booking": {
      "id": 1,
      "booking_ref": "GS-2026-001",
      "status": "PENDING_PAYMENT",
      "total_amount": 15000.00,
      "amount_paid": 0.00,
      "amount_due": 15000.00,
      "check_in": "2026-02-01",
      "check_out": "2026-02-05",
      "nights": 4,
      "property_name": "Executive Suite"
    },
    "guest": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+254712345678"
    }
  }
}
```

### Admin: Verify Manual Payment

```
POST /admin/payment/manual-submissions/{submissionId}/verify

Request:
{
  "verified_notes": "Receipt verified against M-PESA statement"
}

Response:
{
  "success": true,
  "message": "Manual payment verified successfully",
  "data": {
    "success": true,
    "message": "Manual payment verified successfully",
    "transaction_id": 15,
    "receipt_number": "LIK123ABC456",
    "amount": 5000.00,
    "booking_ref": "GS-2026-001",
    "booking_status": "PARTIALLY_PAID",
    "amount_paid": 5000.00,
    "amount_due": 10000.00,
    "verified_at": "2026-01-23T14:35:00Z"
  }
}

What happens:
1. BookingTransaction created (ledger entry)
   - source: MANUAL_ENTRY
   - external_ref: LIK123ABC456
2. PaymentIntent status → SUCCEEDED
3. Booking amounts calculated from ledger
   - amount_paid: 5000.00
   - amount_due: 10000.00
   - status: PARTIALLY_PAID
4. MpesaManualSubmission → VERIFIED
```

### Admin: Reject Manual Payment

```
POST /admin/payment/manual-submissions/{submissionId}/reject

Request:
{
  "reason": "Receipt number does not match M-PESA statement"
}

Response:
{
  "success": true,
  "message": "Manual submission rejected",
  "data": {
    "success": true,
    "message": "Manual submission rejected",
    "submission_id": 1,
    "receipt_number": "LIK123ABC456",
    "reason": "Receipt number does not match M-PESA statement",
    "next_step": "Guest can resubmit with correct receipt or try STK Push again"
  }
}

What happens:
1. MpesaManualSubmission → REJECTED
2. No ledger entries created
3. PaymentIntent status stays unchanged
4. Booking status unchanged
5. Guest can resubmit or retry STK
```

### Admin: Get Payment Statistics

```
GET /admin/payment/statistics

Response:
{
  "success": true,
  "data": {
    "submissions": {
      "pending_count": 2,
      "pending_amount": 10000.00,
      "verified_count": 15,
      "verified_amount": 125000.00,
      "rejected_count": 3
    }
  }
}
```

## Validation Rules

### Receipt Number
- **Format**: 9-20 uppercase alphanumeric characters
- **Examples**: `LIK123ABC456`, `LIK1234567890`, `ABC123456789`
- **Must be UNIQUE**: Can't submit same receipt twice
- **Can't exist in ledger**: Receipt already processed as callback

### Phone Number
- **Format**: E.164 (optional but recommended)
- **Example**: `+254712345678`
- **Validation**: `^\+254\d{9}$`

### Amount
- **Min**: 1
- **Max**: 999999.99
- **Max per booking**: Can't exceed `booking.amount_due`

## NON-NEGOTIABLE SEQUENCE (On Verify)

When admin verifies a manual submission, the following MUST happen in order:

```php
// 1. Store everything in a transaction
DB::transaction(function () {
    // Step 1: Create BookingTransaction (ledger entry)
    // - This is the ONLY way to add to amount_paid
    BookingTransaction::create([
        'booking_id' => ...,
        'payment_intent_id' => ...,
        'type' => 'PAYMENT',
        'source' => 'MANUAL_ENTRY',
        'external_ref' => $receipt_number, // idempotency key
        'amount' => ...,
        'currency' => ...,
        'meta' => [...]
    ]);

    // Step 2: Update PaymentIntent status
    PaymentIntent::update(['status' => 'SUCCEEDED']);

    // Step 3: Calculate booking amounts FROM LEDGER
    $totalPaid = BookingTransaction::where('booking_id', ...) 
                    ->where('type', 'PAYMENT')
                    ->sum('amount');
    $amountDue = $booking->total_amount - $totalPaid;

    // Step 4: Update Booking with calculated values
    Booking::update([
        'amount_paid' => $totalPaid,
        'amount_due' => max(0, $amountDue),
        'status' => $amountDue <= 0 ? 'PAID' : 'PARTIALLY_PAID'
    ]);

    // Step 5: Update submission status
    MpesaManualSubmission::update([
        'status' => 'VERIFIED',
        'reviewed_at' => now()
    ]);
});
```

## Security Considerations

### Idempotency
- Each M-PESA receipt number is UNIQUE
- Cannot submit same receipt twice
- Prevents duplicate payments

### Verification Required
- Manual payments MUST be verified by admin
- No auto-processing
- Admin reviews against actual M-PESA statement

### Immutable Ledger
- BookingTransaction is append-only
- Never deleted or modified
- Booking amounts calculated FROM ledger
- Provides audit trail for disputes

### Authorization
- Manual submission: Public (guests can submit)
- Admin verification: Requires `auth` middleware
- Admin routes can be further restricted to admin roles

## Testing

### Manual Submit (Guest)

```bash
curl -X POST http://localhost:8000/payment/manual-entry \
  -H "Content-Type: application/json" \
  -d '{
    "payment_intent_id": 1,
    "mpesa_receipt_number": "LIK123ABC456",
    "amount": 5000.00,
    "phone_e164": "+254712345678",
    "notes": "STK timed out"
  }'
```

### Get Pending (Admin)

```bash
curl http://localhost:8000/admin/payment/manual-submissions/pending \
  -H "Authorization: Bearer <token>"
```

### Verify (Admin)

```bash
curl -X POST http://localhost:8000/admin/payment/manual-submissions/1/verify \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "verified_notes": "Verified against statement"
  }'
```

### Reject (Admin)

```bash
curl -X POST http://localhost:8000/admin/payment/manual-submissions/1/reject \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "reason": "Receipt not found in statement"
  }'
```

## Common Scenarios

### Scenario 1: Guest's STK times out, manual receipt submitted

```
1. Guest calls POST /payment/manual-entry with receipt
   → MpesaManualSubmission (SUBMITTED)
   
2. Admin gets pending submissions
   → Sees guest's manual submission
   
3. Admin verifies receipt against statement
   → POST /admin/payment/manual-submissions/1/verify
   → BookingTransaction created
   → PaymentIntent → SUCCEEDED
   → Booking amounts updated
   → Submission → VERIFIED
   
4. Guest gets email: "Payment verified, booking confirmed"
```

### Scenario 2: Receipt number doesn't match statement

```
1. Guest submits receipt (typo)
   → MpesaManualSubmission (SUBMITTED)
   
2. Admin can't find receipt in statement
   → POST /admin/payment/manual-submissions/1/reject
   → Reason: "Receipt LIK123ABC456 not found in statement"
   → Submission → REJECTED
   
3. Guest gets email: "Payment could not be verified, please try again"
   
4. Guest can resubmit with correct receipt or retry STK
```

### Scenario 3: Guest submits duplicate receipt

```
1. Guest submits: LIK123ABC456
   → MpesaManualSubmission (SUBMITTED)

2. Guest submits: LIK123ABC456 again
   → Error: "Receipt 'LIK123ABC456' has already been submitted"
   → Prevents duplicate processing
```

## Integration with Frontend

### Show fallback option on STK timeout:

```javascript
// After STK polling times out
const stkResult = await pollStkStatus(stkRequestId);

if (!stkResult.has_callback) {
  // STK timed out, show manual entry form
  showManualEntryForm({
    paymentIntentId: paymentIntentId,
    amount: amount,
    bookingRef: bookingRef
  });
}

// Manual entry form submission
async function submitManualPayment(data) {
  const response = await fetch('/payment/manual-entry', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      payment_intent_id: data.paymentIntentId,
      mpesa_receipt_number: data.receipt,
      amount: data.amount,
      phone_e164: data.phone,
      notes: 'Submitted manually due to STK timeout'
    })
  });
  
  const result = await response.json();
  
  if (result.success) {
    showMessage('Payment submitted for verification. Check email for updates.');
  } else {
    showError(result.error);
  }
}
```

## Debugging

### View pending submissions:

```sql
SELECT id, mpesa_receipt_number, amount, status, submitted_at
FROM mpesa_manual_submissions
WHERE status = 'SUBMITTED'
ORDER BY submitted_at ASC;
```

### View verification history:

```sql
SELECT ms.mpesa_receipt_number, ms.amount, ms.status, 
       ms.submitted_at, ms.reviewed_at,
       bt.id as transaction_id, b.booking_ref
FROM mpesa_manual_submissions ms
LEFT JOIN booking_transactions bt ON ms.id = bt.meta->>'manual_submission_id'
LEFT JOIN payment_intents pi ON ms.payment_intent_id = pi.id
LEFT JOIN bookings b ON pi.booking_id = b.id
ORDER BY ms.submitted_at DESC;
```

### Check duplicate submission attempts:

```sql
SELECT mpesa_receipt_number, COUNT(*) as count
FROM mpesa_manual_submissions
GROUP BY mpesa_receipt_number
HAVING COUNT(*) > 1;
```

### View all transactions for a booking:

```sql
SELECT * FROM booking_transactions 
WHERE booking_id = 1
ORDER BY created_at DESC;
```

## Next Steps

1. **Frontend**: Add manual entry form as fallback for STK timeout
2. **Email Notifications**: Send confirmation when manual payment verified/rejected
3. **Analytics**: Track STK timeout rate and manual entry volume
4. **Reconciliation**: Regular admin review of manual submissions against M-PESA statements
5. **Webhooks**: Optionally notify external systems when manual payment verified
