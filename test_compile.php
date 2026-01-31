<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$paymentPath = 'resources/views/payment/payment.blade.php';
$content = file_get_contents($paymentPath);
$firstBytes = array_map(fn($b) => ord($b), str_split(substr($content, 0, 10)));
echo "Payment file first 10 bytes: " . implode(', ', $firstBytes) . "\n";
echo "UTF-8 BOM: " . ($firstBytes[0] === 239 && $firstBytes[1] === 187 && $firstBytes[2] === 191 ? "YES" : "NO") . "\n";
echo "UTF-16 LE BOM: " . ($firstBytes[0] === 255 && $firstBytes[1] === 254 ? "YES" : "NO") . "\n";
echo "First 50 chars: " . substr($content, 0, 50) . "\n";
echo "File size: " . strlen($content) . " bytes\n";

// Test if it has null bytes (indicator of UTF-16)
$nullCount = substr_count($content, "\0");
echo "Null bytes in file: " . $nullCount . "\n";
