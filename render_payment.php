<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = App\Models\Booking::find(69);
if (!$booking) {
    echo "Booking not found\n";
    exit(1);
}

echo view('payment.payment', compact('booking'))->render();
