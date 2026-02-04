$filepath = "resources\views\payment\payment.blade.php"

# Read as UTF-16 (which it currently is)
$content = [System.IO.File]::ReadAllText($filepath, [System.Text.Encoding]::Unicode)

# Write as UTF-8 without BOM
$utf8 = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($filepath, $content, $utf8)

Write-Host "Converted to UTF-8 (no BOM)"

# Verify
$bytes = [System.IO.File]::ReadAllBytes($filepath)
Write-Host "First 10 bytes: $($bytes[0..9] -join ', ')"

if($bytes[0] -eq 64) {
    Write-Host "SUCCESS - File starts with @"
} else {
    Write-Host "ERROR - First byte is $($bytes[0])"
}
