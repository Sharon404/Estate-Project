<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\MpesaC2BService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaC2BController extends Controller
{
    protected MpesaC2BService $c2bService;

    public function __construct(MpesaC2BService $c2bService)
    {
        $this->c2bService = $c2bService;
    }

    /**
     * Validation URL handler for M-PESA C2B.
     * Safaricom sends a validation request before posting the transaction.
     */
    public function validateCallback(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('M-PESA C2B Validation received', [
            'payload' => $payload,
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
        ]);

        try {
            $result = $this->c2bService->validate($payload);
            Log::info('M-PESA C2B Validation response', ['result' => $result]);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('M-PESA C2B Validation error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'System error'
            ]);
        }
    }

    /**
     * Confirmation URL handler for M-PESA C2B.
     * Safaricom posts the confirmed transaction here.
     */
    public function confirmCallback(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('M-PESA C2B Confirmation received', [
            'payload' => $payload,
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
        ]);

        try {
            $result = $this->c2bService->confirm($payload);
            Log::info('M-PESA C2B Confirmation response', ['result' => $result]);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('M-PESA C2B Confirmation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload,
            ]);
            // Always ACK to Safaricom even on error
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Received'
            ]);
        }
    }
}
