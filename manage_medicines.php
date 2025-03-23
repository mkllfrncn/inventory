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
    $dosage = $_POST['dosage'];
    $stock = $_POST['stock'];
    $expiry_date = $_POST['expiry_date'];
    $manufacturing_date = $_POST['manufacturing_date'];
    $dosage_form = $_POST['dosage_form'];
    $brand = $_POST['brand'];
    $batch_number = $_POST['batch_number'];

    if (!empty($name) && !empty($dosage) && !empty($stock) && !empty($expiry_date) && !empty($manufacturing_date) && !empty($dosage_form) && !empty($brand) && !empty($batch_number)) {
        $stmt = $mysqli->prepare("INSERT INTO medicines (name, dosage, stock, expiry_date, manufacturing_date, dosage_form, brand, batch_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssss", $name, $dosage, $stock, $expiry_date, $manufacturing_date, $dosage_form, $brand, $batch_number);

        if ($stmt->execute()) {
            echo "<script>alert('Medicine added successfully!'); window.location.href='manage_medicines.php';</script>";
        } else {
            echo "<script>alert('Error adding medicine: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Fetch all medicines
$result = $mysqli->query("SELECT * FROM medicines");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Manage Medicines</title>
</head>
<body>

    <div class="dashboard-container">
        <h1>Medicine Management</h1>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>

        <!-- Input Form -->
        <form method="POST">
            <input type="text" name="name" placeholder="Medicine Name" required>
            <input type="text" name="dosage" placeholder="Dosage (e.g., 500mg)" required>
            <input type="number" name="stock" placeholder="Stock Quantity" required>
            <label for="expiry_date">Expiry Date:</label>
            <input type="date" name="expiry_date" required>
            <label for="manufacturing_date">Manufacturing Date:</label>
            <input type="date" name="manufacturing_date" required>
            <input type="text" name="dosage_form" placeholder="Dosage Form (Tablet, Syrup, etc.)" required>
            <input type="text" name="brand" placeholder="Brand Name" required>
            <input type="text" name="batch_number" placeholder="Batch Number" required>
            <button type="submit">Add Medicine</button>
        </form>

        <!-- Search Bar -->
        <input type="text" id="searchMedicines" placeholder="Search medicines..." onkeyup="searchTable('searchMedicines', 'medicineTable')">

        <!-- Medicine Table -->
        <table id="medicineTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Dosage</th>
                    <th>Stock</th>
                    <th>Expiry Date</th>
                    <th>Manufacturing Date</th>
                    <th>Dosage Form</th>
                    <th>Brand</th>
                    <th>Batch Number</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['dosage']) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><b>Expiry:</b> <?= $row['expiry_date'] ?></td>
                    <td><b>Manufactured:</b> <?= $row['manufacturing_date'] ?></td>
                    <td><?= htmlspecialchars($row['dosage_form']) ?></td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><?= htmlspecialchars($row['batch_number']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
