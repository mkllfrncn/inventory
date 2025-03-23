<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch only the most recent receipt for each restocking batch
$result = $mysqli->query("SELECT * FROM receipts GROUP BY date_created ORDER BY date_created DESC");

if (!$result) {
    die("Database error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Restocking Receipts</title>
</head>
<body>

<div class="dashboard-container">
    <h1>Restocking Receipts</h1>
    <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>

    <table border="1">
        <thead>
            <tr>
                <th>Item</th>
                <th>Type</th>
                <th>Date Restocked</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($receipt = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php
                    $item_name = "Unknown Item";
                    if ($receipt['item_type'] == 'medicine') {
                        $item_result = $mysqli->query("SELECT name FROM medicines WHERE id = " . intval($receipt['item_id']));
                    } else {
                        $item_result = $mysqli->query("SELECT name FROM equipments WHERE id = " . intval($receipt['item_id']));
                    }

                    if ($item_result && $item_row = $item_result->fetch_assoc()) {
                        $item_name = htmlspecialchars($item_row['name']);
                    }
                    echo $item_name;
                    ?>
                </td>
                <td><?= ucfirst($receipt['item_type']) ?></td>
                <td><?= $receipt['date_created'] ?></td>
                <td>
                    <!-- View Receipt Button -->
                    <form action="restock_receipt.php" method="GET" style="display:inline;">
                        <input type="hidden" name="id" value="<?= intval($receipt['id']) ?>">
                        <button type="submit" class="view-receipt">📄 View Receipt</button>
                    </form>

                    <!-- Request Form: View or Upload -->
                    <form action="upload_request_form.php" method="POST" enctype="multipart/form-data" style="display:inline;" id="uploadForm-<?= $receipt['id'] ?>">
                        <input type="hidden" name="receipt_id" value="<?= intval($receipt['id']) ?>">
                        <input type="file" name="request_file" accept=".pdf, .doc, .docx" style="display:none;" id="fileInput-<?= $receipt['id'] ?>" onchange="submitForm(<?= $receipt['id'] ?>)">
                        
                        <!-- If a request form exists, show "View Request Form" -->
                        <?php if (!empty($receipt['request_file'])): ?>
                            <a href="uploads/<?= $receipt['request_file'] ?>" class="view-receipt" target="_blank">
                                📄 View Request Form
                            </a>
                        <?php endif; ?>

                        <!-- Upload Request Form Button -->
                        <button type="button" class="upload-button" onclick="document.getElementById('fileInput-<?= $receipt['id'] ?>').click();">
                            📤 Upload Request Form
                        </button>
                    </form>
                </td>               
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript to Auto-Submit Form When File Is Selected -->
<script>
function submitForm(receiptId) {
    document.getElementById("uploadForm-" + receiptId).submit();
}
</script>

</body>
</html>
