<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment_name = $_POST['name'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;
    $status = $_POST['status'] ?? 'Available';

    $stmt = $mysqli->prepare("INSERT INTO equipments (name, quantity, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $equipment_name, $quantity, $status);
    $stmt->execute();

    // Redirect back to the manage equipment page
    header("Location: manage_equipments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #800000;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px; /* Add margin to give space between button and search bar */
        }
        .back-button:hover {
            background-color: darkorange;
        }
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
        input, select, button {
            width: 62%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
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
        .search-bar {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
    </style>
    <script>
        function searchEquipment() {
            let input = document.getElementById("search-bar").value.toLowerCase();
            let table = document.getElementById("equipment-table");
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
        <h2>Equipment Management</h2>
        <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>

        <input type="text" id="search-bar" class="search-bar" placeholder="Search equipment..." onkeyup="searchEquipment()">
        
        <form id="equipmentForm" method="POST">
            <input type="text" name="name" placeholder="Equipment Name" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <select name="status" required>
                <option value="Available">Available</option>
                <option value="In Use">In Use</option>
                <option value="Broken">Broken</option>
            </select>
            <button type="submit" class="submit-btn">Save Equipment</button>
        </form>

        <table id="equipment-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM equipments ORDER BY name";
                $result = mysqli_query($mysqli, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
