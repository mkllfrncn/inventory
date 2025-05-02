<?php
include 'db.php';

// Delete individual medicine by ID
if (isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];
    $stmt = $mysqli->prepare("DELETE FROM medicines WHERE id = ?");
    $stmt->bind_param("i", $remove_id);
    $stmt->execute();
    header("Location: manage_medicines.php");
    exit();
}

// Add new medicine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['remove_id']) && !isset($_POST['delete_expired'])) {
    $medicine_name = $_POST['name'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $expiry_date = $_POST['expiry_date'] ?? NULL;
    $manufacturing_date = $_POST['manufacturing_date'] ?? NULL;
    $dosage_form = $_POST['dosage_form'] ?? '';
    $brand_name = $_POST['brand'] ?? '';
    $batch_number = $_POST['batch_number'] ?? '';

    $stmt = $mysqli->prepare("INSERT INTO medicines (name, dosage, stock, expiry_date, manufacturing_date, dosage_form, brand, batch_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssss", $medicine_name, $dosage, $stock, $expiry_date, $manufacturing_date, $dosage_form, $brand_name, $batch_number);
    $stmt->execute();
    header("Location: manage_medicines.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #800000;
            color: white;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .date-container {
            display: flex;
            justify-content: space-between;
        }
        .date-container label, .date-container input {
            width: 48%;
        }
        table {
            width: 100%;
            margin-top: 20px;
            background: white;
            color: black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        .submit-btn {
            background-color: #ff9800;
            color: white;
            font-size: 16px;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 50%;
            border-radius: 5px;
        }
        .submit-btn:hover {
            background-color: darkorange;
        }
    </style>
    <script>
        function searchMedicine() {
            let input = document.getElementById("search-bar").value.toLowerCase();
            let table = document.getElementById("medicine-table");
            let rows = table.getElementsByTagName("tr");
            
            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let found = false;
                
                for (let cell of cells) {
                    if (cell.innerText.toLowerCase().includes(input)) {
                        found = true;
                        break;
                    }
                }
                rows[i].style.display = found ? "" : "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
    <div style="text-align: left; margin-bottom: 10px;">
    <a href="dashboard.php" class="back-button" style="
        background-color: #800000;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
    ">&larr; Back to Dashboard</a>
</div>
<h2>Medicine Management</h2>
        
        <input type="text" id="search-bar" class="search-bar" placeholder="Search medicines..." onkeyup="searchMedicine()">
        
        <form id="medicineForm" method="POST">
        <input type="text" name="name" placeholder="Medicine Name" required>
        <input type="number" name="stock" placeholder="Stock Quantity">
        <input type="text" name="dosage" placeholder="Dosage (e.g., 500mg)">
        <input type="text" name="dosage_form" placeholder="Dosage Form (Tablet, Syrup, etc.)">
        <input type="text" name="brand" placeholder="Brand Name">
        <div class="date-container">
            <label>Manufacturing Date: <input type="date" name="manufacturing_date"></label>
            <label>Expiry Date: <input type="date" name="expiry_date"></label>
        </div>
        <input type="text" name="batch_number" placeholder="Batch Number">
        <div style="display: flex; justify-content: center; margin-top: 15px;">
            <button type="submit" class="submit-btn" style="width: 60%;">Save Medicine</button>
        </div>
        </form>
        <table id="medicine-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Dosage</th>
                <th>Dosage Form</th>
                <th>Brand</th>
                <th>Manufacturing Date</th>
                <th>Expiry Date</th>
                <th>Batch Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM medicines ORDER BY name";
            $result = mysqli_query($mysqli, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['stock']}</td>
                    <td>{$row['dosage']}</td>
                    <td>{$row['dosage_form']}</td>
                    <td>{$row['brand']}</td>
                    <td>{$row['manufacturing_date']}</td>
                    <td>{$row['expiry_date']}</td>
                    <td>{$row['batch_number']}</td>
                    <td>
                        <form method='POST' onsubmit=\"return confirm('Are you sure you want to remove this medicine?');\">
                            <input type='hidden' name='remove_id' value='{$row['id']}'>
                            <button type='submit' style='background-color: crimson; color: white; padding: 5px 10px; border: none; border-radius: 5px;'>Remove</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
        </table>
    </div>
</body>
</html>
