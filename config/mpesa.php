<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-PESA Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for M-PESA STK Push integration via Daraja API
    |
    */

    'mock_mode' => env('MPESA_MOCK_MODE', false),

    // OAuth2 Credentials
    'consumer_key' => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
    'passkey' => env('MPESA_PASSKEY', ''),

    // API URLs
    'auth_url' => env('MPESA_AUTH_URL', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'),
    'stk_push_url' => env('MPESA_STK_PUSH_URL', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'),
    'c2b_register_url' => env('MPESA_C2B_REGISTER_URL', 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'),

    // Business Details
    'business_shortcode' => env('MPESA_BUSINESS_SHORTCODE', '174379'),
    'business_name' => env('MPESA_BUSINESS_NAME', 'Holiday Rentals'),
    'till_number' => env('MPESA_TILL_NUMBER', '174379'),
    'company_name' => env('MPESA_COMPANY_NAME', 'Holiday Rentals'),

    // Mock tokens (for testing)
    'mock_access_token' => env('MPESA_MOCK_ACCESS_TOKEN', 'mock_token_12345'),

    // STK Callback URL (set in environment)
    'callback_url' => env('MPESA_CALLBACK_URL', 'http://localhost:8001/payment/mpesa/callback'),

    // C2B Validation and Confirmation URLs (publicly accessible)
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL', 'http://localhost:8001/api/payment/c2b/validate'),
    'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL', 'http://localhost:8001/api/payment/c2b/confirm'),

    // Timeout for payment polling (minutes)
    'stk_timeout_minutes' => env('MPESA_STK_TIMEOUT_MINUTES', 5),
];
