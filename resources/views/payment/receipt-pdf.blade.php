<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt - {{ $booking_ref }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #652482;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #652482;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            color: #222;
            margin-bottom: 10px;
        }
        .receipt-ref {
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #652482;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #decfbc;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-label {
            display: table-cell;
            width: 40%;
            color: #666;
            font-size: 11px;
        }
        .info-value {
            display: table-cell;
            width: 60%;
            font-weight: bold;
            color: #222;
        }
        .amount-section {
            background-color: #f8f5f0;
            padding: 20px;
            margin: 30px 0;
            border-radius: 5px;
            text-align: center;
        }
        .amount-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #652482;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .status-paid {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">TAUSI HOLIDAY & GETAWAY HOMES</div>
        <div class="company-tagline">Nanyuki, Kenya</div>
        <div class="receipt-title">PAYMENT RECEIPT</div>
        <div class="receipt-ref">Booking Reference: {{ $booking_ref }}</div>
        <div style="margin-top: 10px;">
            <span class="status-paid">✓ PAID</span>
        </div>
    </div>

    <!-- Guest Information -->
    <div class="section">
        <div class="section-title">Guest Information</div>
        <div class="info-row">
            <div class="info-label">Guest Name:</div>
            <div class="info-value">{{ $guest_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone Number:</div>
            <div class="info-value">{{ $guest_phone }}</div>
        </div>
        @if($guest_email)
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $guest_email }}</div>
        </div>
        @endif
    </div>

    <!-- Booking Details -->
    <div class="section">
        <div class="section-title">Booking Details</div>
        <div class="info-row">
            <div class="info-label">Property:</div>
            <div class="info-value">{{ $property_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Check-in Date:</div>
            <div class="info-value">{{ $check_in }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Check-out Date:</div>
            <div class="info-value">{{ $check_out }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Number of Nights:</div>
            <div class="info-value">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</div>
        </div>
    </div>

    <!-- Amount Paid -->
    <div class="amount-section">
        <div class="amount-label">Total Amount Paid</div>
        <div class="amount-value">{{ $currency }} {{ $amount_paid }}</div>
    </div>

    <!-- Payment Information -->
    <div class="section">
        <div class="section-title">Payment Information</div>
        <div class="info-row">
            <div class="info-label">Payment Method:</div>
            <div class="info-value">{{ $payment_method }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">M-PESA Transaction Code:</div>
            <div class="info-value">{{ $mpesa_receipt }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Payment Date & Time:</div>
            <div class="info-value">{{ $payment_date }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Receipt Generated:</div>
            <div class="info-value">{{ $generated_at }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for choosing Tausi Holiday & Getaway Homes!</strong></p>
        <p style="margin-top: 10px;">This is an automated receipt. For inquiries, contact us at Tausi Holiday & Getaway Homes, Nanyuki, Kenya</p>
        <p style="margin-top: 10px;">Generated on {{ $generated_at }}</p>
    </div>
</body>
</html>
