<?php
session_start();

// Database Connection
$conn = new mysqli("localhost", "root", "", "library_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include notify_fines.php (Ensure it does NOT close the database connection)
if (isset($_SESSION['user_id'])) {
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS library</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://t3.ftcdn.net/jpg/11/79/40/14/360_F_1179401456_tBeUmHj7sTGaGav0fK9PmsR2YzqrIvnY.jpg') no-repeat center center fixed;
            background-size: cover;
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
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            transition: 0.3s;
        }

        nav ul li a:hover {
            background: white;
            color: #007bff;
            border-radius: 5px;
        }

        /* Features Section */
        .features {
            display: flex;
            justify-content: center;
            text-align: center;
            padding: 20px;
            flex-wrap: wrap;
        }

        .feature {
            width: 300px;
            background: white;
            padding: 15px;
            margin: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .feature:hover {
            transform: scale(1.05);
        }

        .feature img {
            width: 100px;
            height: 100px;
        }

        /* Trending Books */
        .book-list {
            text-align: center;
            padding: 20px;
        }

        .book-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .book-item {
            width: 250px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            text-align: center;
        }

        .book-item:hover {
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        .logo-img {
    height: 100px; /* Adjust as needed */
    width: auto;
    margin-right: 100px;
    vertical-align: left;
}
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <a href="dashboard.php" class="logo">NIBS Library</a>
    <img src="data:image/png;base64,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" alt="NIBS Library Logo" class="logo-img">
</a>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="catalog.php"><i class="fas fa-book"></i> Catalog</a></li>
        <li><a href="services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="borrowed.php"><i class="fas fa-book-reader"></i> My Books</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="signin.php"><i class="fas fa-sign-in-alt"></i> Signin</a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> Signup</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Features Section -->
<section class="features">
    <div class="feature">
        <img src="data:image/png;base64,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" alt="Read Books">
        <h3>Read Library Books</h3>
        <p>Millions of books available for reading.</p>
    </div>
    <div class="feature">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7kNuv6axyg7rH74a4c__jwvsZcmZOckycLg&s" alt="Reading Goal">
        <h3>Set a Yearly Reading Goal</h3>
        <p>Track your progress and reading habits.</p>
    </div>
    <div class="feature">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPImC0UCGMLv-UyVqJ5OLFgi_08t1RoyMBTw&s" alt="Track Books">
        <h3>Organize Your Books</h3>
        <p>Keep track of your favorite books & reading log.</p>
    </div>
</section>

<!-- Trending Books Section -->
<section class="book-list">
    <h2>Trending Books</h2>
    <div class="book-container">
        <?php
        $query = "SELECT id, title, author, isbn, genre, availablecopies, publish_year FROM book ORDER BY RAND() LIMIT 5";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book-item'>
                        <h3>{$row['title']}</h3>
                        <p><strong>Author:</strong> {$row['author']}</p>
                        <p><strong>ISBN:</strong> {$row['isbn']}</p>
                        <p><strong>Genre:</strong> {$row['genre']}</p>
                        <p><strong>AvailableCopies:</strong> {$row['availablecopies']}</p>
                        <p><strong>Published:</strong> {$row['publish_year']}</p>
                        <form method='POST' action='borrow.php'>
                            <input type='hidden' name='book_id' value='{$row['id']}'>
                            <button type='submit' class='borrow-button'>Borrow</button>
                        </form>
                      </div>";
            }
        } else {
            echo "<p>No trending books available.</p>";
        }
        ?>
    </div>
</section>

<!-- Footer -->
    <footer>
    <p>&copy; <?php echo date("Y"); ?> NIBS Library. All Rights Reserved.</p>
    <p>Location: 123 Main Street, City, Country</p>
    <p>Building Number: 5</p>
    <p>Follow us: 
    <a href="https://facebook.com/yourpage" target="_blank" style="margin: 0 10px;">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/yourhandle" target="_blank" style="margin: 0 10px;">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="https://instagram.com/yourhandle" target="_blank" style="margin: 0 10px;">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://linkedin.com/in/yourhandle" target="_blank" style="margin: 0 10px;">
            <i class="fab fa-linkedin-in"></i>
        </a>

    </p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
