<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - NIBS Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: url('https://t3.ftcdn.net/jpg/11/79/40/14/360_F_1179401456_tBeUmHj7sTGaGav0fK9PmsR2YzqrIvnY.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Horizontal Navigation */
        .horizontal-nav {
            display: flex;
            justify-content: center;
            background: #007bff;
            padding: 10px 0;
        }
        .horizontal-nav ul {
            list-style: none;
            display: flex;
            padding: 0;
        }
        .horizontal-nav ul li {
            margin: 0 15px;
        }
        .horizontal-nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            transition: 0.3s;
        }
        .horizontal-nav ul li a:hover {
            background: white;
            color: #007bff;
            border-radius: 5px;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .horizontal-nav ul {
                flex-direction: column;
                text-align: center;
            }
            .horizontal-nav ul li {
                margin: 5px 0;
            }
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input, textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            background:  #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Horizontal Navigation -->
<nav class="horizontal-nav">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="catalog.php"><i class="fas fa-book"></i> Catalog</a></li>
        <li><a href="services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="borrowed.php"><i class="fas fa-book-reader"></i> My Books</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> Signup</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Contact Form -->
<div class="container">
    <h2>Contact Us</h2>
    <form action="contact.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>
        
        <button type="submit">Send Message</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> NIBS Library. All rights reserved.</p>
</footer>

</body>
</html>
