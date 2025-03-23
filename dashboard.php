<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>StMediCare</title>
</head>
<body>
<div id="notification" style="display: none; position: fixed; top: 10px; right: 10px; background: #FFD700; color: #800000; padding: 15px; border-radius: 5px; font-weight: bold; box-shadow: 0px 2px 10px rgba(0,0,0,0.3);">
</div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php">🏠</a>
        <a href="manage_medicines.php">💊</a>
        <a href="manage_equipments.php">🩺</a>
        <a href="receipts.php">🧾</a>
        <a href="faqs.php">❓</a>
        <a href="logout.php">🚪</a>
    </div>

    <div class="dashboard-container">
        <h1>StMediCare</h1>
        <div class="dashboard-grid">
            <div class="dashboard-card">Total Released Medicine</div>
            <div class="dashboard-card">Most Visited Departments</div>
            <div class="dashboard-card">Highly Consumed Medicine</div>
        </div>
    </div>

</body>
</html>
