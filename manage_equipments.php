<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $id = $_POST['equipment_id'] ?? 0;
        $stmt = $mysqli->prepare("DELETE FROM equipments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: manage_equipments.php");
        exit();
    }

    if (isset($_POST['equipment_id']) && isset($_POST['status'])) {
        $id = $_POST['equipment_id'];
        $status = $_POST['status'];
        $stmt = $mysqli->prepare("UPDATE equipments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        header("Location: manage_equipments.php");
        exit();
    }

    if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['status'])) {
        $equipment_name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];
        $stmt = $mysqli->prepare("INSERT INTO equipments (name, quantity, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $equipment_name, $quantity, $status);
        $stmt->execute();
        header("Location: manage_equipments.php");
        exit();
    }
}

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
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .back-button:hover {
            background-color: darkorange;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #800000;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative; /* Added this to make button position relative to the container */
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
        <a href="dashboard.php" class="back-button">&larr; Back to Dashboard</a>
        <h2>Equipment Management</h2>

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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM equipments ORDER BY name";
                $result = mysqli_query($mysqli, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr data-id='{$row['id']}'>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>
                            <select class='status-dropdown' data-id='{$row['id']}'>
                                <option value='Available' " . ($row['status'] === 'Available' ? 'selected' : '') . ">Available</option>
                                <option value='In Use' " . ($row['status'] === 'In Use' ? 'selected' : '') . ">In Use</option>
                                <option value='Broken' " . ($row['status'] === 'Broken' ? 'selected' : '') . ">Broken</option>
                            </select>
                        </td>
                        <td>
                            <button class='remove-button' data-id='{$row['id']}' style='color:white; background:maroon; border:none; padding:5px 10px; border-radius:5px;'>Remove</button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
    document.querySelectorAll('.remove-button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            
            // Confirmation alert before removal
            const confirmed = confirm("Are you sure you want to remove this equipment?");
            if (!confirmed) return; // If user cancels, stop the removal process

            // Send the removal request to the server
            fetch('update_equipment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: action=remove&id=${id}
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Equipment removed successfully!");
                    document.querySelector(tr[data-id='${id}']).remove(); // Remove the row from the table
                } else {
                    alert("Failed to remove equipment. Please try again.");
                }
            })
            .catch(error => {
                alert("An error occurred. Please try again.");
                console.error("Error removing equipment:", error);
            });
        });
    });
</script>
</body>
</html>
