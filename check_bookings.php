<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Latest bookings:\n";
$bookings = \App\Models\Booking::orderBy('id', 'desc')->limit(3)->get(['id', 'booking_ref', 'property_id', 'status', 'total_amount', 'created_at']);
foreach ($bookings as $booking) {
    echo "ID: {$booking->id} | Ref: {$booking->booking_ref} | Property: {$booking->property_id} | Status: {$booking->status} | Amount: {$booking->total_amount} | Created: {$booking->created_at}\n";
}

echo "\nLatest payment intents:\n";
$intents = \App\Models\PaymentIntent::orderBy('id', 'desc')->limit(3)->get(['id', 'booking_id', 'amount', 'status', 'created_at']);
foreach ($intents as $intent) {
    echo "ID: {$intent->id} | Booking: {$intent->booking_id} | Amount: {$intent->amount} | Status: {$intent->status} | Created: {$intent->created_at}\n";
}
