<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['book_id'])) {
    exit("Unauthorized access");
}

$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'library_db';

$connection = new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$book_id = $_POST['book_id'];

$query = "SELECT title, author, description, genre, publish_year FROM books WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($book) {
    echo "<p><strong>Title:</strong> {$book['title']}</p>
          <p><strong>Author:</strong> {$book['author']}</p>
          <p><strong>Genre:</strong> {$book['genre']}</p>
          <p><strong>Publish Year:</strong> {$book['publish_year']}</p>
          <p><strong>Description:</strong> {$book['description']}</p>";
} else {
    echo "<p>Book details not found.</p>";
}

$stmt->close();
$connection->close();
?>
