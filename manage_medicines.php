<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        <h2>Medicine Management</h2>
        <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>
        
        <input type="text" id="search-bar" class="search-bar" placeholder="Search medicines..." onkeyup="searchMedicine()">
        
        <form id="medicineForm" method="POST">
            <input type="text" name="name" placeholder="Medicine Name" required>
            <input type="text" name="dosage" placeholder="Dosage (e.g., 500mg)">
            <input type="number" name="stock" placeholder="Stock Quantity">
            
            <div class="date-container">
                <label>Expiry Date: <input type="date" name="expiry_date"></label>
                <label>Manufacturing Date: <input type="date" name="manufacture_date"></label>
            </div>
            
            <input type="text" name="form" placeholder="Dosage Form (Tablet, Syrup, etc.)">
            <input type="text" name="brand" placeholder="Brand Name">
            <input type="text" name="batch" placeholder="Batch Number">
            <button type="submit" class="submit-btn">Save Medicine</button>
        </form>

        <table id="medicine-table">
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
                <?php
                $query = "SELECT * FROM medicines ORDER BY name";
                $result = mysqli_query($mysqli, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['dosage']}</td>
                        <td>{$row['stock']}</td>
                        <td>{$row['expiry_date']}</td>
                        <td>{$row['manufacturing_date']}</td>
                        <td>{$row['dosage_form']}</td>
                        <td>{$row['brand']}</td>
                        <td>{$row['batch_number']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
