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
    <script src="script.js"></script>
    <meta charset="UTF-8">
    <title>StMediCare</title>
    <div id="notification" style="display: none; position: fixed; top: 10px; right: 10px; background: #FFD700; color: #800000; padding: 15px; border-radius: 5px; font-weight: bold; box-shadow: 0px 2px 10px rgba(0,0,0,0.3);">
    </div>
    <style>
        body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f5f5f5;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    background-color: #800000;
    width: 80px;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 100px;
    box-sizing: border-box;
}

.sidebar a {
    color: yellow;
    text-decoration: none;
    font-size: 28px;
    margin: 20px 0;
    position: relative;
}

.sidebar-logo {
    height: 60px;
    margin-bottom: 80px;
}

.sidebar a.logout-btn {
    margin-top: 200px;
}

.sidebar a:hover::after {
    content: attr(data-label);
    position: absolute;
    left: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: #FFD700;
    color: #800000;
    padding: 4px 10px;
    border-radius: 5px;
    font-size: 14px;
    white-space: nowrap;
    font-weight: bold;
}

.logo-wrapper {
    position: absolute;
    top: 10px;
}

.sidebar-logo {
    height: 60px;
}

.header {
    background-color: #800000;
    color: white;
    padding: 20px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-time {
    font-size: 16px;
    color: #FFD700;
    font-weight: bold;
    left: 100%;
}

.header .title {
    font-size: 28px;
    font-weight: bold;
    color: #FFD700;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.dashboard-container {
    margin-left: 100px;
    padding: 30px;
}

.dashboard-container h1 {
    color: #800000;
    font-size: 32px;
}

.footer {
    text-align: right;
    margin-top: 50px;
    color: gray;
    font-size: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #800000;
    color: white;
}

.btn {
    padding: 6px 12px;
    font-size: 14px;
    color: white;
    background-color: #800000;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background-color: #a00000;
}
        </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-wrapper">
        <img src="logo.png" alt="Logo" class="sidebar-logo">
    </div>
    <a href="manage_medicines.php" data-label="Medicines">💊</a>
    <a href="manage_equipments.php" data-label="Equipments">🩺</a>
    <a href="requisitions.php" data-label="Requisitions">🧾</a>
    <a href="logout.php" data-label="Logout" class="logout-btn">🚪</a>
</div>

<div class="header">
    <div style="flex: 1;"></div> <!-- spacer on the left -->
    <div class="title">StMediCare</div>
    <div class="header-time" id="header-datetime">Loading time...</div>
</div>

<div class="dashboard-container">
    <h2>Medicine Inventory</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Expiration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $medicines = mysqli_query($mysqli, "SELECT * FROM medicines");
        while ($row = mysqli_fetch_assoc($medicines)):
        ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td class="quantity-cell" data-id="<?= $row['id'] ?>"><?= $row['stock'] ?></td>
                <td><?= $row['expiry_date'] ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="medicine_id" value="<?= $row['id'] ?>">
                    <button class="btn" name="decrease_medicine">-</button>
                    <button class="btn" name="increase_medicine">+</button>
                </form>
            </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2 style="margin-top: 40px;">Equipment Inventory</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $equipments = mysqli_query($mysqli, "SELECT * FROM equipments");
        while ($row = mysqli_fetch_assoc($equipments)):
        ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td class="quantity-cell" data-id="<?= $row['id'] ?>"><?= $row['quantity'] ?></td>
                <td class="status-cell"><?= $row['status'] ?></td>
            <td>
                <form method="POST" style="display:inline;">
                <input type="hidden" name="equipment_id" value="<?= $row['id'] ?>">
                    <button class="btn" name="decrease_equipment" type="button">-</button>
                    <button class="btn" name="increase_equipment" type="button">+</button>
                    <button class="btn" name="edit_status" type="button">Edit Status</button>
                </form>
            </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <button class="btn" onclick="window.print()">Print Inventory</button>
        <br><br>
        <span id="datetime"></span>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
        });
        const time = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        const full = `${date} ${time}`;

        const footerDT = document.getElementById('datetime');
        const headerDT = document.getElementById('header-datetime');

        if (footerDT) footerDT.innerText = full;
        if (headerDT) headerDT.innerText = full;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
});
</script>

    <script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('click', function(e) {
        const btn = e.target;
        if (btn.name === 'decrease_equipment' || btn.name === 'increase_equipment') {
            e.preventDefault();

            const row = btn.closest('tr');
            const quantityCell = row.querySelector('.quantity-cell');
            let currentQty = parseInt(quantityCell.textContent);

            if (btn.name === 'decrease_equipment') {
                if (currentQty > 0) quantityCell.textContent = currentQty - 1;
            } else if (btn.name === 'increase_equipment') {
                quantityCell.textContent = currentQty + 1;
            }
        }
    });
});
</script>

<script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('click', function(e) {
        const btn = e.target;
        if (btn.name === 'edit_status') {
            e.preventDefault();

            const row = btn.closest('tr');
            const statusCell = row.querySelector('.status-cell');

            const currentStatus = statusCell.textContent.trim();
            const nextStatus = getNextStatus(currentStatus);
            statusCell.textContent = nextStatus;
        }
    });

    function getNextStatus(current) {
        const statuses = ["Available", "In Use", "Broken"];
        const index = statuses.indexOf(current);
        return statuses[(index + 1) % statuses.length];
    }
});
</script>

<script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const btn = e.submitter;
        if (btn.name === 'decrease_medicine' || btn.name === 'increase_medicine') {
            e.preventDefault(); // Stop form from submitting

            const row = btn.closest('tr');
            const quantityCell = row.querySelector('.quantity-cell');
            let currentQty = parseInt(quantityCell.textContent);

            if (btn.name === 'decrease_medicine') {
                if (currentQty > 0) quantityCell.textContent = currentQty - 1;
            } else if (btn.name === 'increase_medicine') {
                quantityCell.textContent = currentQty + 1;
            }
        }
    });
});
</script>

</body>
</html>
