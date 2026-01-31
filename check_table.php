<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "mpesa_manual_submissions table structure:\n";
echo str_repeat('-', 50) . "\n";

$columns = DB::select('SHOW COLUMNS FROM mpesa_manual_submissions');

foreach ($columns as $col) {
    echo sprintf("%-25s %s\n", $col->Field, $col->Type);
}
