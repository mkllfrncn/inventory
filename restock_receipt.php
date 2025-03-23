<?php
session_start();
require 'db.php';

// Ensure a valid receipt ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ Error: Invalid or missing receipt ID.");
}

$receipt_id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM receipts WHERE id = $receipt_id");

if (!$result || $result->num_rows == 0) {
    die("❌ Error: Receipt not found.");
}

$receipt = $result->fetch_assoc();

// Get item details
$item_name = "Unknown Item";
if ($receipt['item_type'] == 'medicine') {
    $item_result = $mysqli->query("SELECT name FROM medicines WHERE id = " . intval($receipt['item_id']));
} else {
    $item_result = $mysqli->query("SELECT name FROM equipments WHERE id = " . intval($receipt['item_id']));
}

if ($item_result && $item_row = $item_result->fetch_assoc()) {
    $item_name = htmlspecialchars($item_row['name']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Restocking Receipt</title>
</head>
<body>

<div class="receipt-container">
    <h2>Restocking Receipt</h2>
    <p><strong>Item:</strong> <?= $item_name ?></p>
    <p><strong>Type:</strong> <?= ucfirst($receipt['item_type']) ?></p>
    <p><strong>Date Restocked:</strong> <?= $receipt['date_created'] ?></p>

    <!-- Show request form download button if available -->
    <?php if (!empty($receipt['request_file'])): ?>
        <p><strong>Request Form:</strong> 
            <a href="uploads/<?= $receipt['request_file'] ?>" target="_blank" class="view-receipt">📄 View Request Form</a>
        </p>
    <?php else: ?>
        <p><strong>Request Form:</strong> ❌ No File Uploaded</p>
    <?php endif; ?>

    <!-- Upload New Request Form -->
    <h3>Upload / Update Request Form</h3>
    <form action="upload_request_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="receipt_id" value="<?= $receipt_id ?>">
        <input type="file" name="request_file" accept=".pdf, .doc, .docx" required>
        <button type="submit">📤 Upload</button>
    </form>

    <button onclick="window.print()">🖨 Print Receipt</button>
    <a href="receipts.php"><button>📋 Back to Receipts</button></a>
</div>

</body>
</html>
