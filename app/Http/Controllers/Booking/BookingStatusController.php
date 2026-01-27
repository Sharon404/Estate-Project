<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BookingStatusController extends Controller
{
    /**
    * GET /api/booking/{reference}/status
    * Returns current booking payment status for polling.
    */
    public function show(string $reference): JsonResponse
    {
        try {
            $booking = Booking::with([
                'bookingTransactions' => function ($q) {
                    $q->orderByDesc('posted_at');
                },
                'receipts' => function ($q) {
                    $q->orderByDesc('issued_at');
                }
            ])->where('booking_ref', $reference)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }

            $latestReceipt = $booking->receipts->first();
            $latestTx = $booking->bookingTransactions->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'status' => $booking->status,
                    'amount_paid' => (float) $booking->amount_paid,
                    'total_amount' => (float) $booking->total_amount,
                    'amount_due' => (float) $booking->amount_due,
                    'last_receipt' => $latestReceipt ? [
                        'receipt_no' => $latestReceipt->receipt_no,
                        'mpesa_receipt_number' => $latestReceipt->mpesa_receipt_number,
                        'amount' => (float) $latestReceipt->amount,
                        'issued_at' => $latestReceipt->issued_at?->toIso8601String(),
                    ] : null,
                    'last_payment' => $latestTx ? [
                        'source' => $latestTx->source,
                        'external_ref' => $latestTx->external_ref,
                        'amount' => (float) $latestTx->amount,
                        'posted_at' => $latestTx->posted_at?->toIso8601String(),
                    ] : null,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Booking status polling failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not retrieve booking status',
            ], 500);
        }
    }
}
