<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StMediCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> 
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
        .logo {
            width: 150px;
            margin: 0 auto 20px;
            display: block;
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
        .forgot {
            text-align: left;
            font-size: 12px;
            color: #871a1c;
            margin-top: 5px;
        }
        .forgot a {
            color: #871a1c;
            text-decoration: none;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <img src="logo.png" alt="StMediCare Logo" class="logo">
            <div class="title">StMediCare</div>
            <form action="login.php" method="post">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="forgot">
                    <a href="forgot_password.php">Forgot Your Password?</a>
                </div>
                <button class="login-button" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
