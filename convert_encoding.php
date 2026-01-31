<?php
$path = 'resources/views/payment/payment.blade.php';

// Read as UTF-16 LE
$content = file_get_contents($path);

// Decode from UTF-16 LE
$decoded = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');

// Write as UTF-8 without BOM
file_put_contents($path, $decoded);

// Verify
$bytes = array_map(fn($b) => ord($b), str_split(substr(file_get_contents($path), 0, 10)));
echo "After conversion - First 10 bytes: " . implode(', ', $bytes) . "\n";
echo "UTF-8 BOM (239,187,191): " . ($bytes[0] === 239 && $bytes[1] === 187 && $bytes[2] === 191 ? "YES" : "NO") . "\n";
echo "UTF-16 LE BOM (255,254): " . ($bytes[0] === 255 && $bytes[1] === 254 ? "YES" : "NO") . "\n";
echo "First char: " . chr($bytes[3]) . chr($bytes[4]) . chr($bytes[5]) . "\n";
echo "Null bytes: " . substr_count(file_get_contents($path), "\0") . "\n";
