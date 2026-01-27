<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #652482;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f5f0;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .info-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .label {
            font-weight: bold;
            color: #652482;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            color: #222;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #652482;
            margin-top: 20px;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #decfbc;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">New Contact Form Submission</h1>
        <p style="margin: 10px 0 0 0;">Tausi Holiday & Getaway Homes</p>
    </div>
    
    <div class="content">
        <p>You have received a new message from your website contact form:</p>
        
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $contact->name }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">
                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
            </span>
        </div>
        
        @if($contact->phone)
        <div class="info-row">
            <span class="label">Phone:</span>
            <span class="value">{{ $contact->phone }}</span>
        </div>
        @endif
        
        <div class="info-row">
            <span class="label">Submitted:</span>
            <span class="value">{{ $contact->created_at->format('F d, Y \a\t h:i A') }}</span>
        </div>
        
        @if($contact->ip_address)
        <div class="info-row">
            <span class="label">IP Address:</span>
            <span class="value">{{ $contact->ip_address }}</span>
        </div>
        @endif
        
        <div class="message-box">
            <span class="label">Message:</span>
            <div class="value" style="white-space: pre-wrap;">{{ $contact->message }}</div>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #fff; border-radius: 4px; text-align: center;">
            <strong>Reply directly to this email to respond to {{ $contact->name }}</strong>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was automatically generated from your website contact form.</p>
        <p>&copy; {{ date('Y') }} Tausi Holiday & Getaway Homes. All rights reserved.</p>
    </div>
</body>
</html>
