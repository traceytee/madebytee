<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Database Connection
$connection = new mysqli("localhost", "root", "", "library_db");
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

// Fetch unpaid fines
$fine_query = "SELECT COALESCE(SUM(amount), 0) AS total_fines FROM fines WHERE user_id = ? AND status = 'Unpaid'";
$fine_stmt = $connection->prepare($fine_query);
$fine_stmt->bind_param("i", $user_id);
$fine_stmt->execute();
$fine_result = $fine_stmt->get_result();
$fine_data = $fine_result->fetch_assoc();
$total_fines = $fine_data['total_fines'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIBS Library | My Borrowed Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .fine-notification {
            background: #ffcc00;
            color: #333;
            padding: 15px;
            margin-bottom: 20px;
            font-weight: bold;
            border-radius: 5px;
        }
        .fine-notification a {
            color: #d9534f;
            font-weight: bold;
            text-decoration: none;
        }
        .fine-notification a:hover {
            text-decoration: underline;
        }
        .pay-fine-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .pay-fine-btn:hover {
            background-color: #c9302c;
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
    </style>
</head>
<body>

<!-- Borrowed Books Section -->
<div class="container">
    <h1>My Borrowed Books</h1>

    <!-- Fine Notification -->
    <?php if ($total_fines > 0): ?>
        <div class="fine-notification">
            <i class="fas fa-exclamation-circle"></i> You have unpaid fines totaling <strong>$<?= number_format($total_fines, 2) ?></strong>.
            <br>
            <a href="fines_and_payments.php" class="pay-fine-btn">Pay Fines Now</a>
        </div>
    <?php else: ?>
        <p style="color: green; font-weight: bold;">You have no unpaid fines. Keep up the good reading!</p>
    <?php endif; ?>

    <!-- Borrowed Books Table -->
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

</body>
</html>

<?php
// Close statements and connection
$stmt->close();
$fine_stmt->close();
$connection->close();
?>
