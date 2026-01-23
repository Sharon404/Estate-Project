# Receipt Generation System Documentation

## Overview

The Receipt Generation System captures immutable snapshots of payment transactions. Every successful payment (STK or manual) automatically generates a receipt with a system-generated number and comprehensive JSON data snapshot.

## Key Features

### 1. Receipt Generation
- **Automatic**: Receipts are created automatically when payments succeed
- **System-Generated Numbers**: Format `RCP-YYYY-XXXXX` (e.g., `RCP-2026-00001`)
- **Sequential**: Receipt numbers increment daily (reset yearly)
- **Immutable**: Receipt data is stored once and never modified
- **One-to-One**: One receipt per successful payment (idempotency enforced)

### 2. Data Capture
Receipts capture a comprehensive JSON snapshot at generation time:

```json
{
  "receipt_info": {
    "type": "STK_PUSH or MANUAL_ENTRY",
    "generated_at": "2026-01-22T14:30:00Z",
    "issued_by_system": true
  },
  "payment_info": {
    "amount": 5000.00,
    "currency": "KES",
    "payment_method": "STK_PUSH or MANUAL_ENTRY",
    "mpesa_receipt_number": "LIK123ABC456",
    "booking_transaction_id": 15,
    "payment_intent_id": 1
  },
  "booking_info": {
    "id": 1,
    "booking_ref": "GS-2026-001",
    "status": "PARTIALLY_PAID",
    "check_in": "2026-02-01",
    "check_out": "2026-02-05",
    "nights": 4,
    "total_amount": 15000.00,
    "amount_paid_before": 0.00,
    "amount_paid_after": 5000.00,
    "remaining_balance": 10000.00
  },
  "guest_info": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+254712345678"
  },
  "property_info": {
    "id": 1,
    "name": "Beachfront Villa",
    "location": "Diani Beach",
    "room_type": "Deluxe Villa"
  },
  "meta": {
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "server_timezone": "Africa/Nairobi",
    "generated_timestamp": 1674408600
  }
}
```

### 3. Linkage
Receipts are linked to:
- **Booking**: Via `booking_id` foreign key
- **PaymentIntent**: Via `payment_intent_id` foreign key
- **M-PESA**: Via `mpesa_receipt_number` (null for manual payments without M-PESA receipt)
- **Transaction**: Referenced in `receipt_data['payment_info']['booking_transaction_id']`

## Database Schema

```sql
CREATE TABLE receipts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    payment_intent_id BIGINT NOT NULL,
    receipt_no VARCHAR(30) UNIQUE NOT NULL,
    mpesa_receipt_number VARCHAR(20) NULLABLE,
    amount DECIMAL(12, 2) NOT NULL,
    currency CHAR(3) DEFAULT 'KES',
    receipt_data JSON NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE RESTRICT,
    FOREIGN KEY (payment_intent_id) REFERENCES payment_intents(id) ON DELETE RESTRICT,
    INDEX (booking_id),
    INDEX (payment_intent_id),
    INDEX (receipt_no)
);
```

## Receipt Creation Flow

### For STK Push Payments

```
1. M-PESA Callback Received
   ↓
2. Validate Callback (idempotency check)
   ↓
3. Create BookingTransaction (ledger entry)
   ↓
4. Update PaymentIntent (status → SUCCEEDED)
   ↓
5. Update Booking (amounts, status)
   ↓
6. CREATE RECEIPT ← ReceiptService::createStkReceipt()
   ├─ Generate receipt number (RCP-YYYY-XXXXX)
   ├─ Build JSON snapshot (all payment/booking details)
   ├─ Store in receipts table
   └─ Return receipt_id + receipt_no
   ↓
7. Return response with receipt details
```

### For Manual Payment Submissions

```
1. Guest submits M-PESA receipt & amount
   ↓
2. Validate & store MpesaManualSubmission (status → SUBMITTED)
   ↓
3. Admin reviews submission
   ↓
4. Admin clicks "Verify Payment"
   ↓
5. Create BookingTransaction (ledger entry)
   ↓
6. Update PaymentIntent (status → SUCCEEDED)
   ↓
7. Update Booking (amounts, status)
   ↓
8. Update MpesaManualSubmission (status → VERIFIED)
   ↓
9. CREATE RECEIPT ← ReceiptService::createManualReceipt()
   ├─ Generate receipt number (RCP-YYYY-XXXXX)
   ├─ Build JSON snapshot (includes manual_entry flag)
   ├─ Store in receipts table
   └─ Return receipt_id + receipt_no
   ↓
10. Return response with receipt details
```

## ReceiptService API

### Generating Receipt Numbers

```php
$receiptService = new ReceiptService();
$receiptNo = ReceiptService::generateReceiptNumber();
// Returns: "RCP-2026-00001" for first receipt of year 2026
```

**Implementation Details:**
- Queries the last receipt for current year
- Extracts last 5 digits (sequential number)
- Increments by 1
- Pads with leading zeros to 5 digits
- Prefix: `RCP-YYYY-` where YYYY is current year

### Creating STK Receipt

```php
$receiptService = new ReceiptService();
$receipt = $receiptService->createStkReceipt(
    BookingTransaction $transaction,
    ?string $mpesaReceiptNumber = null
);

// Returns Receipt model with:
// - receipt_no: "RCP-2026-00001"
// - amount: 5000.00
// - currency: "KES"
// - receipt_data: JSON snapshot
// - issued_at: Current timestamp
```

**When to use:** After M-PESA callback confirms payment

### Creating Manual Receipt

```php
$receiptService = new ReceiptService();
$receipt = $receiptService->createManualReceipt(
    BookingTransaction $transaction,
    ?string $mpesaReceiptNumber = null
);

// Same as createStkReceipt but receipt_data['receipt_info']['type'] = 'MANUAL_ENTRY'
```

**When to use:** When admin verifies manual payment submission

### Retrieving Receipts

#### Get by Receipt Number
```php
$receiptService = new ReceiptService();
$receipt = $receiptService->getReceiptByNumber('RCP-2026-00001');

// Returns Receipt model or null
```

#### Get All for Booking
```php
$receiptService = new ReceiptService();
$receipts = $receiptService->getBookingReceipts($booking);

// Returns Collection of Receipt models for booking
```

#### Get Full Receipt Details
```php
$receiptService = new ReceiptService();
$details = $receiptService->getReceiptDetails($receipt);

// Returns array with:
// {
//   "receipt": { ...receipt model data... },
//   "data": { ...receipt_data JSON... }
// }
```

### Idempotency Checking

```php
$receiptService = new ReceiptService();
$exists = $receiptService->receiptExists($transaction);

// Returns true if receipt already exists for this transaction
// Uses booking_transaction_id in receipt_data to check
```

**Purpose:** Prevents duplicate receipts if receipt creation is retried

## API Endpoints

### Get Receipt by Number

**Endpoint:** `GET /payment/receipts/{receiptNo}`

**Response (Success - 200):**
```json
{
  "success": true,
  "data": {
    "receipt": {
      "id": 1,
      "booking_id": 1,
      "payment_intent_id": 1,
      "receipt_no": "RCP-2026-00001",
      "mpesa_receipt_number": "LIK123ABC456",
      "amount": "5000.00",
      "currency": "KES",
      "issued_at": "2026-01-22T14:30:00Z"
    },
    "data": {
      "receipt_info": { ... },
      "payment_info": { ... },
      "booking_info": { ... },
      "guest_info": { ... },
      "property_info": { ... },
      "meta": { ... }
    }
  }
}
```

**Response (Not Found - 404):**
```json
{
  "success": false,
  "message": "Receipt not found"
}
```

### List Booking Receipts

**Endpoint:** `GET /payment/bookings/{bookingId}/receipts`

**Response (Success - 200):**
```json
{
  "success": true,
  "booking_ref": "GS-2026-001",
  "receipt_count": 2,
  "data": [
    {
      "receipt_id": 1,
      "receipt_no": "RCP-2026-00001",
      "amount": 5000.00,
      "currency": "KES",
      "mpesa_receipt_number": "LIK123ABC456",
      "issued_at": "2026-01-22T14:30:00Z",
      "payment_method": "STK_PUSH"
    },
    {
      "receipt_id": 2,
      "receipt_no": "RCP-2026-00002",
      "amount": 10000.00,
      "currency": "KES",
      "mpesa_receipt_number": null,
      "issued_at": "2026-01-23T10:15:00Z",
      "payment_method": "MANUAL_ENTRY"
    }
  ]
}
```

### Get Specific Receipt for Booking

**Endpoint:** `GET /payment/bookings/{bookingId}/receipts/{receiptNo}`

**Response (Success - 200):**
```json
{
  "success": true,
  "data": {
    "receipt": { ... },
    "data": { ... }
  }
}
```

**Response (Not Found - 404):**
```json
{
  "success": false,
  "message": "Receipt not found for this booking"
}
```

## Integration Points

### 1. MpesaCallbackService
**File:** `app/Services/MpesaCallbackService.php`

**Method:** `handleSuccessfulPayment()`

**Receipt Creation Code:**
```php
// After booking update (line ~143)
$receiptService = new ReceiptService();
$receipt = $receiptService->createStkReceipt(
    $transaction,
    $callback->mpesa_receipt_number
);

// Response includes receipt details
return [
    'success' => true,
    'message' => 'Payment processed successfully',
    'receipt_id' => $receipt->id,
    'receipt_no' => $receipt->receipt_no,
    // ... other response fields
];
```

### 2. PaymentService
**File:** `app/Services/PaymentService.php`

**Method:** `verifyManualPayment()`

**Receipt Creation Code:**
```php
// After booking update (line ~405)
$receiptService = new ReceiptService();
$receipt = $receiptService->createManualReceipt(
    $transaction,
    $submission->mpesa_receipt_number
);

// Response includes receipt details
return [
    'success' => true,
    'message' => 'Manual payment verified successfully',
    'receipt_id' => $receipt->id,
    'receipt_no' => $receipt->receipt_no,
    // ... other response fields
];
```

## Example Usage

### Complete Payment Flow with Receipt

#### 1. STK Push Payment
```bash
# 1. Create payment intent
POST /payment/intents
{
  "booking_id": 1,
  "amount": 5000
}
# Response: payment_intent_id = 1

# 2. Initiate STK
POST /payment/mpesa/stk
{
  "payment_intent_id": 1
}
# User enters M-PESA PIN, M-PESA sends callback

# 3. Callback processed automatically
# System creates: Transaction → Updates Intent → Updates Booking → Creates Receipt

# 4. Retrieve receipt
GET /payment/receipts/RCP-2026-00001
# Full receipt with all details
```

#### 2. Manual Payment
```bash
# 1. Create payment intent
POST /payment/intents
{
  "booking_id": 1,
  "amount": 5000
}
# Response: payment_intent_id = 1

# 2. Guest submits manual payment (STK failed)
POST /payment/manual-entry
{
  "payment_intent_id": 1,
  "mpesa_receipt_number": "LIK123ABC456",
  "amount": 5000,
  "phone_e164": "+254712345678"
}
# Response: submission_id = 1, status = SUBMITTED

# 3. Admin verifies (admin portal)
POST /admin/payment/manual-submissions/1/verify
{
  "verified_by": "admin",
  "verification_notes": "Receipt verified via M-PESA"
}
# System creates: Transaction → Updates Intent → Updates Booking → Creates Receipt

# 4. Guest retrieves receipt
GET /payment/receipts/RCP-2026-00001
# Full receipt with manual entry flag
```

### Retrieve Booking Payment History

```bash
# Get all receipts for booking
GET /payment/bookings/1/receipts

# Response includes:
# - All payment receipts with amounts and dates
# - Payment methods (STK vs Manual)
# - Total paid so far

# Get specific receipt details
GET /payment/bookings/1/receipts/RCP-2026-00001
# Full snapshot with before/after amounts
```

## Key Design Decisions

### 1. Sequential Number Generation
- **Why:** Human-readable, easy to reference in support tickets
- **Format:** RCP-YYYY-XXXXX ensures year clarity and prevents duplicates
- **Reset:** Resets yearly (RCP-2027-00001 on Jan 1, 2027)

### 2. JSON Snapshot
- **Why:** Immutable historical record - captures exact state at payment time
- **What's Included:**
  - Payment details (amount, method, M-PESA receipt)
  - Booking state (total cost, check-in/out, nights)
  - Amounts (before payment, after payment, remaining)
  - Guest info (for receipts shown to guests)
  - Server metadata (timezone, IP address, user agent)

### 3. One Receipt Per Payment
- **Why:** Clear reconciliation - one payment transaction = one receipt
- **Enforcement:** `receiptExists()` checks booking_transaction_id in receipt_data
- **Idempotency:** If receipt creation is retried, it returns existing receipt

### 4. Automatic Generation
- **Why:** No manual intervention needed, ensures all payments have receipts
- **Timing:** After booking is updated (ensures state is final)
- **Both Flows:** STK and Manual both trigger receipt creation

## Testing

### Unit Tests
```php
// Test sequential number generation
test('receipt_number_generation_increments', function () {
    $receipt1 = ReceiptService::generateReceiptNumber();
    $receipt2 = ReceiptService::generateReceiptNumber();
    
    expect($receipt1)->toBe('RCP-2026-00001');
    expect($receipt2)->toBe('RCP-2026-00002');
});

// Test idempotency
test('duplicate_receipt_not_created', function () {
    $transaction = BookingTransaction::factory()->create();
    
    $receipt1 = $receiptService->createStkReceipt($transaction, 'LIK123');
    $receipt2 = $receiptService->createStkReceipt($transaction, 'LIK123');
    
    expect($receipt1->id)->toBe($receipt2->id);
    expect(Receipt::count())->toBe(1);
});

// Test snapshot completeness
test('receipt_snapshot_captures_all_data', function () {
    $transaction = BookingTransaction::factory()->create();
    $receipt = $receiptService->createStkReceipt($transaction, 'LIK123');
    
    $data = $receipt->receipt_data;
    expect($data)->toHaveKey('receipt_info');
    expect($data)->toHaveKey('payment_info');
    expect($data)->toHaveKey('booking_info');
    expect($data)->toHaveKey('guest_info');
    expect($data)->toHaveKey('property_info');
    expect($data)->toHaveKey('meta');
});
```

### Integration Tests
```php
// Test STK payment creates receipt
test('stk_payment_creates_receipt', function () {
    $paymentIntent = PaymentIntent::factory()->create();
    
    // Simulate M-PESA callback
    $callback = MpesaStkCallback::factory()->create([
        'mpesa_receipt_number' => 'LIK123ABC456'
    ]);
    
    // Process callback
    $response = $mpesaCallbackService->handleSuccessfulPayment($callback);
    
    expect($response['receipt_no'])->toBe('RCP-2026-00001');
    expect(Receipt::count())->toBe(1);
});

// Test manual payment creates receipt
test('manual_payment_verification_creates_receipt', function () {
    $submission = MpesaManualSubmission::factory()->create();
    
    // Verify payment
    $response = $paymentService->verifyManualPayment($submission);
    
    expect($response['receipt_no'])->toBe('RCP-2026-00001');
    expect(Receipt::count())->toBe(1);
});
```

## Troubleshooting

### Receipt Not Created
1. Check if payment succeeded (check PaymentIntent status)
2. Check if BookingTransaction exists (payment must be in ledger)
3. Check application logs for receipt creation errors
4. Verify ReceiptService is imported in integration points

### Duplicate Receipt Created
1. Verify idempotency check: `receiptExists($transaction)`
2. Check if retry logic caused multiple calls to `createStkReceipt()`
3. Query receipts table for booking_transaction_id in receipt_data

### Receipt Number Not Sequential
1. Check database for corrupted receipt_no values
2. Verify `generateReceiptNumber()` is querying last receipt correctly
3. Check for concurrent requests (race condition)

### JSON Snapshot Incomplete
1. Verify all related models exist (Booking, Guest, Property, PaymentIntent)
2. Check for soft-deleted records (exclude from snapshot)
3. Verify database transactions (snapshot must use final state)

## Security Considerations

### 1. Receipt Access Control
- **Current:** Receipts are publicly accessible by receipt number
- **Future Enhancement:** Add booking_id to receipt lookup (verify guest owns booking)
- **Admin:** All admin receipt endpoints protected by `auth` middleware

### 2. Data in JSON Snapshot
- **Sensitive:** Guest personal info included (email, phone)
- **Mitigation:** Receipts are only shown to guest (via booking lookup) or admin
- **Future:** Add encryption for sensitive fields if needed

### 3. Receipt Immutability
- **Database:** receipt_data has no update trigger (immutable)
- **API:** No endpoint to modify receipt_data
- **Audit Trail:** issued_at timestamp for verification

## Performance Considerations

### 1. Receipt Number Generation
- **Query:** Searches for last receipt with LIKE operator
- **Index:** receipt_no has B-tree index for fast lookup
- **Improvement:** Could cache latest receipt number (with invalidation on creation)

### 2. Receipt Retrieval
- **Query:** Direct lookup by receipt_no (indexed)
- **Joins:** Includes booking, guest, property data
- **Optimization:** Eager-loading prevents N+1 queries

### 3. Booking Receipts List
- **Query:** WHERE booking_id (indexed foreign key)
- **Limit:** Consider pagination for high-volume bookings
- **Future:** Add limit/offset parameters to API

## Future Enhancements

1. **Receipt Email:** Email receipt to guest after generation
2. **Receipt Download:** PDF generation of receipt_data
3. **Receipt Search:** Search receipts by date range, amount, booking ref
4. **Receipt Analytics:** Monthly/yearly receipt reports
5. **Receipt Verification:** Digital signature on receipt_no for fraud prevention
6. **Partial Receipts:** If guest pays multiple times, show cumulative receipt
7. **Receipt Refunds:** Track refund receipts separately with RFD-YYYY-XXXXX format
8. **Multi-Currency:** Support other currencies (currently hardcoded KES)
