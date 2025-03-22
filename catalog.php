<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Database Connection
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'library_db';

$connection = new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch borrowed books
$user_id = $_SESSION['user_id'];
$query = "SELECT book.id AS book_id, book.title, book.author, borrow_record.borrow_date, borrow_record.return_date 
          FROM borrow_record
          JOIN book ON borrow_record.book_id = book.id
          WHERE borrow_record.customer_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS Library | Account</title>
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

        /* Page Styling */
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }

        /* Footer */
        footer {
            background: #007bff;
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
        <li><a href="borrowed.php"><i class="fas fa-book-reader"></i> My Books</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

<!-- Account Information -->
<div class="container">
    <h1>My Borrowed Books</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['book_id']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                        <td><?= !empty($row['return_date']) ? htmlspecialchars($row['return_date']) : '<span style="color:red;">Not returned yet</span>' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan='5'>You haven't borrowed any books yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer>
    <p>&copy; <?= date("Y"); ?> NIBS Library. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$stmt->close();
$connection->close();
?>
