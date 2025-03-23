<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    if (!empty($name) && !empty($quantity) && !empty($status)) {
        $stmt = $mysqli->prepare("INSERT INTO equipments (name, quantity, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $quantity, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Equipment added successfully!'); window.location.href='manage_equipments.php';</script>";
        } else {
            echo "<script>alert('Error adding equipment: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Fetch all equipments
$result = $mysqli->query("SELECT * FROM equipments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Manage Equipments</title>
</head>
<body>

    <div class="dashboard-container">
        <h1>Equipment Management</h1>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>

        <!-- Input Form -->
        <form method="POST">
            <input type="text" name="name" placeholder="Equipment Name" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Available">Available</option>
                <option value="In Use">In Use</option>
                <option value="Damaged">Damaged</option>
            </select>
            <button type="submit">Add Equipment</button>
        </form>

        <!-- Search Bar -->
        <input type="text" id="searchEquip" placeholder="Search..." onkeyup="searchTable('searchEquip', 'equipmentTable')">

        <!-- Equipment Table -->
        <table id="equipmentTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
