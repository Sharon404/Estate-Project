<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\InitiateStkRequest;
use App\Models\MpesaStkRequest;
use App\Services\MpesaStkService;
use App\Services\MpesaCallbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    protected MpesaStkService $stkService;
    protected MpesaCallbackService $callbackService;

    public function __construct(
        MpesaStkService $stkService,
        MpesaCallbackService $callbackService
    ) {
        $this->stkService = $stkService;
        $this->callbackService = $callbackService;
    }

    /**
     * Initiate M-PESA STK Push.
     *
     * POST /payment/mpesa/stk
     *
     * @param InitiateStkRequest $request
     * @return JsonResponse
     */
    public function initiateStk(InitiateStkRequest $request): JsonResponse
    {
        try {
            \Log::info('STK initiation request received', [
                'payment_intent_id' => $request->validated('payment_intent_id'),
                'phone_e164' => $request->validated('phone_e164'),
            ]);

            $paymentIntent = \App\Models\PaymentIntent::findOrFail(
                $request->validated('payment_intent_id')
            );

            \Log::info('Payment intent found', [
                'intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount,
                'status' => $paymentIntent->status,
            ]);

            $stkRequest = $this->stkService->initiateStk(
                $paymentIntent,
                $request->validated('phone_e164')
            );

            \Log::info('STK request successful', [
                'stk_request_id' => $stkRequest->id,
                'checkout_request_id' => $stkRequest->checkout_request_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'STK Push initiated successfully. Please enter PIN on your phone.',
                'data' => [
                    'stk_request_id' => $stkRequest->id,
                    'checkout_request_id' => $stkRequest->checkout_request_id,
                    'merchant_request_id' => $stkRequest->merchant_request_id,
                    'phone_e164' => $stkRequest->phone_e164,
                    'amount' => $stkRequest->paymentIntent->amount,
                    'status' => $stkRequest->status,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('STK Push initiation failed', [
                'payment_intent_id' => $request->validated('payment_intent_id') ?? 'not provided',
                'phone' => $request->validated('phone_e164') ?? 'not provided',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate STK Push',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle M-PESA STK callback (webhook).
     *
     * POST /payment/mpesa/callback
     * This is called by Safaricom's servers, not by the client.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Log callback for debugging
        Log::info('M-PESA STK Callback received', [
            'checkout_request_id' => data_get($payload, 'Body.stkCallback.CheckoutRequestID'),
            'result_code' => data_get($payload, 'Body.stkCallback.ResultCode'),
        ]);

        try {
            // Find STK request by checkout_request_id
            $checkoutRequestId = data_get($payload, 'Body.stkCallback.CheckoutRequestID');
            
            if (!$checkoutRequestId) {
                throw new \Exception('Missing CheckoutRequestID in callback');
            }

            $stkRequest = MpesaStkRequest::where('checkout_request_id', $checkoutRequestId)
                ->firstOrFail();

            // Store callback FIRST (before processing)
            $callback = $this->callbackService->storeCallback($stkRequest, $payload);

            // Process callback (creates ledger, updates intent/booking)
            $result = $this->callbackService->processCallback($callback);

            // Log result
            Log::info('M-PESA callback processed', $result);

            // Return success response to Safaricom
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted',
            ], 200);
        } catch (\Exception $e) {
            Log::error('M-PESA callback processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            // Return error response to Safaricom (they'll retry)
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Query STK Push status.
     * Used by client to poll for payment confirmation.
     *
     * GET /payment/mpesa/stk/{stkRequestId}/status
     *
     * @param MpesaStkRequest $stkRequest
     * @return JsonResponse
     */
    public function stkStatus(MpesaStkRequest $stkRequest): JsonResponse
    {
        $paymentIntent = $stkRequest->paymentIntent;
        $booking = $paymentIntent->booking;

        return response()->json([
            'success' => true,
            'data' => [
                'stk_request_id' => $stkRequest->id,
                'stk_status' => $stkRequest->status,
                'payment_intent_status' => $paymentIntent->status,
                'booking_status' => $booking->status,
                'amount_paid' => $booking->amount_paid,
                'amount_due' => $booking->amount_due,
                'has_callback' => $stkRequest->mpesaStkCallbacks()->exists(),
                'last_callback_at' => $stkRequest->mpesaStkCallbacks()
                    ->latest('received_at')
                    ->first()
                    ?->created_at,
            ],
        ]);
    }
}
