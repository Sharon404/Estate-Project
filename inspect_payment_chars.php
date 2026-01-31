<?php
$contents = file_get_contents(__DIR__ . '/resources/views/payment/payment.blade.php');

$firstChars = mb_substr($contents, 0, 5, 'UTF-8');
$firstBytes = array_map('ord', str_split(substr($contents, 0, 10)));

$firstAtPos = strpos($contents, '@');
$firstAtChar = $firstAtPos !== false ? mb_substr($contents, $firstAtPos, 1, 'UTF-8') : null;
$firstAtCode = $firstAtChar !== null ? mb_ord($firstAtChar, 'UTF-8') : null;
$firstAtByte = $firstAtPos !== false ? ord($contents[$firstAtPos]) : null;
$firstAtHex = $firstAtPos !== false ? bin2hex($contents[$firstAtPos]) : null;

echo "First chars: ", $firstChars, "\n";
echo "First bytes: ", implode(',', $firstBytes), "\n";
echo "First @ pos: ", ($firstAtPos !== false ? $firstAtPos : 'none'), "\n";
echo "First @ code: ", ($firstAtCode !== null ? $firstAtCode : 'none'), "\n";
echo "First @ byte: ", ($firstAtByte !== null ? $firstAtByte : 'none'), "\n";
echo "First @ hex: ", ($firstAtHex !== null ? $firstAtHex : 'none'), "\n";
