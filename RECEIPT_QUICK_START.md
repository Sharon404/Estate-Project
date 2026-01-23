# Receipt Generation - Quick Start Guide

## System Overview

The Receipt Generation System automatically creates system-generated receipts for every successful payment (both STK Push and manual entry). Each receipt is a snapshot capturing all payment and booking details at the moment of payment confirmation.

**Key Fact:** Receipts are completely automatic - no manual intervention needed. They're created instantly when payments succeed.

## How It Works

### STK Payment → Receipt
```
1. Guest pays via M-PESA STK
2. M-PESA sends callback to /payment/mpesa/callback
3. System confirms payment and creates ledger entry
4. System AUTOMATICALLY creates receipt with snapshot
5. Receipt appears in system immediately
```

### Manual Payment → Receipt
```
1. Guest submits M-PESA receipt (if STK times out)
2. Admin verifies the receipt in admin panel
3. System confirms payment and creates ledger entry
4. System AUTOMATICALLY creates receipt with snapshot
5. Receipt appears in system immediately
```

## Receipt Number Format

**Format:** `RCP-YYYY-XXXXX`

Examples:
- `RCP-2026-00001` ← First receipt of 2026
- `RCP-2026-00002` ← Second receipt of 2026
- `RCP-2026-00500` ← 500th receipt of 2026

**How it works:**
- First receipt of year = 00001
- Numbers increment sequentially
- Resets to 00001 on January 1st each year
- Completely automatic - no manual numbering

## What's in a Receipt

Each receipt stores a complete snapshot:

```json
{
  "receipt_info": {
    "type": "STK_PUSH or MANUAL_ENTRY",
    "generated_at": "2026-01-22T14:30:00Z"
  },
  "payment_info": {
    "amount": 5000.00,
    "currency": "KES",
    "payment_method": "STK_PUSH or MANUAL_ENTRY",
    "mpesa_receipt_number": "LIK123ABC456"
  },
  "booking_info": {
    "booking_ref": "GS-2026-001",
    "check_in": "2026-02-01",
    "check_out": "2026-02-05",
    "nights": 4,
    "total_amount": 15000.00,
    "amount_paid_before": 0.00,
    "amount_paid_after": 5000.00,
    "remaining_balance": 10000.00
  },
  "guest_info": {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+254712345678"
  },
  "property_info": {
    "name": "Beachfront Villa",
    "location": "Diani Beach",
    "room_type": "Deluxe Villa"
  }
}
```

## For Guests

### Getting a Receipt

**After STK Payment:**
```bash
# Guest receives receipt_no (e.g., RCP-2026-00001)
# Guest can retrieve anytime with:

GET /payment/receipts/RCP-2026-00001

# Response contains full receipt with all payment details
```

**After Manual Payment:**
```bash
# Same process - admin provides receipt_no to guest
# Guest retrieves with same endpoint

GET /payment/receipts/RCP-2026-00001
```

### List All Payment Receipts for Booking

```bash
# Get all receipts for a booking
GET /payment/bookings/1/receipts

# Response shows:
# - All payment receipts
# - Payment amounts and dates
# - Payment methods (STK vs Manual)
```

## For Developers

### Creating a Receipt (Automatic)

**In STK flow:** `MpesaCallbackService.php` line ~145
```php
$receiptService = new ReceiptService();
$receipt = $receiptService->createStkReceipt($transaction, $mpesaReceiptNumber);

// Returns: Receipt with receipt_no = "RCP-2026-00001"
```

**In manual flow:** `PaymentService.php` line ~420
```php
$receiptService = new ReceiptService();
$receipt = $receiptService->createManualReceipt($transaction, $mpesaReceiptNumber);

// Returns: Receipt with receipt_no = "RCP-2026-00001"
```

### Retrieving Receipts

**Get by receipt number:**
```php
$receipt = Receipt::where('receipt_no', 'RCP-2026-00001')->first();
$data = $receipt->receipt_data; // JSON snapshot
```

**Get all for booking:**
```php
$receipts = $booking->receipts;
// or
$receipts = Receipt::where('booking_id', 1)->get();
```

**Get details (includes nested relations):**
```php
$receiptService = new ReceiptService();
$details = $receiptService->getReceiptDetails($receipt);
// Returns: ['receipt' => {...}, 'data' => {...}]
```

## For Admin

### Verify Manual Payment → Creates Receipt

**Process:**
1. Guest submits manual M-PESA receipt (times out or fails STK)
2. Admin sees submission in pending list
3. Admin verifies the M-PESA receipt number
4. Admin clicks "Verify"
5. System creates receipt automatically
6. Receipt number provided to guest

**Code location:** `AdminPaymentController.php` → `verifySubmission()` method

**Response includes:**
```json
{
  "success": true,
  "message": "Manual payment verified successfully",
  "receipt_id": 1,
  "receipt_no": "RCP-2026-00001"
}
```

## API Reference

### Public Endpoints

#### Get Receipt by Number
```bash
GET /payment/receipts/{receiptNo}

Example:
GET /payment/receipts/RCP-2026-00001

Response:
{
  "success": true,
  "data": {
    "receipt": {
      "id": 1,
      "receipt_no": "RCP-2026-00001",
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

#### List Booking Receipts
```bash
GET /payment/bookings/{bookingId}/receipts

Example:
GET /payment/bookings/1/receipts

Response:
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
      "payment_method": "STK_PUSH",
      "issued_at": "2026-01-22T14:30:00Z"
    },
    {
      "receipt_id": 2,
      "receipt_no": "RCP-2026-00002",
      "amount": 10000.00,
      "currency": "KES",
      "payment_method": "MANUAL_ENTRY",
      "issued_at": "2026-01-23T10:15:00Z"
    }
  ]
}
```

#### Get Specific Receipt for Booking
```bash
GET /payment/bookings/{bookingId}/receipts/{receiptNo}

Example:
GET /payment/bookings/1/receipts/RCP-2026-00001

Response:
{
  "success": true,
  "data": {
    "receipt": { ... },
    "data": { ... }
  }
}
```

## Database Schema

```sql
receipts table:
├─ id (Primary Key)
├─ booking_id (Foreign Key → bookings)
├─ payment_intent_id (Foreign Key → payment_intents)
├─ receipt_no (Unique, VARCHAR 30) ← RCP-2026-00001 format
├─ mpesa_receipt_number (VARCHAR 20, nullable)
├─ amount (Decimal 12,2) ← Payment amount
├─ currency (Char 3, default KES)
├─ receipt_data (JSON) ← Complete snapshot
├─ issued_at (Timestamp)
└─ created_at, updated_at (auto)

Indexes:
├─ PRIMARY KEY (id)
├─ UNIQUE (receipt_no)
├─ FOREIGN KEY (booking_id)
├─ FOREIGN KEY (payment_intent_id)
└─ Regular indexes on foreign keys
```

## Important Design Decisions

### 1. Why Sequential Numbers?
- **Easy reference:** RCP-2026-00001 is human-readable
- **Support tickets:** Guests can reference by receipt number
- **Unique guarantee:** Can't have duplicates
- **Year-based:** RCP-2027-00001 starts fresh in 2027

### 2. Why JSON Snapshot?
- **Immutable record:** Captures state exactly when paid
- **Complete history:** All details preserved forever
- **Audit trail:** Shows amounts before/after payment
- **No updates:** Receipt_data never changes

### 3. Why Automatic Creation?
- **No manual work:** System creates receipts automatically
- **No missed receipts:** Every payment gets one
- **Consistent:** Same process for STK and manual
- **Reliable:** Idempotency prevents duplicates

### 4. Why One Receipt Per Payment?
- **Clear mapping:** One payment transaction = one receipt
- **Simple accounting:** Easy to reconcile
- **Guest clarity:** One receipt per payment, not per booking
- **System design:** Matches payment intent 1:1

## Troubleshooting

### Receipt Not Created
**Check:**
1. Did payment succeed? (Check payment_intent status = SUCCEEDED)
2. Is BookingTransaction in database? (Payment must be in ledger)
3. Check application logs for errors
4. Verify database has receipts table

### Can't Find Receipt
**Try:**
1. Verify receipt number format: RCP-YYYY-XXXXX
2. Check case sensitivity: RCP-2026-00001 (uppercase)
3. Confirm receipt was generated for this booking
4. Check if booking_id matches

### Duplicate Receipts
**This shouldn't happen because:**
1. `receiptExists()` checks if receipt already exists
2. Idempotency check uses booking_transaction_id
3. Multiple calls to create same receipt returns existing one
4. Database has UNIQUE constraint on receipt_no

## Testing Checklist

- [ ] STK payment creates receipt ✓
- [ ] Manual payment creates receipt ✓
- [ ] Receipt number increments correctly ✓
- [ ] Receipt data snapshot is complete ✓
- [ ] GET /payment/receipts/{no} works ✓
- [ ] GET /payment/bookings/{id}/receipts works ✓
- [ ] GET /payment/bookings/{id}/receipts/{no} works ✓
- [ ] JSON data structure is correct ✓
- [ ] Idempotency prevents duplicates ✓
- [ ] No orphaned receipts (always linked to booking) ✓

## Files Reference

| File | Purpose |
|------|---------|
| `app/Services/ReceiptService.php` | Receipt generation logic (300+ lines) |
| `app/Http/Controllers/Payment/PaymentController.php` | Receipt endpoints (3 new methods) |
| `app/Services/MpesaCallbackService.php` | Creates receipt after STK success |
| `app/Services/PaymentService.php` | Creates receipt after manual verification |
| `routes/web.php` | Receipt retrieval routes (3 new) |
| `app/Models/Receipt.php` | Receipt model (already exists) |
| `RECEIPT_SYSTEM_DOCUMENTATION.md` | Complete documentation (2000+ lines) |
| `RECEIPT_IMPLEMENTATION_SUMMARY.md` | Implementation summary |

## Performance Notes

- **Receipt number generation:** O(1) with index on receipt_no
- **Receipt retrieval:** O(1) direct lookup
- **Booking receipts list:** O(n) where n = number of payments
- **No pagination:** Consider adding for bookings with 100+ payments

## Security Notes

- **Access control:** Anyone can retrieve receipts by number (publicly accessible)
- **Future:** Add booking_id to receipt lookup to verify guest owns booking
- **Data:** Guest info in receipt_data is visible to all (could restrict if needed)
- **Immutable:** Receipt_data can never be modified (no update endpoints)

## Related Documentation

- Full details: See `RECEIPT_SYSTEM_DOCUMENTATION.md`
- Implementation: See `RECEIPT_IMPLEMENTATION_SUMMARY.md`
- API tests: Examples in `RECEIPT_SYSTEM_DOCUMENTATION.md` → "Testing"
- Integration code: `ReceiptService.php` and `MpesaCallbackService.php`

---

**Summary:** Receipts are completely automatic. When a payment succeeds (STK or manual), a receipt with a system-generated number and complete data snapshot is created instantly. Guests retrieve receipts by number using the API.
