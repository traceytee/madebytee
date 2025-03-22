<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Background Styling */
        body {
            background: url('https://st.hzcdn.com/simgs/pictures/home-offices/our-rolling-and-hook-over-ladders-the-library-ladder-company-img~5fd1aea90d2c8ebf_9-1342-1-a006117.jpg') no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Navigation Bar */
        .nav-bar {
            background: rgba(0, 123, 255, 0.9);
            padding: 15px 0;
            width: 100%;
            position: fixed;
            top: 0;
            text-align: center;
        }

        .nav-bar ul {
            list-style: none;
            padding: 0;
        }

        .nav-bar ul li {
            display: inline;
            margin: 0 15px;
        }

        .nav-bar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            transition: 0.3s;
        }

        .nav-bar ul li a:hover {
            background: white;
            color: #007bff;
            border-radius: 5px;
        }

        /* Dashboard Box */
        .dashboard-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
            margin-top: 80px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #007bff;
        }

        /* Dashboard Links */
        .dashboard-links {
            margin-top: 20px;
        }

        .dashboard-links a {
            display: block;
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 10px auto;
            width: 80%;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        .dashboard-links a:hover {
            background: #0056b3;
        }

        /* Footer */
        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="nav-bar">
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="fines_and_payments.php"><i class="fas fa-money-bill-wave"></i> Fines & Payments</a></li>
            <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>

        <div class="dashboard-links">
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a>
            <a href="fines_and_payments.php"><i class="fas fa-money-bill-wave"></i> Fines & Payments</a>
            <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> NIBS Library - Admin Panel. All Rights Reserved.</p>
    </footer>

</body>
</html>