<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-PESA Configuration - Production
    |--------------------------------------------------------------------------
    |
    | Configuration for M-PESA STK Push integration via Daraja API
    | Environment: production (switched from sandbox)
    | All credentials loaded from .env - DO NOT hardcode
    |
    */

    'mock_mode' => env('MPESA_MOCK_MODE', false),
    'environment' => env('MPESA_ENV', 'production'),

    // OAuth2 Credentials (from .env)
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'passkey' => env('MPESA_PASSKEY'),
    'initiator_name' => env('MPESA_INITIATOR_NAME'),
    'initiator_password' => env('MPESA_INITIATOR_PASSWORD'),

    // Base URL resolver (production)
    'base_url' => env('MPESA_BASE_URL', 'https://api.safaricom.co.ke'),

    // API URLs built from base_url
    'auth_url' => env('MPESA_AUTH_URL') ?: 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
    'stk_push_url' => env('MPESA_STK_PUSH_URL') ?: 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
    'c2b_register_url' => env('MPESA_C2B_REGISTER_URL') ?: 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl',

    // Business Details
    'business_shortcode' => env('MPESA_BUSINESS_SHORTCODE', '174379'),
    'business_name' => env('MPESA_BUSINESS_NAME', 'Holiday Rentals'),
    'till_number' => env('MPESA_TILL_NUMBER', '174379'),
    'company_name' => env('MPESA_COMPANY_NAME', 'Holiday Rentals'),

    // STK Callback URL (must be HTTPS and publicly accessible in production)
    'callback_url' => env('MPESA_CALLBACK_URL'),

    // C2B Validation and Confirmation URLs (must be HTTPS and publicly accessible)
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL'),
    'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),

    // Timeout for payment polling (minutes)
    'stk_timeout_minutes' => env('MPESA_STK_TIMEOUT_MINUTES', 5),

    // SSL verification (false for testing via ngrok, true for production)
    'verify_ssl' => env('MPESA_VERIFY_SSL', true),
];
