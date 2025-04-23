<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the most recent requisitions
$result = $mysqli->query("SELECT * FROM requisitions ORDER BY submitted_at DESC");

if (!$result) {
    die("Database error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Supplies Requisitions</title>
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
<style>
    .buttons-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    .buttons-container .new-btn,
    .buttons-container .print-btn,
    .buttons-container .upload-btn {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        color: white;
    }
    .new-btn { background-color: #f39c12; }
    .print-btn { background-color: #4CAF50; }
    .upload-btn { background-color: #2196F3; }
</style>


<script>
function printPDF() {
    const iframe = document.getElementById('stFormFrame');
    const win = window.open('', '_blank');
    win.document.write('<iframe src="' + iframe.src + '" frameborder="0" style="width:100%;height:100%;" allowfullscreen></iframe>');
    win.document.close();
    win.focus();
    win.print();
    // Optionally, close after printing:
    // win.close();
}
</script>
</head>
<body>

<div class="dashboard-container">
    <h1>Supplies Requisitions</h1>
    <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>
    
    
<div class="buttons-container">
    <a href="new_requisition.php">
        <button class="new-btn">New Requisition</button>
    </a>
    <button class="print-btn" onclick="printPDF()">Print</button>
    <form action="upload_receipt.php" method="post" enctype="multipart/form-data" style="display:inline;">
        <label class="upload-btn">
            Upload Receipts
            <input type="file" name="receipt" onchange="this.form.submit()" style="display:none;">
        </label>
    </form>
</div>

<div class="pdf-container">
    <iframe id="stFormFrame" src="ST(form).pdf" width="100%" height="600px" style="border:1px solid #ccc;"></iframe>
</div>

    </div>
</div>

<!-- Modal Structure -->
<div id="requisitionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Requisition Details</h2>
        <div id="requisitionContent"></div>
        <button onclick="printPDF()">🖨️ Print</button>
        <button onclick="closeModal()">⬅ Back to Requisitions</button>
    </div>
</div>

<script>
function submitForm() {
    document.querySelector("form[action='upload_requisition_form.php']").submit();
}

function openModal(requisitionId) {
    fetch('view_requisition.php?id=' + requisitionId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('requisitionContent').innerHTML = data;
            document.getElementById('requisitionModal').style.display = 'block';
        });
}

function closeModal() {
    document.getElementById('requisitionModal').style.display = 'none';
}
</script>

</body>
</html>
