<?php
session_start();

// Database Connection
$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert admin into database
    $query = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $success_message = "Admin registered successfully! You can now log in.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register | Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background: url('https://st.hzcdn.com/simgs/pictures/home-offices/our-rolling-and-hook-over-ladders-the-library-ladder-company-img~5fd1aea90d2c8ebf_9-1342-1-a006117.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .input-container input {
            width: 100%;
            padding: 10px 10px 10px 35px; /* Space for icon */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            color: #007bff;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .message {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        .login-link {
            margin-top: 15px;
            display: block;
            text-decoration: none;
            color: #007bff;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Registration</h2>

    <?php if (isset($success_message)): ?>
        <p class="message"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p class="message error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-container">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
    </form>

    <a href="admin_login.php" class="login-link"><i class="fas fa-sign-in-alt"></i> Already have an account? Login</a>
</div>

</body>
</html>
