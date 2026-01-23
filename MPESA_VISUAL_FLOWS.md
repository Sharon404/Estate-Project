# M-PESA Payment Flows - Visual Guide

## Quick Answer: YES ✅

Your system **fully supports** the flow you described:
- STK fails → Show till number → User pays → User enters receipt → Payment validated

---

## Visual Flowchart 1: STK Success Path

```
┌─────────────────────────────────────────────────────────────────┐
│ GUEST PAYMENT SCREEN                                             │
│ - Booking amount: 5000 KES                                       │
│ - Phone number input field                                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
                  [Guest enters phone]
                  [Clicks "Pay Now"]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ SYSTEM: Create Payment Intent                                    │
│ POST /payment/intents                                            │
│ Response: payment_intent_id = 45                                 │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ SYSTEM: Send STK Push to M-PESA                                  │
│ POST /payment/mpesa/stk                                          │
│ "Enter your M-PESA PIN to pay 5000 KES..."                      │
└─────────────────────────────────────────────────────────────────┘
                            ↓
                  ┌─────────┴──────────┐
                  ↓                     ↓
         ✅ SUCCESS            ❌ FAILURE/TIMEOUT
                  ↓                     ↓
      [Guest enters PIN]     [No prompt received]
      [Payment processed]    [Network error]
                  ↓                     ↓
      ┌──────────────────────────────────────┐
      │ M-PESA Callback Received             │
      │ Transaction verified                 │
      │ Receipt auto-generated               │
      │ Email sent to guest                  │
      │ Booking status: CONFIRMED            │
      └──────────────────────────────────────┘
                            ↓
      ✅ PAYMENT COMPLETE (Instant, no admin work)
      Time: ~30 seconds
```

---

## Visual Flowchart 2: Manual Payment Fallback Path (Your Use Case)

```
┌─────────────────────────────────────────────────────────────────┐
│ GUEST PAYMENT SCREEN                                             │
│ - Booking amount: 5000 KES                                       │
│ - Phone number: 0712345678                                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
                [STK initiated → FAILS]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ FALLBACK: Manual Payment Option Displayed                        │
│                                                                   │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │  M-PESA Prompt Failed                                       │ │
│ │  Please pay manually:                                       │ │
│ │                                                              │ │
│ │  Till Number: *138#                                         │ │
│ │  Amount: 5000 KES                                           │ │
│ │  Company: Nairobi Homes                                     │ │
│ │                                                              │ │
│ │  Steps:                                                      │ │
│ │  1. Open M-PESA                                             │ │
│ │  2. Send Money                                              │ │
│ │  3. Till: *138#                                             │ │
│ │  4. Amount: 5000                                            │ │
│ │  5. Enter PIN                                               │ │
│ │                                                              │ │
│ │  Receipt Number: [                                      ]   │ │
│ │  [Submit for Review]                                        │ │
│ └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [GUEST TAKES ACTION - PAYS TO TILL]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ GUEST'S PHONE (M-PESA)                                           │
│                                                                   │
│ 1. Select "Send Money"                                           │
│ 2. Enter till: *138#                                             │
│ 3. Enter amount: 5000                                            │
│ 4. Enter PIN                                                     │
│ 5. PAYMENT SENT                                                  │
│                                                                   │
│ M-PESA Message:                                                  │
│ "You have sent 5000 KES to *138#"                               │
│ "Ref: LIK123ABC456"                                              │
│ "New balance: 995000"                                            │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [GUEST SEES RECEIPT: LIK123ABC456]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ GUEST: Enter Receipt Number                                      │
│                                                                   │
│ Receipt Number: [LIK123ABC456]                                   │
│ Amount: [5000]                                                   │
│ Phone: [+254712345678] (optional)                               │
│ Notes: [Paid via till] (optional)                               │
│                                                                   │
│ [Submit Receipt for Review]                                      │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ SYSTEM: Validate & Create Submission                             │
│ POST /payment/manual-entry                                       │
│                                                                   │
│ Validations:                                                     │
│ ✓ Receipt format is valid (9-20 alphanumeric)                   │
│ ✓ Amount matches booking                                        │
│ ✓ Receipt not already used                                      │
│ ✓ Payment intent exists                                         │
│                                                                   │
│ Create: MpesaManualSubmission                                    │
│ Status: SUBMITTED                                                │
│ ID: 12                                                           │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ GUEST: Confirmation Message                                      │
│                                                                   │
│ ✓ Receipt Submitted                                              │
│ Receipt: LIK123ABC456                                            │
│ Amount: 5000 KES                                                 │
│ Status: SUBMITTED                                                │
│                                                                   │
│ Next: Admin will verify within 24 hours                         │
│       You'll receive a confirmation email                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [EMAIL TO ADMIN: New submission pending]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ ADMIN DASHBOARD                                                  │
│                                                                   │
│ Pending Manual Submissions:                                      │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │ Booking: BK00001                                            │ │
│ │ Guest: John Doe                                             │ │
│ │ Email: john@example.com                                     │ │
│ │ Receipt: LIK123ABC456                                       │ │
│ │ Amount: 5000 KES                                            │ │
│ │ Submitted: 10:15 AM                                         │ │
│ │ Status: SUBMITTED                                           │ │
│ │                                                              │ │
│ │ [View Details] [Verify] [Reject]                            │ │
│ └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [ADMIN: Check M-PESA statement]
         [ADMIN: Verify receipt exists]
         [ADMIN: Verify amount matches]
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ ADMIN: Click "Verify" Button                                     │
│ POST /admin/payment/manual-submissions/12/verify                │
│                                                                   │
│ Verified Notes: "Verified against statement"                    │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ SYSTEM: Process Verification                                     │
│                                                                   │
│ 1. Update submission status: SUBMITTED → VERIFIED               │
│ 2. Create BookingTransaction (status: COMPLETED)                │
│ 3. Generate Receipt PDF                                         │
│ 4. Update Booking status: PENDING_PAYMENT → CONFIRMED           │
│ 5. Create Audit Log entry                                       │
│ 6. Queue confirmation emails                                    │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ EMAIL TO GUEST                                                   │
│                                                                   │
│ Subject: Payment Confirmed                                      │
│                                                                   │
│ Dear John,                                                       │
│                                                                   │
│ Your payment of 5000 KES has been verified!                     │
│ M-PESA Reference: LIK123ABC456                                  │
│ Receipt attached                                                │
│                                                                   │
│ Your booking is now CONFIRMED                                   │
│ Check-in: 2026-02-01                                            │
│ Check-out: 2026-02-05                                           │
│                                                                   │
│ Thank you!                                                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [EMAIL TO ADMIN: Verification complete]
                            ↓
      ✅ PAYMENT COMPLETE (After admin approval)
      Time: 30 seconds + 24 hours (admin review)
      Status: Booking CONFIRMED
      Receipt: Sent to guest email
```

---

## Comparison: STK vs Manual

```
┌─────────────────────┬──────────────────────────────────┬──────────────────────────────────┐
│ Aspect              │ STK Push (Automatic)             │ Manual (Fallback)                │
├─────────────────────┼──────────────────────────────────┼──────────────────────────────────┤
│ How guest pays      │ Automatic prompt on phone        │ Guest pays to till number        │
│ Guest enters PIN    │ On phone (M-PESA UI)             │ On phone (M-PESA UI)             │
│ Receipt             │ Auto-generated on success        │ Guest gets code (LIK123ABC456)   │
│ System receives     │ M-PESA callback                  │ Guest enters code                │
│ Validation          │ Automatic via callback           │ Admin manual verification        │
│ Admin work          │ None                             │ ~2 minutes review                │
│ Time to confirm     │ ~30 seconds                      │ 30 seconds + ~24 hours           │
│ Email to guest      │ Immediate                        │ After admin approval             │
│ Booking status      │ CONFIRMED immediately            │ CONFIRMED after verification    │
│ When to use         │ First choice (preferred)          │ If STK fails/times out          │
│ Success rate        │ 95%+                             │ 100% (once verified)             │
│ Cost                │ Standard M-PESA charge           │ Standard M-PESA charge           │
└─────────────────────┴──────────────────────────────────┴──────────────────────────────────┘
```

---

## Rejection Path (Alternative)

```
┌─────────────────────────────────────────────────────────────────┐
│ ADMIN: Reviews Submission                                        │
│                                                                   │
│ Receipt: LIK123ABC456                                            │
│ Amount: 5000 KES                                                 │
│                                                                   │
│ Admin checks M-PESA statement...                                │
│ ✗ Receipt NOT FOUND                                              │
│ ✗ Possible fraud                                                 │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ ADMIN: Click "Reject" Button                                     │
│ POST /admin/payment/manual-submissions/12/reject                │
│                                                                   │
│ Rejection Reason: "Receipt not found in M-PESA statement"       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ SYSTEM: Process Rejection                                        │
│                                                                   │
│ 1. Update submission status: SUBMITTED → REJECTED               │
│ 2. Update payment intent status: PENDING → FAILED               │
│ 3. Create Audit Log entry                                       │
│ 4. Queue rejection email                                        │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ EMAIL TO GUEST                                                   │
│                                                                   │
│ Subject: Payment Could Not Be Verified                          │
│                                                                   │
│ Dear John,                                                       │
│                                                                   │
│ Unfortunately, we could not verify your payment submission.      │
│                                                                   │
│ Receipt: LIK123ABC456                                            │
│ Reason: Not found in M-PESA records                             │
│                                                                   │
│ Please try again by:                                             │
│ 1. Re-attempting STK push payment, OR                            │
│ 2. Sending payment again and resubmitting receipt               │
│                                                                   │
│ Contact support if you need help.                               │
└─────────────────────────────────────────────────────────────────┘
                            ↓
         [BOOKING REMAINS: PENDING_PAYMENT]
         [GUEST CAN: Retry STK or resubmit manual]
```

---

## Data Flow Diagram

```
┌─────────────┐
│   GUEST     │
│  (Browser)  │
└──────┬──────┘
       │ 1. POST /payment/intents
       │    { booking_id: 1 }
       ↓
┌──────────────────────────────────────┐
│      PAYMENT CONTROLLER              │
│  - Create payment intent             │
│  - Return intent_id: 45              │
└──────────────────────────────────────┘
       │ 2. POST /payment/mpesa/stk
       │    { intent_id: 45, phone: '0712345678' }
       ↓
┌──────────────────────────────────────┐
│      MPESA STK SERVICE               │
│  - Call M-PESA API                   │
│  - Send prompt to phone              │
│  - Get response                      │
└──────────────────────────────────────┘
       │
       ├─ Success: Prompt sent
       │  └─ Poll for callback
       │
       └─ Failure: Timeout
          │ 3. POST /payment/manual-entry
          │    { intent_id: 45, receipt: 'LIK123ABC456', amount: 5000 }
          ↓
          ┌──────────────────────────────────────┐
          │   PAYMENT CONTROLLER                 │
          │  - Validate receipt format           │
          │  - Check for duplicates              │
          │  - Create manual submission          │
          │  - Return submission_id: 12          │
          └──────────────────────────────────────┘
          │
          ├─ Email: Notify admin of pending submission
          │
          │ 4. GET /admin/payment/manual-submissions/pending
          ├────────────────────────────────────────→ ADMIN DASHBOARD
          │
          │ 5. POST /admin/payment/manual-submissions/12/verify
          │    { verified_notes: '...' }
          ↓
          ┌──────────────────────────────────────┐
          │   ADMIN PAYMENT CONTROLLER           │
          │  - Verify submission                 │
          │  - Update status: VERIFIED           │
          │  - Create BookingTransaction         │
          │  - Generate Receipt                  │
          │  - Update Booking                    │
          │  - Create Audit Log                  │
          └──────────────────────────────────────┘
          │
          ├─ Email: Confirmation to guest
          │
          ↓
       [PAYMENT CONFIRMED]
       [BOOKING STATUS: CONFIRMED]
       [RECEIPT: PDF sent to email]
```

---

## Status Progression

```
Payment Intent Status Flow:
┌──────────┐
│ INITIATED│  ← Create payment intent
└────┬─────┘
     │
     ├─ Try STK Push
     │  ↓
     ├──────────┐
     │ PENDING  │  ← Waiting for callback
     └────┬─────┘
          │
          ├─ Callback received (success)
          │  ↓
          ├──────────┐
          │ SUCCESS  │  ← Payment verified
          └──────────┘
          │
          └─ Callback timeout
             ↓
          ┌──────────┐
          │  FAILED  │  ← Switch to manual
          └────┬─────┘
               │
               ├─ Submit manual receipt
               │  ↓
               ├──────────┐
               │ PENDING  │  ← Waiting for admin
               └────┬─────┘
                    │
                    ├─ Admin verifies
                    │  ↓
                    ├──────────┐
                    │ SUCCESS  │  ← Payment verified
                    └──────────┘
                    │
                    └─ Admin rejects
                       ↓
                    ┌──────────┐
                    │  FAILED  │  ← Guest can retry
                    └──────────┘
```

---

## Timeline Examples

### STK Success Timeline
```
10:00:00 - Guest visits payment screen
10:00:05 - Creates payment intent (ID: 45)
10:00:10 - Initiates STK push to 0712345678
10:00:15 - M-PESA sends prompt to phone
10:00:30 - Guest enters PIN
10:00:45 - M-PESA processes payment
10:01:00 - Callback received by system
10:01:05 - Payment verified
10:01:10 - Receipt generated
10:01:15 - Email sent to guest
10:01:20 - Booking status: CONFIRMED ✅

Total time: ~1 minute
Admin work: 0 minutes
```

### Manual Payment Timeline
```
10:00:00 - Guest visits payment screen
10:00:05 - Creates payment intent (ID: 45)
10:00:10 - Initiates STK push to 0712345678
10:00:20 - STK times out (no response)
10:00:25 - System shows fallback: Till number *138#
10:00:30 - Guest sees fallback screen
10:05:00 - Guest pays to till via M-PESA
10:05:05 - Guest gets receipt: LIK123ABC456
10:05:10 - Guest submits receipt LIK123ABC456
10:05:15 - System creates submission (ID: 12)
10:05:20 - Confirmation shown to guest
10:05:25 - Email: Admin notified of pending submission
10:30:00 - Admin checks dashboard (25 mins later)
10:30:05 - Admin reviews submission
10:30:10 - Admin verifies receipt in M-PESA statement
10:30:15 - Admin clicks "Verify"
10:30:20 - Payment recorded as COMPLETED
10:30:25 - Receipt generated
10:30:30 - Email sent to guest: "Payment confirmed"
10:30:35 - Booking status: CONFIRMED ✅
11:05:45 - Guest receives email

Total time: ~1 hour 5 minutes
Admin work: ~5 minutes
```

---

## Summary Visual

```
                    PAYMENT SYSTEM
                         │
                         ├─── STK PUSH (Automatic)
                         │         ├─ Success ✅ (30 seconds)
                         │         └─ Fail ❌
                         │
                         └─── MANUAL FALLBACK (Admin Required)
                                  ├─ User submits receipt
                                  ├─ Admin reviews
                                  ├─ Approve ✅ (1-24 hours)
                                  └─ Reject ❌ (Retry)
                         
                         Both flows result in:
                         - Receipt PDF
                         - Email confirmation
                         - Booking CONFIRMED
                         - Audit trail logged
```

