<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the most recent receipt for each restocking batch
$result = $mysqli->query("SELECT * FROM receipts ORDER BY date_created DESC");

if (!$result) {
    die("Database error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Restocking Receipts</title>
    <style>
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }
        .buttons-container button {
            padding: 12px 24px;
            font-size: 16px;
            width: 220px;
            border: darkorange;
            cursor: pointer;
            background-color: #ff9800;
            color: white;
            border-radius: 5px;
        }
        .buttons-container button:hover {
            background-color: #800000;
            border-radius: 5px;
            border: 1px solid darkorange;
        }
        .editable {
            border: 1px solid #ccc;
            padding: 5px;
            background: none;
            cursor: text;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 60%;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            color: black;
        }
        .modal-content button {
            padding: 15px 15px;
            font-size: 14px;
            margin-top: 10px;
            width: 220px;
        }
        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
        .table-container {
            width: 90%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            color: black;
        }
        th {
            background-color: #ff9800;
            color: white;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h1>Restocking Receipts</h1>
    <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>
    
    <div class="buttons-container">
        <form action="restock_receipt.php" method="GET">
            <button type="submit" class="view-receipt">📄 View Receipts</button>
        </form>
        
        <form action="upload_request_form.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="request_file" accept=".pdf, .doc, .docx" style="display:none;" id="fileInput" onchange="submitForm()">
            <button type="button" class="upload-button" onclick="document.getElementById('fileInput').click();">📤 Upload Form</button>
        </form>
        
        <form action="add_receipt.php" method="POST">
            <button type="submit" class="add-button">➕ Add Receipt</button>
        </form>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['message'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Date Restocked</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($receipt = $result->fetch_assoc()): ?>
                <tr>
                    <td contenteditable="true" class="editable"> <?= ucfirst($receipt['item_type']) ?> </td>
                    <td contenteditable="true" class="editable"> <?= $receipt['date_created'] ?> </td>
                    <td>
                        <button class="view-receipt" onclick="openModal('<?= $receipt['id'] ?>')">📄 View</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div id="receiptModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Receipt Details</h2>
        <div id="receiptContent"></div>
        <button onclick="window.print()">🖨️ Print</button>
        <button onclick="closeModal()">⬅ Back to Receipts</button>
    </div>
</div>

<script>
function submitForm() {
    document.querySelector("form[action='upload_request_form.php']").submit();
}

function openModal(receiptId) {
    fetch('restock_receipt.php?id=' + receiptId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('receiptContent').innerHTML = data;
            document.getElementById('receiptModal').style.display = 'block';
        });
}

function closeModal() {
    document.getElementById('receiptModal').style.display = 'none';
}
</script>

</body>
</html>
