<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Book
if (isset($_POST['add_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $availablecopies = (int)$_POST['availablecopies'];
    $publish_year = (int)$_POST['publish_year'];

    if (!empty($title) && !empty($author) && !empty($isbn) && !empty($genre) && $publish_year > 0) {
        $query = "INSERT INTO book (title, author, isbn, genre, availablecopies, publish_year) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiii", $title, $author, $isbn, $genre, $availablecopies, $publish_year);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_books.php");
        exit();
    }
}

// Handle Edit Book
if (isset($_POST['edit_book'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $availablecopies = (int)$_POST['availablecopies'];
    $publish_year = (int)$_POST['publish_year'];

    if (!empty($title) && !empty($author) && !empty($isbn) && !empty($genre) && $publish_year > 0) {
        $query = "UPDATE book SET title = ?, author = ?, isbn = ?, genre = ?, availablecopies = ?, publish_year = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiiii", $title, $author, $isbn, $genre, $availablecopies, $publish_year, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_books.php");
        exit();
    }
}

// Handle Delete Book
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM book WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_books.php");
    exit();
}

// Fetch Books
$query = "SELECT * FROM book";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .header, .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px 0;
        }
        .nav {
            display: flex;
            justify-content: center;
            background: #0056b3;
            padding: 10px 0;
        }
        .nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 10px;
            font-weight: bold;
        }
        .nav a:hover {
            background-color: white;
            color: #0056b3;
            border-radius: 5px;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn {
            padding: 8px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .form-container {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container input, .form-container button {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
        }
        .form-container button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Admin Dashboard</h2>
</div>

<nav class="nav">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_books.php">Manage Books</a>
    <a href="admin_logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Manage Books</h2>

    <!-- Add Book Form -->
    <div class="form-container">
        <h3>Add New Book</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="text" name="isbn" placeholder="ISBN" required>
            <input type="text" name="genre" placeholder="Genre" required>
            <input type="number" name="availablecopies" placeholder="Available Copies" required>
            <input type="number" name="publish_year" placeholder="Publish Year" required>
            <button type="submit" name="add_book">Add Book</button>
        </form>
    </div>

    <!-- Books Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Genre</th>
            <th>Available Copies</th>
            <th>Publish Year</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['isbn']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
                <td><?= htmlspecialchars($row['availablecopies']) ?></td>
                <td><?= htmlspecialchars($row['publish_year']) ?></td>
                <td><a href="?delete=<?= $row['id'] ?>" class="btn btn-delete">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="footer">
    <p>&copy; <?php echo date("Y"); ?> NIBS Library. All rights reserved.</p>
</div>

</body>
</html>

<?php $conn->close(); ?>
