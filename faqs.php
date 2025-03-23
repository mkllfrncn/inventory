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
    <link rel="stylesheet" href="style.css">
    <title>FAQs</title>
    <style>
        .faq-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background:rgb(255, 255, 255);
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-question {
            background: #cc7a00;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .faq-answer {
            display: none;
            padding: 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            background-color: #dc3545; /* Red */
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            border: 2px solid #bd2130;
            transition: all 0.3s ease-in-out;
        }

        .back-button:hover {
            background-color: #bd2130;
            border-color: #a71d2a;
        }
    </style>
</head>
<body>

<div class="faq-container">
    <h1>Frequently Asked Questions</h1>

    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>

    <div class="faq-item">
        <div class="faq-question">📦 How do I restock medicines?</div>
        <div class="faq-answer">Go to the "Receipts" page and use the "Upload Request Form" button to add a request form when restocking.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">📑 How can I upload a request form?</div>
        <div class="faq-answer">In the "Actions" column of the Receipts page, click "Upload Request Form" to select and upload your document.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">🔍 Where can I find past restocking receipts?</div>
        <div class="faq-answer">Go to the "Receipts" page to see all restocking records. Click "View Receipt" for details.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">📜 Can I edit a receipt after it's created?</div>
        <div class="faq-answer">No, receipts are automatically generated and cannot be modified. However, you can upload a new request form if needed.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">⚙️ How do I update my medicine stock?</div>
        <div class="faq-answer">Medicines are updated automatically when a restocking receipt is created. Check the "Manage Medicines" page for stock levels.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">📂 Where are request forms stored?</div>
        <div class="faq-answer">Uploaded request forms are stored in the "uploads" folder and can be accessed through the Receipts page.</div>
    </div>
</div>

<script>
    document.querySelectorAll('.faq-question').forEach(item => {
        item.addEventListener('click', () => {
            let answer = item.nextElementSibling;

            // Hide all other answers
            document.querySelectorAll('.faq-answer').forEach(ans => {
                if (ans !== answer) {
                    ans.style.display = "none";
                }
            });

            // Toggle selected answer
            answer.style.display = (answer.style.display === "block") ? "none" : "block";
        });
    });
</script>

</body>
</html>
