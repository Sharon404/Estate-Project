#!/bin/bash

# Complete Booking → Payment Flow Test Script
# Run this to test the entire booking workflow

BASE_URL="http://localhost:8001"
PROPERTY_ID=1
GUEST_EMAIL="test_$(date +%s)@example.com"
GUEST_NAME="Test User $(date +%s)"
GUEST_PHONE="+254701123456"

echo "=========================================="
echo "COMPLETE BOOKING FLOW TEST"
echo "=========================================="

# STEP 1: Create DRAFT booking
echo -e "\n[STEP 1] Create DRAFT booking..."
BOOKING_RESPONSE=$(curl -s -X POST "$BASE_URL/bookings" \
  -H "Content-Type: application/json" \
  -d "{
    \"property_id\": $PROPERTY_ID,
    \"check_in\": \"2026-02-01\",
    \"check_out\": \"2026-02-05\",
    \"adults\": 2,
    \"children\": 0,
    \"special_requests\": \"Early check-in preferred\",
    \"guest\": {
      \"full_name\": \"$GUEST_NAME\",
      \"email\": \"$GUEST_EMAIL\",
      \"phone_e164\": \"$GUEST_PHONE\"
    }
  }")

echo "$BOOKING_RESPONSE" | jq .

BOOKING_ID=$(echo "$BOOKING_RESPONSE" | jq -r '.data.id')
if [ "$BOOKING_ID" = "null" ] || [ -z "$BOOKING_ID" ]; then
  echo "❌ Failed to create booking"
  exit 1
fi
echo "✅ Booking created: ID=$BOOKING_ID"

# STEP 2: Get summary (generates booking_ref)
echo -e "\n[STEP 2] Get booking summary..."
SUMMARY_RESPONSE=$(curl -s -X GET "$BASE_URL/bookings/$BOOKING_ID/summary" \
  -H "Content-Type: application/json")

echo "$SUMMARY_RESPONSE" | jq .

BOOKING_REF=$(echo "$SUMMARY_RESPONSE" | jq -r '.data.booking_ref')
if [ "$BOOKING_REF" = "null" ] || [ -z "$BOOKING_REF" ]; then
  echo "❌ Failed to generate booking reference"
  exit 1
fi
echo "✅ Booking reference generated: $BOOKING_REF"

TOTAL_AMOUNT=$(echo "$SUMMARY_RESPONSE" | jq -r '.data.total_amount')
echo "   Total amount: $TOTAL_AMOUNT KES"

# STEP 3: Confirm booking
echo -e "\n[STEP 3] Confirm and lock booking..."
CONFIRM_RESPONSE=$(curl -s -X PATCH "$BASE_URL/bookings/$BOOKING_ID/confirm" \
  -H "Content-Type: application/json" \
  -d "{
    \"adults\": 2,
    \"children\": 0,
    \"special_requests\": \"Early check-in preferred\"
  }")

echo "$CONFIRM_RESPONSE" | jq .

STATUS=$(echo "$CONFIRM_RESPONSE" | jq -r '.data.status')
if [ "$STATUS" != "PENDING_PAYMENT" ]; then
  echo "❌ Booking status not PENDING_PAYMENT: $STATUS"
  exit 1
fi
echo "✅ Booking confirmed and locked: status=$STATUS"

# STEP 4: Create payment intent
echo -e "\n[STEP 4] Create payment intent..."
INTENT_RESPONSE=$(curl -s -X POST "$BASE_URL/payment/intents" \
  -H "Content-Type: application/json" \
  -d "{
    \"booking_id\": $BOOKING_ID,
    \"amount\": $TOTAL_AMOUNT
  }")

echo "$INTENT_RESPONSE" | jq .

PAYMENT_INTENT_ID=$(echo "$INTENT_RESPONSE" | jq -r '.data.payment_intent_id')
if [ "$PAYMENT_INTENT_ID" = "null" ] || [ -z "$PAYMENT_INTENT_ID" ]; then
  echo "❌ Failed to create payment intent"
  exit 1
fi
echo "✅ Payment intent created: ID=$PAYMENT_INTENT_ID"

# STEP 5: Initiate STK Push
echo -e "\n[STEP 5] Initiate M-PESA STK Push..."
STK_RESPONSE=$(curl -s -X POST "$BASE_URL/payment/mpesa/stk" \
  -H "Content-Type: application/json" \
  -d "{
    \"booking_id\": $BOOKING_ID,
    \"amount\": $TOTAL_AMOUNT,
    \"phone_e164\": \"$GUEST_PHONE\"
  }")

echo "$STK_RESPONSE" | jq .

STK_REQUEST_ID=$(echo "$STK_RESPONSE" | jq -r '.data.stk_request_id')
if [ "$STK_REQUEST_ID" = "null" ] || [ -z "$STK_REQUEST_ID" ]; then
  echo "❌ Failed to initiate STK"
  exit 1
fi
CHECKOUT_REQUEST_ID=$(echo "$STK_RESPONSE" | jq -r '.data.checkout_request_id')
echo "✅ STK Push initiated: request_id=$STK_REQUEST_ID"
echo "   Checkout ID: $CHECKOUT_REQUEST_ID"

# STEP 6: Check payment status
echo -e "\n[STEP 6] Check payment status..."
STATUS_RESPONSE=$(curl -s -X GET "$BASE_URL/payment/intents/$PAYMENT_INTENT_ID" \
  -H "Content-Type: application/json")

echo "$STATUS_RESPONSE" | jq .
echo "⏳ Waiting for M-PESA callback (user should enter PIN)..."

# STEP 7: Manual submission (if STK times out)
echo -e "\n[STEP 7] Submit manual receipt (fallback)..."
MANUAL_RESPONSE=$(curl -s -X POST "$BASE_URL/payment/manual-entry" \
  -H "Content-Type: application/json" \
  -d "{
    \"booking_id\": $BOOKING_ID,
    \"mpesa_receipt_number\": \"LIK123ABC456\",
    \"amount\": $TOTAL_AMOUNT,
    \"phone_e164\": \"$GUEST_PHONE\"
  }")

echo "$MANUAL_RESPONSE" | jq .

SUBMISSION_ID=$(echo "$MANUAL_RESPONSE" | jq -r '.data.submission_id')
if [ "$SUBMISSION_ID" != "null" ] && [ -n "$SUBMISSION_ID" ]; then
  echo "✅ Manual submission created: ID=$SUBMISSION_ID"
  echo "⏳ Manual submission awaiting admin verification..."
fi

# STEP 8: Verify booking status
echo -e "\n[STEP 8] Final booking status..."
BOOKING_STATUS=$(curl -s -X GET "$BASE_URL/bookings/$BOOKING_ID" \
  -H "Content-Type: application/json")

echo "$BOOKING_STATUS" | jq .

echo -e "\n=========================================="
echo "TEST SUMMARY"
echo "=========================================="
echo "Booking ID: $BOOKING_ID"
echo "Booking Ref: $BOOKING_REF"
echo "Guest Email: $GUEST_EMAIL"
echo "Total Amount: $TOTAL_AMOUNT KES"
echo "Payment Intent: $PAYMENT_INTENT_ID"
echo "STK Request: $STK_REQUEST_ID"
echo ""
echo "NEXT STEPS:"
echo "1. User enters M-PESA PIN on their phone"
echo "2. M-PESA sends callback to /payment/mpesa/callback"
echo "3. BookingTransaction ledger entry created"
echo "4. Booking status updated to PAID"
echo "5. Receipt generated and email sent"
echo ""
echo "OR if STK fails:"
echo "1. Manual submission created (ID=$SUBMISSION_ID)"
echo "2. Admin verifies via /admin/payment/manual-submissions/{id}/verify"
echo "3. BookingTransaction ledger entry created"
echo "4. Booking status updated to PAID"
echo "5. Receipt generated and email sent"
echo "=========================================="
