<?php
$pdo = new PDO('mysql:host=127.0.0.1:3307;dbname=holiday_rentals', 'holiday_user', 'secret');

echo "Testing property match for 'Standart Room'\n";

// Test exact match
$stmt = $pdo->prepare('SELECT id, name FROM properties WHERE LOWER(name) = LOWER(?) AND status = ? AND pending_removal = ?');
$stmt->execute(['Standart Room', 'APPROVED', 0]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "EXACT MATCH FOUND: ID " . $result['id'] . " - " . $result['name'] . "\n";
} else {
    echo "EXACT MATCH NOT FOUND\n";
}

// List all properties
echo "\nAll approved properties:\n";
$stmt = $pdo->query('SELECT id, name, status, pending_removal FROM properties WHERE status = "APPROVED" AND pending_removal = 0');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . " | " . $row['name'] . " | " . $row['status'] . " | " . $row['pending_removal'] . "\n";
}
