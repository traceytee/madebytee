<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: borrowed.php");
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

// Fetch available books
$query = "SELECT id, title, author, availablecopies FROM book WHERE availablecopies > 0";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS Library | Borrow Books</title>
    <style>
        /* Navigation */
        nav {
            background-color: #007bff;
            padding: 10px 0;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 10px 15px;
            display: inline-block;
        }
        nav ul li a:hover {
            background-color: #0056b3;
            border-radius: 5px;
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
        .borrow-btn {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .borrow-btn:hover {
            background-color: #218838;
        }

        /* Footer */
        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="catalog.php">Catalog</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Available Books -->
<div class="container">
    <h1>Available Books</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Available Copies</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['availablecopies']) ?></td>
                    <td>
                        <form method="POST" action="process_borrow.php">
                            <input type="hidden" name="book_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="borrow-btn">Borrow</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> NIBS Library. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$connection->close();
?>
