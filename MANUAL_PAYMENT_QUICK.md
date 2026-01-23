# Manual M-PESA Payment - Quick Reference

## TL;DR

Guest can submit M-PESA receipt manually if STK fails. Admin verifies and processes it.

## Flow (30 seconds)

```
STK Timeout → Guest: POST /payment/manual-entry → Admin: Verify ✓ → Ledger Entry → Booking Updated
```

## Guest: Submit Receipt

```bash
curl -X POST http://localhost:8000/payment/manual-entry \
  -H "Content-Type: application/json" \
  -d '{
    "payment_intent_id": 1,
    "mpesa_receipt_number": "LIK123ABC456",
    "amount": 5000,
    "phone_e164": "+254712345678",
    "notes": "Optional notes"
  }'

Response:
{
  "success": true,
  "submission_id": 1,
  "status": "SUBMITTED",
  "next_step": "Admin will verify within 24 hours"
}
```

## Admin: Verify Payment

```bash
# Get pending submissions
curl http://localhost:8000/admin/payment/manual-submissions/pending \
  -H "Authorization: Bearer TOKEN"

# Get submission details
curl http://localhost:8000/admin/payment/manual-submissions/1 \
  -H "Authorization: Bearer TOKEN"

# Verify payment
curl -X POST http://localhost:8000/admin/payment/manual-submissions/1/verify \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "verified_notes": "Verified against M-PESA statement"
  }'

Response:
{
  "success": true,
  "data": {
    "transaction_id": 15,
    "booking_status": "PARTIALLY_PAID",
    "amount_paid": 5000,
    "amount_due": 10000
  }
}
```

## Admin: Reject Payment

```bash
curl -X POST http://localhost:8000/admin/payment/manual-submissions/1/reject \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "reason": "Receipt number does not match M-PESA statement"
  }'
```

## Receipt Format

Valid receipts:
- Length: 9-20 characters
- Characters: Uppercase letters (A-Z) and digits (0-9)
- Example: `LIK123ABC456`, `LIK1234567890`

## Database Queries

### View pending submissions
```sql
SELECT * FROM mpesa_manual_submissions WHERE status = 'SUBMITTED' ORDER BY submitted_at ASC;
```

### View verification history
```sql
SELECT mpesa_receipt_number, amount, status, submitted_at, reviewed_at
FROM mpesa_manual_submissions
ORDER BY reviewed_at DESC NULLS FIRST;
```

### Check for duplicate submissions
```sql
SELECT mpesa_receipt_number, COUNT(*) FROM mpesa_manual_submissions GROUP BY mpesa_receipt_number HAVING COUNT(*) > 1;
```

### View transactions for booking
```sql
SELECT * FROM booking_transactions WHERE booking_id = 1 ORDER BY created_at DESC;
```

## What Happens on Verify

1. ✅ BookingTransaction created (source: MANUAL_ENTRY)
2. ✅ PaymentIntent status → SUCCEEDED
3. ✅ Booking amounts recalculated from ledger
4. ✅ Booking status updated (PAID or PARTIALLY_PAID)
5. ✅ Submission status → VERIFIED

## Routes

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/payment/manual-entry` | POST | No | Guest submits receipt |
| `/admin/payment/manual-submissions/pending` | GET | Yes | Get pending submissions |
| `/admin/payment/manual-submissions/{id}` | GET | Yes | Get submission details |
| `/admin/payment/manual-submissions/{id}/verify` | POST | Yes | Admin approves payment |
| `/admin/payment/manual-submissions/{id}/reject` | POST | Yes | Admin rejects payment |
| `/admin/payment/statistics` | GET | Yes | Get stats |

## Validation

| Field | Rule | Example |
|-------|------|---------|
| receipt | 9-20 uppercase alphanumeric | LIK123ABC456 |
| amount | 1-999999.99 | 5000 |
| phone | E.164 format (optional) | +254712345678 |

## Status Values

| Status | Meaning |
|--------|---------|
| SUBMITTED | Awaiting admin verification |
| VERIFIED | Admin verified, payment processed |
| REJECTED | Admin rejected, guest can resubmit |

## Testing

```bash
# Quick test
./tests/integration/test-manual-payment-flow.sh

# Full documentation
cat MANUAL_PAYMENT.md
```

## Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| Receipt already submitted | Duplicate submission | Check receipt number |
| Amount exceeds amount due | Overpayment attempt | Reduce amount |
| Invalid receipt format | Wrong format | Use 9-20 alphanumeric, uppercase |
| Payment intent not found | Wrong intent ID | Verify intent exists |

## Security

- ✅ Receipt uniqueness enforced at DB level
- ✅ Admin verification required (no auto-processing)
- ✅ Immutable audit trail
- ✅ All-or-nothing ledger entry
- ✅ Full transaction rollback on error

## Files

| File | Purpose |
|------|---------|
| `app/Http/Requests/SubmitManualMpesaRequest.php` | Form validation |
| `app/Http/Controllers/Payment/AdminPaymentController.php` | Admin endpoints |
| `app/Services/PaymentService.php` | Business logic |
| `MANUAL_PAYMENT.md` | Full documentation |

---

For complete documentation, see `MANUAL_PAYMENT.md`
