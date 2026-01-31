<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$compiler = app('blade.compiler');

$paymentContents = file_get_contents(__DIR__ . '/resources/views/payment/payment.blade.php');
$paymentCompiled = $compiler->compileString($paymentContents);

$confirmContents = file_get_contents(__DIR__ . '/resources/views/booking/confirm.blade.php');
$confirmCompiled = $compiler->compileString($confirmContents);

if (method_exists($compiler, 'getCompilers')) {
	echo "Compilers: ", implode(',', $compiler->getCompilers()), "\n";
}

echo "Payment compiled preview:\n";
echo substr($paymentCompiled, 0, 200), "\n\n";

echo "Confirm compiled preview:\n";
echo substr($confirmCompiled, 0, 200), "\n";

$sample = "@if(true) OK @endif";
$sampleCompiled = $compiler->compileString($sample);
echo "\nSample compiled:\n";
echo $sampleCompiled, "\n";
