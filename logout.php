<?php
session_start();

// Handle logout when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: signin.php"); // Redirect to signin page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 100px;
        }
        form {
            display: inline-block;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h2>Are you sure you want to log out?</h2>
    <form method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
