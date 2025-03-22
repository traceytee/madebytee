<?php
session_start();
require 'db.php'; // Ensure this file contains the correct DB connection

if (!isset($_SESSION['users_id'])) {
    die("Access denied!");
}

$user_id = $_SESSION['users_id'];

// Database credentials (ensure these are correct in db.php)
$hostname = "localhost"; // Change if using a remote DB
$username = "root";      // Default for XAMPP
$password = "";          // Default for XAMPP (empty password)
$dbname = "library_db";  // Ensure this matches your database name

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement
$query = "SELECT borrow_record.id, book.title, book.author, borrow_record.borrow_date, borrow_record.return_date 
          FROM borrow_record
          JOIN book ON borrow_record.book_id = book.id 
          WHERE borrow_record.users_id = ?";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $users_id);
$stmt->execute();
$result = $stmt->get_result();

$output = "";

while ($row = $result->fetch_assoc()) {
    $output .= "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['borrow_date']}</td>
                    <td>{$row['return_date']}</td>
                    <td><button onclick='viewBook({$row['id']})'>View</button></td>
                </tr>";
}

$stmt->close();
$conn->close();

echo $output;
?>
