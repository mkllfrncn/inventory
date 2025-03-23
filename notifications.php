<?php
require 'db.php';

$lowStock = [];
$nearExpiry = [];

// Today's date
$today = date('Y-m-d');

// Fetch medicines with stock < 10
$result = $mysqli->query("SELECT name FROM medicines WHERE stock < 10");
while ($row = $result->fetch_assoc()) {
    $lowStock[] = $row['name'];
}

// Fetch medicines expiring in the next 30 days
$result = $mysqli->query("SELECT name, expiry_date FROM medicines WHERE expiry_date <= DATE_ADD('$today', INTERVAL 30 DAY)");
while ($row = $result->fetch_assoc()) {
    $nearExpiry[] = ["name" => $row['name'], "expiry_date" => $row['expiry_date']];
}

// Return JSON response
echo json_encode(["lowStock" => $lowStock, "nearExpiry" => $nearExpiry]);
?>
