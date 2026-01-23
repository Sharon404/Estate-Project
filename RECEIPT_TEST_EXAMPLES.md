# Receipt System - Test Examples

## Testing the Receipt Generation System

This guide provides practical examples for testing all receipt functionality.

## 1. STK Payment → Receipt Creation

### Scenario
Guest initiates STK Push payment, M-PESA processes it, receipt should be created automatically.

### Test Steps

#### Step 1: Create Payment Intent
```bash
curl -X POST http://localhost:8000/payment/intents \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 1,
    "amount": 5000
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "payment_intent_id": 1,
    "status": "INITIATED",
    "amount": 5000
  }
}
```

#### Step 2: Initiate STK
```bash
curl -X POST http://localhost:8000/payment/mpesa/stk \
  -H "Content-Type: application/json" \
  -d '{
    "payment_intent_id": 1,
    "phone_number": "254712345678"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "STK request initiated",
  "stk_request_id": 1
}
```

#### Step 3: Simulate M-PESA Callback
```bash
# In real scenario, M-PESA sends POST to /payment/mpesa/callback
# For testing, simulate callback directly or use test helper

curl -X POST http://localhost:8000/payment/mpesa/callback \
  -H "Content-Type: application/json" \
  -d '{
    "Body": {
      "stkCallback": {
        "MerchantRequestID": "request-123",
        "CheckoutRequestID": "checkout-123",
        "ResultCode": 0,
        "ResultDesc": "The service request has been processed successfully.",
        "CallbackMetadata": {
          "Item": [
            { "Name": "Amount", "Value": 5000 },
            { "Name": "MpesaReceiptNumber", "Value": "LIK123ABC456" },
            { "Name": "TransactionDate", "Value": 20260122143000 },
            { "Name": "PhoneNumber", "Value": 254712345678 }
          ]
        }
      }
    }
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Payment processed successfully",
  "booking_id": 1,
  "receipt_id": 1,
  "receipt_no": "RCP-2026-00001"
}
```

#### Step 4: Verify Receipt Was Created
```bash
curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00001 \
  -H "Content-Type: application/json"
```

**Expected Response:**
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
      "receipt_info": {
        "type": "STK_PUSH",
        "generated_at": "2026-01-22T14:30:00Z",
        "issued_by_system": true
      },
      "payment_info": {
        "amount": 5000.00,
        "currency": "KES",
        "payment_method": "STK_PUSH",
        "mpesa_receipt_number": "LIK123ABC456",
        "booking_transaction_id": 1,
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
        "ip_address": "127.0.0.1",
        "server_timezone": "Africa/Nairobi",
        "generated_timestamp": 1674408600
      }
    }
  }
}
```

### Assertions
- ✅ Receipt created with correct receipt_no (RCP-2026-00001)
- ✅ Receipt linked to booking_id = 1
- ✅ Receipt linked to payment_intent_id = 1
- ✅ Receipt_data JSON contains all required fields
- ✅ amount_paid_before = 0, amount_paid_after = 5000
- ✅ receipt_data['receipt_info']['type'] = 'STK_PUSH'
- ✅ Receipt shows M-PESA receipt number

---

## 2. Manual Payment → Receipt Creation

### Scenario
STK times out, guest submits manual payment, admin verifies, receipt created.

### Test Steps

#### Step 1: Create Payment Intent
```bash
curl -X POST http://localhost:8000/payment/intents \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 2,
    "amount": 10000
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "payment_intent_id": 2,
    "status": "INITIATED",
    "amount": 10000
  }
}
```

#### Step 2: Guest Submits Manual Payment
```bash
curl -X POST http://localhost:8000/payment/manual-entry \
  -H "Content-Type: application/json" \
  -d '{
    "payment_intent_id": 2,
    "mpesa_receipt_number": "LIK456XYZ789",
    "amount": 10000,
    "phone_e164": "+254712345678",
    "notes": "STK timed out, paid via USSD"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Payment submitted successfully",
  "submission_id": 1,
  "status": "SUBMITTED",
  "payment_intent_id": 2,
  "amount": 10000
}
```

#### Step 3: Admin Reviews Submission
```bash
curl -X GET http://localhost:8000/admin/payment/manual-submissions/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "payment_intent_id": 2,
    "booking_id": 2,
    "mpesa_receipt_number": "LIK456XYZ789",
    "amount": 10000.00,
    "status": "SUBMITTED",
    "submitted_at": "2026-01-22T15:00:00Z"
  }
}
```

#### Step 4: Admin Verifies Payment
```bash
curl -X POST http://localhost:8000/admin/payment/manual-submissions/1/verify \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "verified_by": "admin_user",
    "verification_notes": "M-PESA receipt verified via USSD balance check"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Manual payment verified successfully",
  "transaction_id": 2,
  "receipt_number": "LIK456XYZ789",
  "receipt_id": 2,
  "receipt_no": "RCP-2026-00002",
  "amount": 10000.00,
  "booking_status": "PAID",
  "amount_paid": 10000.00,
  "amount_due": 0
}
```

#### Step 5: Verify Receipt Was Created
```bash
curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00002 \
  -H "Content-Type: application/json"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "receipt": {
      "id": 2,
      "booking_id": 2,
      "payment_intent_id": 2,
      "receipt_no": "RCP-2026-00002",
      "mpesa_receipt_number": "LIK456XYZ789",
      "amount": "10000.00",
      "currency": "KES",
      "issued_at": "2026-01-22T15:05:00Z"
    },
    "data": {
      "receipt_info": {
        "type": "MANUAL_ENTRY",
        "generated_at": "2026-01-22T15:05:00Z",
        "issued_by_system": true
      },
      "payment_info": {
        "amount": 10000.00,
        "currency": "KES",
        "payment_method": "MANUAL_ENTRY",
        "mpesa_receipt_number": "LIK456XYZ789",
        "booking_transaction_id": 2,
        "payment_intent_id": 2
      },
      "booking_info": {
        "id": 2,
        "booking_ref": "GS-2026-002",
        "status": "PAID",
        "check_in": "2026-03-01",
        "check_out": "2026-03-08",
        "nights": 7,
        "total_amount": 10000.00,
        "amount_paid_before": 0.00,
        "amount_paid_after": 10000.00,
        "remaining_balance": 0.00
      },
      "guest_info": {
        "id": 2,
        "first_name": "Jane",
        "last_name": "Smith",
        "email": "jane@example.com",
        "phone": "+254712345679"
      },
      "property_info": {
        "id": 2,
        "name": "Garden Cottage",
        "location": "Nairobi",
        "room_type": "Standard Room"
      },
      "meta": {
        "ip_address": "192.168.1.1",
        "server_timezone": "Africa/Nairobi",
        "generated_timestamp": 1674408900
      }
    }
  }
}
```

### Assertions
- ✅ Receipt created with correct receipt_no (RCP-2026-00002)
- ✅ Receipt number incremented from previous (00001 → 00002)
- ✅ Receipt_data['receipt_info']['type'] = 'MANUAL_ENTRY'
- ✅ Receipt linked to booking_id = 2
- ✅ Receipt linked to payment_intent_id = 2
- ✅ M-PESA receipt number stored correctly
- ✅ amount_paid_before = 0, amount_paid_after = 10000
- ✅ Booking status updated to PAID

---

## 3. Receipt Number Sequential Increment

### Scenario
Multiple payments within same year should have incrementing receipt numbers.

### Test Steps

#### Payment 1
```bash
# Follow STK test steps → Creates RCP-2026-00001
```

#### Payment 2
```bash
# Follow Manual payment test steps → Creates RCP-2026-00002
```

#### Payment 3
```bash
curl -X POST http://localhost:8000/payment/intents \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 3,
    "amount": 7500
  }'
# Initiate STK → Process callback → Should create RCP-2026-00003
```

### Verify Sequence
```bash
curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00001
# Should exist ✓

curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00002
# Should exist ✓

curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00003
# Should exist ✓

curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00004
# Should NOT exist (404)
```

### Assertions
- ✅ Receipt numbers increment sequentially
- ✅ No gaps in numbering
- ✅ Format consistent (RCP-2026-XXXXX)

---

## 4. List All Booking Receipts

### Scenario
Booking has multiple payments (partial payments), guest retrieves all receipts.

### Setup: Create Booking with Multiple Payments
```bash
# Payment 1: RCP-2026-00001 (5000)
# Payment 2: RCP-2026-00002 (5000)
# Payment 3: RCP-2026-00003 (5000)
# Booking total: 15000 (all three payments sum to total)
```

### Test Steps

```bash
curl -X GET http://localhost:8000/payment/bookings/1/receipts \
  -H "Content-Type: application/json"
```

**Expected Response:**
```json
{
  "success": true,
  "booking_ref": "GS-2026-001",
  "receipt_count": 3,
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
      "amount": 5000.00,
      "currency": "KES",
      "mpesa_receipt_number": "LIK456XYZ789",
      "issued_at": "2026-01-22T15:00:00Z",
      "payment_method": "MANUAL_ENTRY"
    },
    {
      "receipt_id": 3,
      "receipt_no": "RCP-2026-00003",
      "amount": 5000.00,
      "currency": "KES",
      "mpesa_receipt_number": "LIK789DEF012",
      "issued_at": "2026-01-22T16:00:00Z",
      "payment_method": "STK_PUSH"
    }
  ]
}
```

### Assertions
- ✅ receipt_count = 3 (correct count)
- ✅ All 3 receipts included in list
- ✅ Each receipt has payment_method (STK_PUSH or MANUAL_ENTRY)
- ✅ Amounts sum to booking total (5000 + 5000 + 5000 = 15000)
- ✅ Issued dates in chronological order

---

## 5. Get Specific Receipt for Booking

### Test Steps

```bash
curl -X GET http://localhost:8000/payment/bookings/1/receipts/RCP-2026-00001 \
  -H "Content-Type: application/json"
```

**Expected Response:**
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

### Invalid Cases

#### Receipt doesn't exist
```bash
curl -X GET http://localhost:8000/payment/bookings/1/receipts/RCP-2026-99999
```

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Receipt not found for this booking"
}
```

#### Receipt exists but for different booking
```bash
curl -X GET http://localhost:8000/payment/bookings/99/receipts/RCP-2026-00001
# Receipt is for booking 1, not booking 99
```

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Receipt not found for this booking"
}
```

### Assertions
- ✅ Retrieves correct receipt
- ✅ Receipt belongs to correct booking
- ✅ Returns 404 if not found
- ✅ Returns 404 if belongs to different booking

---

## 6. Idempotency Test (No Duplicate Receipts)

### Scenario
If receipt creation is retried, should not create duplicate.

### Test Steps

#### Setup
```bash
# Create payment intent and process callback
# Should create receipt RCP-2026-00001
```

#### Retry Receipt Creation
```bash
# In test code, manually call:
$receiptService->createStkReceipt($transaction, 'LIK123ABC456');
$receiptService->createStkReceipt($transaction, 'LIK123ABC456');
```

### Verify No Duplicate
```bash
curl -X GET http://localhost:8000/payment/bookings/1/receipts
```

**Expected Response:**
```json
{
  "success": true,
  "booking_ref": "GS-2026-001",
  "receipt_count": 1,  // ← Still 1, not 2
  "data": [
    {
      "receipt_id": 1,
      "receipt_no": "RCP-2026-00001"
    }
  ]
}
```

### Assertions
- ✅ Only 1 receipt exists (not 2)
- ✅ receipt_count = 1
- ✅ Idempotency check prevents duplicate
- ✅ No exception thrown on retry

---

## 7. JSON Snapshot Completeness Test

### Scenario
Verify receipt_data JSON contains all required fields.

### Test Steps

```bash
curl -X GET http://localhost:8000/payment/receipts/RCP-2026-00001
```

### Verify JSON Structure

**Receipt Info Section:**
```json
"receipt_info": {
  "type": "STK_PUSH",           // Required: 'STK_PUSH' or 'MANUAL_ENTRY'
  "generated_at": "2026-01-22T14:30:00Z",  // Required: ISO8601 timestamp
  "issued_by_system": true       // Required: Always true
}
```

**Payment Info Section:**
```json
"payment_info": {
  "amount": 5000.00,             // Required: Payment amount
  "currency": "KES",             // Required: Currency code
  "payment_method": "STK_PUSH",  // Required: How payment was made
  "mpesa_receipt_number": "LIK123ABC456",  // Optional: M-PESA receipt
  "booking_transaction_id": 1,   // Required: Link to transaction
  "payment_intent_id": 1         // Required: Link to payment intent
}
```

**Booking Info Section:**
```json
"booking_info": {
  "id": 1,                       // Required: Booking ID
  "booking_ref": "GS-2026-001",  // Required: Booking reference
  "status": "PARTIALLY_PAID",    // Required: Booking status
  "check_in": "2026-02-01",      // Required: Check-in date
  "check_out": "2026-02-05",     // Required: Check-out date
  "nights": 4,                   // Required: Number of nights
  "total_amount": 15000.00,      // Required: Total booking cost
  "amount_paid_before": 0.00,    // Required: Amount paid before this payment
  "amount_paid_after": 5000.00,  // Required: Amount paid including this payment
  "remaining_balance": 10000.00  // Required: Remaining to pay
}
```

**Guest Info Section:**
```json
"guest_info": {
  "id": 1,                       // Required: Guest ID
  "first_name": "John",          // Required: First name
  "last_name": "Doe",            // Required: Last name
  "email": "john@example.com",   // Required: Email
  "phone": "+254712345678"       // Required: Phone
}
```

**Property Info Section:**
```json
"property_info": {
  "id": 1,                       // Required: Property ID
  "name": "Beachfront Villa",    // Required: Property name
  "location": "Diani Beach",     // Required: Location
  "room_type": "Deluxe Villa"    // Required: Room type
}
```

**Meta Section:**
```json
"meta": {
  "ip_address": "192.168.1.1",   // Required: Client IP
  "user_agent": "Mozilla/5.0...", // Optional: Browser user agent
  "server_timezone": "Africa/Nairobi",  // Required: Server timezone
  "generated_timestamp": 1674408600     // Required: Unix timestamp
}
```

### Assertions
- ✅ receipt_info has type, generated_at, issued_by_system
- ✅ payment_info has all payment details
- ✅ booking_info has before/after amounts
- ✅ guest_info has all guest details
- ✅ property_info has property details
- ✅ meta has IP and timestamp
- ✅ No null values in required fields

---

## 8. Database Direct Query Test

### Verify Data Integrity

```sql
-- Check receipts table structure
DESCRIBE receipts;

-- Should show columns:
-- - id (INT)
-- - booking_id (INT, FK)
-- - payment_intent_id (INT, FK)
-- - receipt_no (VARCHAR 30, UNIQUE)
-- - mpesa_receipt_number (VARCHAR 20, nullable)
-- - amount (DECIMAL 12,2)
-- - currency (CHAR 3)
-- - receipt_data (JSON)
-- - issued_at (TIMESTAMP)
-- - created_at, updated_at (TIMESTAMP)
```

```sql
-- Query specific receipt
SELECT * FROM receipts WHERE receipt_no = 'RCP-2026-00001';

-- Should return:
-- id: 1
-- booking_id: 1
-- payment_intent_id: 1
-- receipt_no: RCP-2026-00001
-- mpesa_receipt_number: LIK123ABC456
-- amount: 5000.00
-- currency: KES
-- receipt_data: JSON data
-- issued_at: 2026-01-22 14:30:00
```

```sql
-- Query all receipts for booking
SELECT receipt_no, amount, currency, issued_at FROM receipts 
WHERE booking_id = 1 
ORDER BY issued_at ASC;

-- Should return all 3 receipts in order
```

```sql
-- Verify UNIQUE constraint on receipt_no
INSERT INTO receipts (booking_id, payment_intent_id, receipt_no, amount, currency, receipt_data) 
VALUES (1, 1, 'RCP-2026-00001', 5000, 'KES', '{}');

-- Should fail with UNIQUE constraint error
```

```sql
-- Verify JSON data structure
SELECT 
  receipt_no,
  JSON_EXTRACT(receipt_data, '$.receipt_info.type') AS payment_type,
  JSON_EXTRACT(receipt_data, '$.payment_info.amount') AS payment_amount,
  JSON_EXTRACT(receipt_data, '$.booking_info.booking_ref') AS booking_ref
FROM receipts;

-- Should return properly formatted JSON
```

---

## 9. Error Cases Test

### Receipt Not Found
```bash
curl -X GET http://localhost:8000/payment/receipts/RCP-9999-99999
```

**Expected:**
- Status: 404
- Message: "Receipt not found"
- success: false

### Invalid Booking ID
```bash
curl -X GET http://localhost:8000/payment/bookings/99999/receipts
```

**Expected:**
- Status: 404
- Message: "Not found" (Booking not found)

### Malformed Receipt Number
```bash
curl -X GET http://localhost:8000/payment/receipts/INVALID
```

**Expected:**
- Status: 404
- Message: "Receipt not found"

---

## 10. Integration Test Script (Laravel)

```php
<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\PaymentIntent;
use App\Models\Receipt;
use App\Models\BookingTransaction;
use App\Services\ReceiptService;
use Tests\TestCase;

class ReceiptGenerationTest extends TestCase
{
    protected $booking;
    protected $paymentIntent;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->booking = Booking::factory()->create([
            'total_amount' => 15000,
            'amount_paid' => 0,
            'amount_due' => 15000,
        ]);
        
        $this->paymentIntent = PaymentIntent::factory()->create([
            'booking_id' => $this->booking->id,
            'amount' => 5000,
        ]);
    }

    public function test_stk_payment_creates_receipt()
    {
        $transaction = BookingTransaction::create([
            'booking_id' => $this->booking->id,
            'payment_intent_id' => $this->paymentIntent->id,
            'amount' => 5000,
            'currency' => 'KES',
            'source' => 'STK_PUSH',
            'mpesa_receipt_number' => 'LIK123ABC456',
        ]);

        $receiptService = new ReceiptService();
        $receipt = $receiptService->createStkReceipt(
            $transaction, 
            'LIK123ABC456'
        );

        $this->assertNotNull($receipt);
        $this->assertEquals('RCP-2026-00001', $receipt->receipt_no);
        $this->assertEquals(5000, $receipt->amount);
        $this->assertEquals('STK_PUSH', $receipt->receipt_data['receipt_info']['type']);
        
        $this->assertDatabaseHas('receipts', [
            'receipt_no' => 'RCP-2026-00001',
            'booking_id' => $this->booking->id,
        ]);
    }

    public function test_receipt_number_increments()
    {
        $transaction1 = BookingTransaction::factory()->create();
        $transaction2 = BookingTransaction::factory()->create();

        $receiptService = new ReceiptService();
        
        $receipt1 = $receiptService->createStkReceipt($transaction1, null);
        $receipt2 = $receiptService->createStkReceipt($transaction2, null);

        $this->assertEquals('RCP-2026-00001', $receipt1->receipt_no);
        $this->assertEquals('RCP-2026-00002', $receipt2->receipt_no);
    }

    public function test_idempotency_prevents_duplicates()
    {
        $transaction = BookingTransaction::factory()->create();
        $receiptService = new ReceiptService();

        $receipt1 = $receiptService->createStkReceipt($transaction, 'LIK123');
        $receipt2 = $receiptService->createStkReceipt($transaction, 'LIK123');

        $this->assertEquals($receipt1->id, $receipt2->id);
        $this->assertEquals(1, Receipt::count());
    }

    public function test_retrieve_receipt_by_number()
    {
        $transaction = BookingTransaction::factory()->create();
        $receiptService = new ReceiptService();
        $receipt = $receiptService->createStkReceipt($transaction, 'LIK123');

        $this->get('/payment/receipts/RCP-2026-00001')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.receipt.receipt_no', 'RCP-2026-00001');
    }

    public function test_list_booking_receipts()
    {
        // Create 3 receipts for same booking
        $this->get("/payment/bookings/{$this->booking->id}/receipts")
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('receipt_count', 3);
    }

    public function test_receipt_not_found()
    {
        $this->get('/payment/receipts/RCP-9999-99999')
            ->assertStatus(404)
            ->assertJsonPath('success', false);
    }
}
?>
```

---

## Automation Test Script (Postman/Newman)

```json
{
  "info": {
    "name": "Receipt Generation Tests",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "1. Create Payment Intent",
      "request": {
        "method": "POST",
        "url": {
          "raw": "{{base_url}}/payment/intents",
          "host": ["{{base_url}}"],
          "path": ["payment", "intents"]
        },
        "body": {
          "mode": "raw",
          "raw": "{\"booking_id\": 1, \"amount\": 5000}"
        }
      },
      "tests": [
        "pm.test('Status 200', () => pm.response.code === 200)",
        "pm.test('Has payment_intent_id', () => pm.response.json().data.payment_intent_id)"
      ]
    },
    {
      "name": "2. Retrieve Receipt",
      "request": {
        "method": "GET",
        "url": {
          "raw": "{{base_url}}/payment/receipts/RCP-2026-00001",
          "host": ["{{base_url}}"],
          "path": ["payment", "receipts", "RCP-2026-00001"]
        }
      },
      "tests": [
        "pm.test('Status 200', () => pm.response.code === 200)",
        "pm.test('Receipt found', () => pm.response.json().success === true)",
        "pm.test('Has receipt_data', () => pm.response.json().data.data)"
      ]
    }
  ]
}
```

---

## Checklist Summary

### Manual Testing
- [ ] STK payment → receipt created
- [ ] Receipt number format correct
- [ ] Manual payment → receipt created
- [ ] Receipt numbers increment
- [ ] List booking receipts works
- [ ] Get specific receipt works
- [ ] JSON snapshot complete
- [ ] Idempotency (no duplicates)
- [ ] 404 on not found
- [ ] Error handling works

### Automated Testing
- [ ] Unit tests pass
- [ ] Integration tests pass
- [ ] API tests pass
- [ ] Database tests pass

### Database Verification
- [ ] Table structure correct
- [ ] Indexes exist
- [ ] Foreign keys working
- [ ] UNIQUE constraint on receipt_no
- [ ] JSON data stored correctly

---

End of Receipt System Test Examples
