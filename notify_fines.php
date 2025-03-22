<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "library_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You need to <a href='signin.php'>signin</a> to view fines.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch total unpaid fines for the logged-in user
$fine_query = "SELECT COALESCE(SUM(amount), 0) AS total_fines FROM fines WHERE user_id = ? AND status = 'Unpaid'";
$stmt = $conn->prepare($fine_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$fine_result = $stmt->get_result();
$fine_data = $fine_result->fetch_assoc();
$total_fines = $fine_data['total_fines'] ?? 0;

// Close the statement
$stmt->close();

// Display fine notification
if ($total_fines > 0) {
    echo "<div style='background: #ffcc00; color: #333; padding: 15px; text-align: center; font-weight: bold;'>
            <i class='fas fa-exclamation-circle'></i> You have unpaid fines totaling <strong>\$$total_fines</strong>.
            Please <a href='fines_and_payments.php' style='color: #d9534f; font-weight: bold;'>click here</a> to pay.
          </div>";
} else {
    echo "<p style='text-align: center; color: green;'>You have no unpaid fines. Keep up the good reading!</p>";
}

// Close database connection
$conn->close();
?>
