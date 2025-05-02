<?php 
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipt_image'])) {
    $uploadDir = 'uploads/receipts/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['receipt_image']['tmp_name'];
    $fileName = basename($_FILES['receipt_image']['name']);
    $newFileName = time() . '_' . $fileName;
    $destination = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        echo "<script>alert('Receipt uploaded successfully.');</script>";
    } else {
        echo "<script>alert('Failed to upload receipt.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplies Requisition Form</title>
  <style>
    body {
      margin: 0;
      font-family: "Times New Roman", Times, serif;
      background-color: #800000;
    }

    .container {
      background-color: white;
      max-width: 900px;
      margin: 40px auto;
      padding: 40px;
      border-radius: 10px;
    }

    .back-button, .print-button {
      background-color: #7b0000;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 20px;
      display: inline-block;
      cursor: pointer;
    }

    .header {
      text-align: center;
    }

    .header img {
      float: left;
      width: 80px;
      height: 80px;
    }

    .header .info {
      margin-left: 100px;
    }

    h2 {
      margin-top: 40px;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table, th, td {
      border: 1px solid black;
    }

    th, td {
      padding: 6px;
      text-align: center;
    }

    .signature-section {
      margin-top: 40px;
    }

    .signature-line {
      margin-top: 50px;
      text-align: center;
    }

    .signature-line span {
      border-top: 1px solid black;
      display: block;
      padding-top: 5px;
      width: 300px;
    }

    .noted-approved {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
    }

    .noted-approved div {
      width: 45%;
      text-align: center;
    }

    .button-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    @media print {
      body {
        background-color: white;
      }
      .button-row {
        display: none;
      }
      .container {
        box-shadow: none;
        border: none;
        margin: 0;
        padding: 0;
      }
    }
  </style>
</head>
<body>

<div class="container">

  <div class="button-row">
    <a href="dashboard.php" class="back-button">← Back to Dashboard</a>
    <button class="print-button" onclick="window.print()">🖨️ Print</button>
    <form method="POST" enctype="multipart/form-data" style="display:inline-block; margin-left: 10px;">
  <input type="file" name="receipt_image" accept="image/*" required>
  <button type="submit" class="print-button">📎 Upload Receipt</button>
</form>
  </div>

  <div class="header">
    <img src="svcc_logo.png" alt="School Logo">
    <div class="info">
      <strong>ST. VINCENT COLLEGE OF CABUYAO</strong><br>
      Mamatid, City of Cabuyao, Laguna<br>
      Tel. No. (049) 531-1617 / <a href="mailto:inquiry_svcc@yahoo.com">inquiry_svcc@yahoo.com</a>
    </div>
  </div>

  <h2>SUPPLIES REQUISITION FORM</h2>

  <p>Date: <strong><?php echo date("F j, Y"); ?></strong></p>
  <p>Department: <strong>Clinic</strong></p>

  <table>
    <tr>
      <th>Item/s</th>
      <th>Total Quantity</th>
      <th>Estimated Cost</th>
      <th>Purpose</th>
    </tr>
    <?php for ($i = 0; $i < 6; $i++): ?>
    <tr>
      <td><input type="text" name="Item/s"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php endfor; ?>
  </table>

  <div class="signature-section">
    <p>Requested by:</p>
    <div class="signature-line">
      <span>(Signature Over Printed Name)</span>
    </div>
  </div>

  <div class="noted-approved">
    <div>
      <p>Noted by:</p>
      <strong>AILEEN JEAN BABIERA</strong><br>
      Property and Supply Management Officer
    </div>
    <div>
      <p>Approved by:</p>
      <strong>MARINA A. CHAVEZ</strong><br>
      SV - President for Administration/Directress
    </div>
  </div>
</div>

</body>
</html>
