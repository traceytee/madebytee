<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Fines
$query = "SELECT fines.id, users.username, fines.amount, fines.status, fines.issued_date FROM fines 
          JOIN users ON fines.user_id = users.id ORDER BY fines.issued_date DESC";
$result = $conn->query($query);

// Mark Fine as Paid
if (isset($_GET['pay'])) {
    $id = $_GET['pay'];
    $conn->query("UPDATE fines SET status = 'Paid' WHERE id = $id");
    header("Location: manage_fines.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fines</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 5px 10px; color: white; text-decoration: none; border-radius: 5px; }
        .btn-pay { background-color: #28a745; }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Fines</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date Issued</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td>$<?= number_format($row['amount'], 2) ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['issued_date'] ?></td>
            <td>
                <?php if ($row['status'] == 'Unpaid'): ?>
                    <a href="?pay=<?= $row['id'] ?>" class="btn btn-pay">Mark as Paid</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
