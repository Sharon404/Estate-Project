<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\MpesaC2BService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mpesa:c2b:register', function (MpesaC2BService $service) {
    $result = $service->registerUrls();

    if ($result['success'] ?? false) {
        $this->info('C2B URLs registered successfully');
        $this->line(json_encode($result['response'] ?? [], JSON_PRETTY_PRINT));
    } else {
        $this->error('Failed to register C2B URLs');
        $this->line(json_encode($result, JSON_PRETTY_PRINT));
    }
})->purpose('Register M-PESA C2B validation and confirmation URLs with Safaricom');

Artisan::command('mpesa:c2b:test {bookingRef}', function (string $bookingRef, MpesaC2BService $service) {
    $this->info("Simulating C2B callback for booking: {$bookingRef}");
    
    // Simulate a C2B confirmation payload
    $payload = [
        'TransactionType' => 'Pay Bill',
        'TransID' => 'TEST' . strtoupper(\Illuminate\Support\Str::random(10)),
        'TransTime' => now()->format('YmdHis'),
        'TransAmount' => '7.00',
        'BusinessShortCode' => config('mpesa.business_shortcode'),
        'BillRefNumber' => $bookingRef,
        'InvoiceNumber' => '',
        'OrgAccountBalance' => '',
        'ThirdPartyTransID' => '',
        'MSISDN' => '254798037574',
        'FirstName' => 'Test',
        'MiddleName' => '',
        'LastName' => 'User',
    ];
    
    $this->info('Payload: ' . json_encode($payload, JSON_PRETTY_PRINT));
    
    try {
        $result = $service->confirm($payload);
        $this->info('Result: ' . json_encode($result, JSON_PRETTY_PRINT));
        
        // Check booking status
        $booking = \App\Models\Booking::where('booking_ref', $bookingRef)->first();
        if ($booking) {
            $this->info("Booking Status: {$booking->status}");
            $this->info("Amount Paid: {$booking->amount_paid}");
            $this->info("Amount Due: {$booking->amount_due}");
        } else {
            $this->error("Booking not found: {$bookingRef}");
        }
    } catch (\Exception $e) {
        $this->error('Error: ' . $e->getMessage());
        $this->line($e->getTraceAsString());
    }
})->purpose('Simulate a C2B callback for testing');
