# Dashboard System - Quick Reference Guide

## Admin URLs

| Feature | URL | Description |
|---------|-----|-------------|
| Admin Dashboard | `/admin/dashboard` | KPI cards, recent bookings, payment summary |
| Bookings Manager | `/admin/bookings` | All bookings with search, filter by status/guest |
| Booking Details | `/admin/bookings/{id}` | Complete booking info with payment history |
| Analytics | `/admin/analytics` | 4 charts: revenue trend, bookings, payment status, methods |
| Audit Logs | `/admin/audit-logs` | Filtered event log with user/date/action filters |
| Audit Log Details | `/admin/audit-logs/{id}` | Detailed event view with changes and metadata |

## Staff URLs

| Feature | URL | Description |
|---------|-----|-------------|
| Staff Dashboard | `/staff/dashboard` | Today's check-ins/outs, upcoming 7 days |

---

## Admin Dashboard Components

### KPI Cards (4 metrics)
- **Total Revenue (This Month)** - Sum of all SUCCEEDED payment intents
- **Total Bookings (This Month)** - Count of bookings created this month
- **Completed Payments** - Count of SUCCEEDED payment intents
- **Pending/Failed Payments** - Count of PENDING or FAILED payment intents
- **Avg Nights** - Average number of nights per booking

### Bookings Table
- Shows paginated list (10 per page) of all bookings
- Columns: Reference, Guest, Property, Check-in/out, Nights, Amount, Status
- Sortable by creation date
- Click row to view full booking details

### Payment Summary
- Latest 15 payment intents
- Shows status (SUCCEEDED, PENDING, FAILED)
- Shows amount and payment method

### Transactions List
- Latest 10 booking transactions
- Shows transaction ID, booking, amount, status

---

## Analytics Dashboard

### Chart 1: Revenue Trend (Line Chart)
- Last 30 days of revenue per day
- X-axis: Dates, Y-axis: Amount in KES
- Shows daily revenue pattern

### Chart 2: Bookings Trend (Bar Chart)
- Last 30 days of bookings created per day
- X-axis: Dates, Y-axis: Number of bookings
- Shows booking volume pattern

### Chart 3: Payment Status (Doughnut Chart)
- Breakdown: SUCCEEDED (green), PENDING (yellow), FAILED (red)
- Shows payment success distribution

### Chart 4: Payment Methods (Pie Chart)
- Breakdown: STK Push vs C2B submissions
- Shows payment method preference

### Summary Stats
- Monthly total revenue
- Monthly total bookings
- Average payment value
- Payment success rate percentage

---

## Audit Logs

### Features
- **Filter by Action**: Created, Updated, Deleted, etc.
- **Filter by Resource**: Booking, Payment, User, etc.
- **Filter by Date**: From/To date range
- **Filter by User**: Who performed the action
- **View Details**: Click to see what changed (before/after values)
- **Pagination**: 50 logs per page

### Detail View Shows
- Timestamp of event
- User who performed action
- Action taken (Created, Updated, etc.)
- Resource affected (Booking #123, Payment #456)
- Changes made (field-by-field before/after)
- Metadata (IP address, user agent)

---

## Staff Dashboard

### 4 Tables

#### Today's Check-ins
- Guest name
- House/Property
- Check-in time
- Booking status
- Action: Click to view booking

#### Today's Check-outs
- Guest name
- House/Property
- Check-out time
- Status badge
- Action: Click to view booking

#### Upcoming Check-ins (Next 7 Days)
- Check-in date
- Guest name
- House/Property
- Number of nights
- Action: Click to view booking

#### Upcoming Check-outs (Next 7 Days)
- Check-out date
- Guest name
- House/Property
- Status badge
- Action: Click to view booking

### KPI Cards (4 metrics)
- **Today's Check-ins** - Count of bookings with check-in = today
- **Today's Check-outs** - Count of bookings with check-out = today
- **Upcoming Check-ins** - Count for next 7 days
- **Upcoming Check-outs** - Count for next 7 days

---

## Booking Detail View

### Guest Information Section
- Full name
- Email address
- Phone number

### Reservation Details Section
- Property/House name
- Check-in date & time
- Check-out date & time
- Number of nights
- Number of guests

### Payment Information Section
- Nightly rate
- Subtotal (rate × nights)
- Any taxes/fees
- Total amount to pay
- Payment status (SUCCEEDED, PENDING, FAILED)

### Transaction History Table (if payments made)
- Transaction ID
- Payment method (STK Push, C2B)
- Amount paid
- Date
- Status

### Booking Metadata
- Booking reference number
- Created date
- Last updated date
- Special requests (if any)
- Current booking status (PENDING_PAYMENT, CONFIRMED, CHECKED_IN, CHECKED_OUT)

---

## Role Permissions

### Admin Role (role:admin)
✅ Access `/admin/dashboard`
✅ Access `/admin/bookings`
✅ Access `/admin/analytics`
✅ Access `/admin/audit-logs`
✅ View all financial data
✅ See all bookings and payments
✅ View audit trail

### Staff Role (role:staff)
✅ Access `/staff/dashboard`
❌ Cannot access `/admin/*` routes
❌ Cannot view financial data
❌ Cannot see analytics
❌ Cannot view audit logs
✅ Can see today's and upcoming check-ins/outs only
✅ Can see basic booking info (guest, dates, property)

---

## Data Behind the Scenes

### What Data Is Tracked
- Every booking creation, update, deletion
- Every payment intent creation and status change
- Every transaction recorded
- Every receipt issued
- User actions (login, logout, data modification)
- All changes with before/after values

### Data Retention
- Audit logs are append-only (cannot be deleted)
- All historical data preserved
- Complete audit trail maintained for compliance

### Queries Run (Performance)
- Revenue aggregations cached for 1 hour
- Booking counts updated in real-time
- Charts generated on-demand (fast due to date grouping)
- Audit logs paginated (50 per page) for performance

---

## Troubleshooting

### Admin Dashboard Shows No Data
- Check if user has `admin` role (case-insensitive)
- Verify at least one booking/payment exists this month
- Check cache: `php artisan optimize:clear`

### Analytics Charts Not Rendering
- Verify Chart.js library loaded (check browser console)
- Check if booking data exists for last 30 days
- Ensure browser JavaScript is enabled

### Staff Dashboard Empty
- Check if any bookings have today's check-in/out dates
- Verify booking statuses are CONFIRMED or PENDING_PAYMENT
- Check staff user has `staff` role

### Audit Logs Not Showing
- Verify `audit.request` middleware is in web.php
- Check if audit logging is enabled in config
- Run: `php artisan optimize:clear`

---

## API Response Examples

### GET /admin/bookings JSON Response
```json
{
  "data": [
    {
      "id": 1,
      "booking_reference": "TAS-2024-001",
      "guest": {"full_name": "John Doe", "email": "john@example.com"},
      "property": {"name": "Beach Villa"},
      "check_in": "2024-01-15T14:00:00",
      "check_out": "2024-01-17T10:00:00",
      "nights": 2,
      "total_amount": 10000,
      "booking_status": "CONFIRMED",
      "payment_status": "SUCCEEDED"
    }
  ],
  "links": {"first": "...", "last": "...", "next": "..."}
}
```

### GET /admin/analytics JSON Response
```json
{
  "revenuePerDay": [
    {"date": "2024-01-01", "revenue": 45000},
    {"date": "2024-01-02", "revenue": 32000}
  ],
  "bookingsPerDay": [
    {"date": "2024-01-01", "count": 3},
    {"date": "2024-01-02", "count": 2}
  ],
  "paymentSuccessFailed": [
    {"status": "SUCCEEDED", "count": 150},
    {"status": "FAILED", "count": 5}
  ],
  "stkVsC2b": [
    {"method": "STK_PUSH", "count": 100, "amount": 500000},
    {"method": "C2B", "count": 55, "amount": 275000}
  ]
}
```

---

## Related Documentation
- [DASHBOARD_IMPLEMENTATION.md](./DASHBOARD_IMPLEMENTATION.md) - Full technical details
- Payment Flow: [Payment Routes Guide](./docs/payment-routes.md)
- Booking Flow: [Booking Routes Guide](./docs/booking-routes.md)
