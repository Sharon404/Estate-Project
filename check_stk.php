<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Latest STK requests:\n";
$stk = \App\Models\MpesaStkRequest::orderBy('id', 'desc')->limit(3)->get(['id', 'payment_intent_id', 'phone_e164', 'status', 'merchant_request_id', 'checkout_request_id', 'created_at']);
foreach ($stk as $s) {
    echo "ID: {$s->id} | Intent: {$s->payment_intent_id} | Phone: {$s->phone_e164} | Status: {$s->status} | MerchantReq: {$s->merchant_request_id} | CheckoutReq: {$s->checkout_request_id} | Created: {$s->created_at}\n";
}
