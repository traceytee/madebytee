<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS Library</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function toggleMenu() {
            document.querySelector('nav ul').classList.toggle('show');
        }
    </script>
</head>
<body>

<!-- Navigation Bar -->
<header>
    <nav>
        <div class="nav-left">
            <a href="dashboard.php" class="logo">📚 NIBS Library</a>
        </div>
        <div class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="catalog.php"><i class="fas fa-book"></i> Catalog</a></li>
            <li><a href="services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="borrowed.php"><i class="fas fa-book-reader"></i> My Books</a></li>
                <li><a href="account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="signup.php"><i class="fas fa-user-plus"></i> Signup</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
