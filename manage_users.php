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

// Handle Add User
if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($password)) {
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['message'] = "User added successfully!";
        } else {
            $_SESSION['error'] = "Error adding user.";
        }
        $stmt->close();
        header("Location: manage_users.php");
        exit();
    }
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user.";
    }
    $stmt->close();
    header("Location: manage_users.php");
    exit();
}

// Fetch Users
$query = "SELECT id, username, email FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .header, .footer { background-color: #007bff; color: white; text-align: center; padding: 15px 0; }
        .nav { display: flex; justify-content: center; background: #0056b3; padding: 10px 0; }
        .nav a { color: white; text-decoration: none; padding: 10px 15px; margin: 0 10px; font-weight: bold; }
        .nav a:hover { background-color: white; color: #0056b3; border-radius: 5px; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .message { color: green; font-weight: bold; text-align: center; }
        .error { color: red; font-weight: bold; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 8px 12px; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; }
        .btn-edit { background-color: #28a745; }
        .btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>

<div class="header"><h2>Admin Dashboard</h2></div>

<nav class="nav">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_books.php">Manage Books</a>
    <a href="admin_logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Manage Users</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <!-- Add User Form -->
    <div>
        <h3>Add New User</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <!-- Users Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="footer"><p>&copy; <?= date("Y"); ?> NIBS Library. All rights reserved.</p></div>

</body>
</html>

<?php $conn->close(); ?>
