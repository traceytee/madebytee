<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS Library | Services</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function toggleMenu() {
            document.querySelector('nav ul').classList.toggle('show');
        }
    </script>
    <style>
        /* General Styles */
        body {
            background: url('https://t3.ftcdn.net/jpg/11/79/40/14/360_F_1179401456_tBeUmHj7sTGaGav0fK9PmsR2YzqrIvnY.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #007bff;
            padding: 15px 20px;
        }

        .logo {
            font-size: 24px;
            color: white;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        /* Responsive Menu */
        @media screen and (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            nav ul {
                display: none;
                flex-direction: column;
                background: #007bff;
                position: absolute;
                width: 100%;
                top: 60px;
                left: 0;
            }
            nav ul.show {
                display: flex;
            }
        }

        /* Services Section */
        .services-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
        }

        .service-item {
            background: white;
            padding: 20px;
            margin: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(208, 177, 177, 0.1);
        }

        .service-item i {
            font-size: 40px;
            color: #007bff;
        }

        .service-item h3 {
            margin-top: 10px;
            font-size: 20px;
        }

        .service-item p {
            font-size: 16px;
            color: #555;
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
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="signup.php"><i class="fas fa-user-plus"></i> Signup</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Services Section -->
<section class="services-container">
    <h1>Our Library Services</h1>

    <div class="service-item">
        <i class="fas fa-book"></i>
        <h3>Book Borrowing</h3>
        <p>Borrow books from our vast collection and return them at your convenience.</p>
    </div>

    <div class="service-item">
        <i class="fas fa-laptop"></i>
        <h3>Digital Resources</h3>
        <p>Access e-books, research papers, and online journals anytime.</p>
    </div>

    <div class="service-item">
        <i class="fas fa-users"></i>
        <h3>Study Spaces</h3>
        <p>Book private study rooms and group discussion areas.</p>
    </div>

    <div class="service-item">
        <i class="fas fa-chalkboard-teacher"></i>
        <h3>Research Assistance</h3>
        <p>Get guidance from our librarians to help with your research.</p>
    </div>

    <div class="service-item">
        <i class="fas fa-wifi"></i>
        <h3>Free Wi-Fi</h3>
        <p>Enjoy high-speed internet access for studying and research.</p>
    </div>

    <div class="service-item">
        <i class="fas fa-calendar-alt"></i>
        <h3>Events & Workshops</h3>
        <p>Attend book readings, seminars, and academic workshops.</p>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> NIBS Library. All rights reserved.</p>
</footer>

</body>
</html>
