<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Check if the username exists
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Update the password
        $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
        $stmt->bind_param("si", $hashed_password, $user['id']);
        $stmt->execute();

        $success = "Your password has been reset successfully.";
    } else {
        $error = "No account found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - StMediCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add your CSS styles here similar to login.php */
        body {
            margin: 0;
            padding: 0;
            background-color: #871a1c;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background-color: white;
            border-radius: 30px;
            padding: 40px;
            width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .title {
            background-color: #ffd700;
            color: #871a1c;
            font-weight: bold;
            font-size: 24px;
            padding: 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 30px;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: #eee;
            padding: 10px;
        }
        .input-group i {
            color: #871a1c;
            margin: 0 10px;
        }
        .input-group input {
            border: none;
            outline: none;
            background: none;
            flex: 1;
            font-size: 16px;
        }
        .login-button {
            margin-top: 20px;
            background-color: #ddd;
            color: #333;
            border: none;
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
        }
        .login-button:hover {
            background-color: #ccc;
        }
        .back-link {
            margin-top: 15px;
            display: block;
            color: #871a1c;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="title">Reset Password</div>
            <form action="forgot_password.php" method="post">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="new_password" placeholder="New Password" required>
                </div>
                <button class="login-button" type="submit">Reset Password</button>
            </form>
            <?php if (isset($success)) echo "<p>$success</p>"; ?>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
            <a class="back-link" href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
