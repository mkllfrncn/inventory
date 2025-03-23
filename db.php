<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "inventory_system";

// Secure connection using mysqli
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Set character encoding
$mysqli->set_charset("utf8");
?>
