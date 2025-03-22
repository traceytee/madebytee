<?php
session_start();
if (!isset($_SESSION['users_id'])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['users_id'];

    // Check if the book is available
    $query = "SELECT availablecopies FROM book WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($availablecopies);
    $stmt->fetch();
    $stmt->close();

    if ($availablecopies > 0) {
        // Reduce book count
        $updateQuery = "UPDATE book SET availablecopies = availablecopies - 1 WHERE id = ?";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        // Insert into borrow_record
        $insertQuery = "INSERT INTO borrow_record (customer_id, book_id, borrow_date) VALUES (?, ?, NOW())";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param("ii", $users_id, $book_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to borrowed books page
        header("Location: borrowed.php");
        exit();
    } else {
        echo "<script>
                alert('Sorry, this book is not available.');
                window.location.href = 'borrow.php';
              </script>";
    }
}

$connection->close();
?>
