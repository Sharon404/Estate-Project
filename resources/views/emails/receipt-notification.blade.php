<!-- Email template for receipt notification -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .receipt-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #333;
        }
        .info-value {
            color: #666;
            text-align: right;
        }
        .amount-row .info-value {
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }
        .greeting {
            margin: 20px 0;
            font-size: 16px;
        }
        .greeting strong {
            color: #007bff;
        }
        .details {
            margin: 20px 0;
            line-height: 1.8;
        }
        .details-item {
            margin: 10px 0;
        }
        .details-label {
            font-weight: 600;
            color: #333;
        }
        .details-value {
            color: #666;
            margin-left: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .footer-link {
            color: #007bff;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .cta-button:hover {
            background: #0056b3;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
            font-size: 14px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            color: #155724;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Payment Confirmed</h1>
            <p>Receipt #{{ $receiptNo }}</p>
        </div>

        <!-- Greeting -->
        <div class="greeting">
            Hello <strong>{{ $guestName }}</strong>,
            <p style="margin: 10px 0 0 0;">Thank you for your payment. Your receipt has been generated and is attached below.</p>
        </div>

        <!-- Success Message -->
        <div class="success">
            Your payment has been successfully processed and recorded. You can download or print this receipt for your records.
        </div>

        <!-- Receipt Information -->
        <div class="receipt-info">
            <div class="info-row">
                <span class="info-label">Booking Reference</span>
                <span class="info-value">{{ $bookingRef }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Receipt Number</span>
                <span class="info-value">{{ $receiptNo }}</span>
            </div>
            <div class="info-row amount-row">
                <span class="info-label">Payment Amount</span>
                <span class="info-value">{{ $currency }} {{ number_format($amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date Issued</span>
                <span class="info-value">{{ $issuedAt }}</span>
            </div>
        </div>

        <!-- Details -->
        <div class="details">
            <p style="color: #666; margin: 0 0 15px 0;">Here are the details of your booking:</p>
            <div class="details-item">
                <span class="details-label">Booking Reference:</span>
                <span class="details-value">{{ $bookingRef }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Check-in Date:</span>
                <span class="details-value">{{ $booking->check_in->format('M d, Y') }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Check-out Date:</span>
                <span class="details-value">{{ $booking->check_out->format('M d, Y') }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Total Booking Amount:</span>
                <span class="details-value">{{ $currency }} {{ number_format($booking->total_amount, 2) }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Amount Paid:</span>
                <span class="details-value">{{ $currency }} {{ number_format($booking->amount_paid, 2) }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Remaining Balance:</span>
                <span class="details-value">{{ $currency }} {{ number_format($booking->amount_due, 2) }}</span>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('payment.receipt-get', ['receiptNo' => $receiptNo]) }}" class="cta-button">
                View Full Receipt
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this email.</p>
            <p>If you have questions about your booking or payment, please <a href="#" class="footer-link">contact support</a>.</p>
            <p>&copy; {{ date('Y') }} Estate Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
