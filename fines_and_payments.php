<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch unpaid fines
$fine_query = "SELECT id, amount, due_date FROM fines WHERE user_id = ? AND status = 'Unpaid'";
$stmt = $conn->prepare($fine_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fines | NIBS Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .pay-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .pay-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Unpaid Fines</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>Fine ID: <?= htmlspecialchars($row['id']) ?> | Amount: $<?= number_format($row['amount'], 2) ?> | Due: <?= htmlspecialchars($row['due_date']) ?>
                    <a href="pay_fine.php?fine_id=<?= $row['id'] ?>" class="pay-btn">Pay Now</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No unpaid fines.</p>
    <?php endif; ?>

    <a href="borrowed.php">Back to Borrowed Books</a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
