<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

// Get the Blade compiler
$compiler = app('blade.compiler');

// Read payment view
$content = file_get_contents('resources/views/payment/payment.blade.php');

echo "File encoding check:\n";
$bytes = array_map(fn($b) => ord($b), str_split(substr($content, 0, 10)));
echo "First 10 bytes: " . implode(', ', $bytes) . "\n";
echo "First 100 chars:\n" . substr($content, 0, 100) . "\n\n";

// Try to compile
try {
    $compiled = $compiler->compileString($content);
    echo "COMPILATION SUCCESS\n";
    echo "Compiled starts with PHP: " . (strpos($compiled, '<?php') !== false ? "YES" : "NO") . "\n";
    echo "First 200 chars of compiled:\n" . substr($compiled, 0, 200) . "\n";
} catch (Exception $e) {
    echo "COMPILATION ERROR: " . $e->getMessage() . "\n";
}
