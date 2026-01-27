<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\PaymentIntent;
use App\Services\ReceiptService;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MpesaC2BService
{
    /**
     * Register C2B Validation and Confirmation URLs with Daraja.
     */
    public function registerUrls(): array
    {
        $registerUrl = config('mpesa.c2b_register_url');
        $shortCode = config('mpesa.business_shortcode');
        $validationUrl = config('mpesa.c2b_validation_url');
        $confirmationUrl = config('mpesa.c2b_confirmation_url');
        $verifySSL = config('mpesa.verify_ssl', true);

        $token = $this->getAccessToken();

        $payload = [
            'ShortCode' => $shortCode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        Log::info('Registering M-PESA C2B URLs', [
            'register_url' => $registerUrl,
            'environment' => config('mpesa.environment'),
            'shortcode' => config('mpesa.business_shortcode'),
        ]);

        $response = Http::withToken($token)
            ->acceptJson()
            ->when(!$verifySSL, function ($http) {
                return $http->withoutVerifying();
            })
            ->timeout(30)
            ->post($registerUrl, $payload);

        if (!$response->successful()) {
            Log::error('Failed to register C2B URLs', [
                'status' => $response->status(),
                'environment' => config('mpesa.environment'),
            ]);
            return [
                'success' => false,
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        $body = $response->json();

        Log::info('C2B URLs registered successfully', [
            'environment' => config('mpesa.environment'),
        ]);

        return [
            'success' => true,
            'response' => $body,
        ];
    }

    /**
     * Validate a C2B transaction before confirmation.
     * Returns Daraja-compatible ResultCode/ResultDesc.
     */
    public function validate(array $payload): array
    {
        $billRef = $payload['BillRefNumber'] ?? null;
        $amount = isset($payload['TransAmount']) ? (float) $payload['TransAmount'] : null;

        if (!$billRef || !$amount) {
            return ['ResultCode' => 1, 'ResultDesc' => 'Missing BillRefNumber or TransAmount'];
        }

        $booking = Booking::where('booking_ref', $billRef)->first();
        if (!$booking) {
            return ['ResultCode' => 1, 'ResultDesc' => 'Booking not found'];
        }

        if (in_array($booking->status, ['CANCELLED', 'EXPIRED'])) {
            return ['ResultCode' => 1, 'ResultDesc' => 'Booking is not payable'];
        }

        if ($amount <= 0) {
            return ['ResultCode' => 1, 'ResultDesc' => 'Invalid amount'];
        }

        return ['ResultCode' => 0, 'ResultDesc' => 'Accepted'];
    }

    /**
     * Confirm a C2B transaction.
     * Idempotent: if transaction already exists, does nothing and returns success.
     */
    public function confirm(array $payload): array
    {
        $transId = $payload['TransID'] ?? null;
        $amount = isset($payload['TransAmount']) ? (float) $payload['TransAmount'] : null;
        $msisdn = $payload['MSISDN'] ?? null;
        $billRef = $payload['BillRefNumber'] ?? null;

        if (!$transId || !$billRef || !$amount) {
            Log::error('C2B confirm missing required fields', compact('transId','billRef','amount'));
            return ['ResultCode' => 0, 'ResultDesc' => 'Received'];
        }

        // Idempotency: check if a transaction with this external_ref already exists
        $existing = BookingTransaction::where('external_ref', $transId)->first();
        if ($existing) {
            Log::info('C2B confirm duplicate ignored', ['trans_id' => $transId]);
            return ['ResultCode' => 0, 'ResultDesc' => 'Received'];
        }

        $booking = Booking::where('booking_ref', $billRef)->first();
        if (!$booking) {
            Log::error('C2B confirm booking not found', ['bill_ref' => $billRef]);
            return ['ResultCode' => 0, 'ResultDesc' => 'Received'];
        }

        try {
            DB::transaction(function () use ($booking, $amount, $msisdn, $transId) {
                // Create ledger entry
                $transaction = BookingTransaction::create([
                    'booking_id' => $booking->id,
                    'payment_intent_id' => $this->createC2bIntent($booking, $amount)->id,
                    'type' => 'PAYMENT',
                    'source' => 'MPESA_C2B',
                    'external_ref' => $transId,
                    'amount' => $amount,
                    'currency' => $booking->currency,
                    'meta' => [
                        'phone_e164' => $msisdn ? $this->normalizeMsisdnToE164($msisdn) : null,
                        'transaction_id' => $transId,
                        'method' => 'C2B',
                    ],
                ]);

                // Update payment intent status
                $transaction->paymentIntent->update(['status' => 'SUCCEEDED']);

                // Recompute booking amounts from ledger
                $totalPaid = BookingTransaction::where('booking_id', $booking->id)
                    ->where('type', 'PAYMENT')
                    ->sum('amount');

                $amountDue = $booking->total_amount - $totalPaid;
                $bookingStatus = $amountDue <= 0 ? 'PAID' : 'PARTIALLY_PAID';

                $booking->update([
                    'amount_paid' => $totalPaid,
                    'amount_due' => max(0, $amountDue),
                    'status' => $bookingStatus,
                ]);

                // Create receipt and queue email
                $receiptService = new ReceiptService();
                $receipt = $receiptService->createC2bReceipt($transaction, $transId);

                try {
                    AuditService::logPaymentSucceeded($transaction->paymentIntent, $transId);
                } catch (\Exception $e) {
                    Log::error('Failed to log C2B payment audit', ['error' => $e->getMessage()]);
                }

                Log::info('C2B payment processed', [
                    'booking_id' => $booking->id,
                    'booking_status' => $bookingStatus,
                    'amount_paid' => $totalPaid,
                    'amount_due' => max(0, $amountDue),
                    'transaction_id' => $transaction->id,
                    'receipt_no' => $receipt->receipt_no,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('C2B confirmation processing failed', ['error' => $e->getMessage(), 'payload' => $payload]);
        }

        // Always ACK to Safaricom
        return ['ResultCode' => 0, 'ResultDesc' => 'Received'];
    }

    private function createC2bIntent(Booking $booking, float $amount): PaymentIntent
    {
        // Create a fresh intent per C2B transaction to preserve ledger linkage
        return PaymentIntent::create([
            'booking_id' => $booking->id,
            'intent_ref' => 'PI-' . Str::upper(Str::random(10)),
            'method' => 'MPESA_C2B',
            'amount' => $amount,
            'currency' => $booking->currency,
            'status' => 'PENDING',
            'metadata' => [
                'booking_ref' => $booking->booking_ref,
            ],
        ]);
    }

    private function normalizeMsisdnToE164(string $msisdn): string
    {
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
        if (Str::startsWith($msisdn, '0')) {
            $msisdn = '254' . substr($msisdn, 1);
        }
        if (!Str::startsWith($msisdn, '254')) {
            $msisdn = '254' . $msisdn;
        }
        return '+' . $msisdn;
    }

    /**
     * Obtain OAuth access token for C2B registration calls.
     */
    private function getAccessToken(): string
    {
        if (config('mpesa.mock_mode')) {
            Log::info('Using mock M-PESA token for C2B registration');
            return config('mpesa.mock_access_token', 'mock_token_12345');
        }

        $authUrl = config('mpesa.auth_url');
        $consumerKey = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');
        $verifySSL = config('mpesa.verify_ssl', true);

        Log::info('Retrieving M-PESA OAuth token for C2B', [
            'auth_url' => $authUrl,
            'consumer_key' => substr($consumerKey, 0, 4) . '***',
            'environment' => config('mpesa.environment'),
        ]);

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->accept('application/json')
            ->when(!$verifySSL, function ($http) {
                return $http->withoutVerifying();
            })
            ->timeout(30)
            ->get($authUrl);

        if (!$response->successful()) {
            throw new \Exception("Failed to get M-PESA access token: {$response->body()}");
        }

        $token = $response->json('access_token');
        if (!$token) {
            throw new \Exception('M-PESA OAuth response missing access_token field');
        }

        return $token;
    }
}
