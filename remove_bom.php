<?php
$path = 'resources/views/payment/payment.blade.php';

// Read file
$content = file_get_contents($path);

// Remove UTF-8 BOM if present
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    $content = substr($content, 3);
    echo "UTF-8 BOM found and removed\n";
} else {
    echo "No UTF-8 BOM found\n";
}

// Write back WITHOUT BOM
file_put_contents($path, $content);

// Verify
$test = file_get_contents($path);
$bytes = array_map(fn($b) => ord($b), str_split(substr($test, 0, 10)));
echo "After BOM removal - First 10 bytes: " . implode(', ', $bytes) . "\n";
echo "First chars: " . substr($test, 0, 20) . "\n";
