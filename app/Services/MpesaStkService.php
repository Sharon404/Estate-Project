<?php

namespace App\Services;

use App\Models\MpesaStkRequest;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class MpesaStkService
{
    /**
     * Initiate M-PESA STK Push.
     *
     * Flow:
     * 1. Validate payment intent
     * 2. Build STK request payload
     * 3. Call M-PESA API
     * 4. Store request + response payloads
     * 5. Mark intent as PENDING
     *
     * @param PaymentIntent $paymentIntent
     * @param string $phoneE164 Customer phone in E.164 format
     * @return MpesaStkRequest
     * @throws \Exception On validation or API errors
     */
    public function initiateStk(PaymentIntent $paymentIntent, string $phoneE164): MpesaStkRequest
    {
        // Validate intent is in correct status
        if ($paymentIntent->status !== 'INITIATED') {
            throw new \Exception(
                "Cannot initiate STK for payment intent in {$paymentIntent->status} status"
            );
        }

        // Get booking for reference
        $booking = $paymentIntent->booking;

        // Build STK request payload
        $requestPayload = [
            'BusinessShortCode' => config('mpesa.business_shortcode'),
            'Password' => $this->generatePassword(),
            'Timestamp' => now()->format('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $paymentIntent->amount,
            'PartyA' => $phoneE164,
            'PartyB' => config('mpesa.business_shortcode'),
            'PhoneNumber' => $phoneE164,
            'CallBackURL' => route('mpesa.callback'),
            'AccountReference' => $booking->booking_ref,
            'TransactionDesc' => "Payment for booking {$booking->booking_ref}",
        ];

        // Call M-PESA STK Push API
        $responsePayload = $this->callMpesaApi($requestPayload);

        // Check for API errors
        if (Arr::get($responsePayload, 'ResponseCode') !== '0') {
            throw new \Exception(
                'M-PESA API Error: ' . Arr::get($responsePayload, 'ResponseDescription')
            );
        }

        // Store STK request in database
        $stkRequest = MpesaStkRequest::create([
            'payment_intent_id' => $paymentIntent->id,
            'phone_e164' => $phoneE164,
            'business_shortcode' => config('mpesa.business_shortcode'),
            'account_reference' => $booking->booking_ref,
            'transaction_desc' => "Payment for booking {$booking->booking_ref}",
            'merchant_request_id' => Arr::get($responsePayload, 'MerchantRequestID'),
            'checkout_request_id' => Arr::get($responsePayload, 'CheckoutRequestID'),
            'request_payload' => $requestPayload,
            'response_payload' => $responsePayload,
            'status' => 'REQUESTED',
        ]);

        // Mark payment intent as PENDING
        $paymentIntent->update(['status' => 'PENDING']);

        return $stkRequest;
    }

    /**
     * Call M-PESA STK Push API.
     * In production, this calls Safaricom's actual API.
     * For testing, configure mock responses in .env
     *
     * @param array $payload
     * @return array
     */
    private function callMpesaApi(array $payload): array
    {
        $apiUrl = config('mpesa.stk_push_url');
        $bearerToken = $this->getAccessToken();

        $response = Http::withToken($bearerToken)
            ->timeout(30)
            ->post($apiUrl, $payload);

        if (!$response->successful()) {
            throw new \Exception(
                "M-PESA API call failed: HTTP {$response->status()} - {$response->body()}"
            );
        }

        return $response->json();
    }

    /**
     * Get M-PESA OAuth2 access token.
     * In production, calls Safaricom's OAuth endpoint.
     * For testing, use mock token from config.
     *
     * @return string
     */
    private function getAccessToken(): string
    {
        if (config('mpesa.mock_mode')) {
            return config('mpesa.mock_access_token');
        }

        $authUrl = config('mpesa.auth_url');
        $consumerKey = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->timeout(30)
            ->post($authUrl);

        if (!$response->successful()) {
            throw new \Exception("Failed to get M-PESA access token: {$response->body()}");
        }

        return $response->json('access_token');
    }

    /**
     * Generate M-PESA password for API authentication.
     * Password = base64(BusinessShortCode + Passkey + Timestamp)
     *
     * @return string
     */
    private function generatePassword(): string
    {
        $businessShortCode = config('mpesa.business_shortcode');
        $passkey = config('mpesa.passkey');
        $timestamp = now()->format('YmdHis');

        $data = $businessShortCode . $passkey . $timestamp;
        return base64_encode($data);
    }

    /**
     * Mark STK request as accepted (after API returns success).
     *
     * @param MpesaStkRequest $stkRequest
     * @return MpesaStkRequest
     */
    public function markAccepted(MpesaStkRequest $stkRequest): MpesaStkRequest
    {
        $stkRequest->update(['status' => 'ACCEPTED']);
        return $stkRequest;
    }

    /**
     * Mark STK request as timed out (if no callback received).
     *
     * @param MpesaStkRequest $stkRequest
     * @return MpesaStkRequest
     */
    public function markTimeout(MpesaStkRequest $stkRequest): MpesaStkRequest
    {
        $stkRequest->update(['status' => 'TIMEOUT']);
        $stkRequest->paymentIntent->update(['status' => 'FAILED']);
        return $stkRequest;
    }
}
