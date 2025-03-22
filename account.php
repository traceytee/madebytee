<?php
session_start();
require 'db.php'; // Ensure this file contains the correct database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$hostname = "localhost"; 
$username = "root";      
$password = "";          
$dbname = "library_db";  

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$query = "SELECT username, full_name, email, phone, join_date FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch borrowed books
$borrowed_books = [];
$query = "SELECT id, title, author, borrow_date, return_date FROM borrowed_books WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $borrowed_books[] = $row;
}
$stmt->close();

$conn->close();

// Generate the HTML output using PHP
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>NIBS Library | Account</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        nav { background-color: #007bff; padding: 15px; text-align: center; }
        nav ul { list-style-type: none; margin: 0; padding: 0; display: flex; justify-content: center; }
        nav ul li { margin: 0 15px; }
        nav ul li a { text-decoration: none; color: white; font-size: 18px; padding: 10px 15px; display: inline-block; }
        nav ul li a:hover { background-color: #0056b3; border-radius: 5px; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .profile-box { text-align: center; padding-bottom: 20px; }
        .profile-box h2 { color: #007bff; }
        .profile-box p { font-size: 16px; color: #333; margin: 5px 0; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        footer { background: #343a40; color: white; text-align: center; padding: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href='dashboard.php'>Home</a></li>
        <li><a href='catalog.php'>Catalog</a></li>
        <li><a href='services.php'>Services</a></li>
        <li><a href='contact.php'>Contact</a></li>
        <li><a href='account.php'>Account</a></li>
        <li><a href='logout.php'>Logout</a></li>
    </ul>
</nav>

<!-- Account Information -->
<div class='container'>
    <div class='profile-box'>
        <h2>Welcome, " . htmlspecialchars($user['full_name']) . "!</h2>
        <p><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($user['phone']) . "</p>
        <p><strong>Joined:</strong> " . date('F d, Y', strtotime($user['join_date'])) . "</p>
        <a href='edit_profile.php' class='btn'>Edit Profile</a>
        <a href='change_password.php' class='btn'>Change Password</a>
    </div>

    <h2>My Borrowed Books</h2>
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
        <tbody>";

if (!empty($borrowed_books)) {
    foreach ($borrowed_books as $book) {
        echo "<tr>
                <td>" . htmlspecialchars($book['id']) . "</td>
                <td>" . htmlspecialchars($book['title']) . "</td>
                <td>" . htmlspecialchars($book['author']) . "</td>
                <td>" . date('F d, Y', strtotime($book['borrow_date'])) . "</td>
                <td>" . date('F d, Y', strtotime($book['return_date'])) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No borrowed books found.</td></tr>";
}

echo "  </tbody>
    </table>
</div>

<!-- Footer -->
<footer>
    <p>&copy; " . date('Y') . " NIBS Library. All rights reserved. | <a href='terms.php' style='color:white;'>Terms & Conditions</a></p>
</footer>

</body>
</html>";
?>
