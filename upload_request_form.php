<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["receipt_id"])) {
    $receipt_id = intval($_POST["receipt_id"]);
    $request_file = null;

    // Handle file upload
    if (!empty($_FILES['request_file']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Ensure the uploads folder exists
        }
        $file_name = time() . "_" . basename($_FILES["request_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // Only allow PDF or Word files
        if (!in_array($file_type, ["pdf", "docx", "doc"])) {
            echo "<script>alert('Only PDF or Word files are allowed.'); window.history.back();</script>";
            exit();
        }

        // Move file to the uploads directory
        if (move_uploaded_file($_FILES["request_file"]["tmp_name"], $target_file)) {
            $request_file = $file_name;
        } else {
            echo "<script>alert('Error uploading file.'); window.history.back();</script>";
            exit();
        }
    }

    // Update the request file in the receipts table
    $stmt = $mysqli->prepare("UPDATE receipts SET request_file = ? WHERE id = ?");
    $stmt->bind_param("si", $request_file, $receipt_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Request form uploaded successfully!'); window.location.href='receipts.php';</script>";
    exit();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit();
}
?>
