<?php
// Database connection
$host = 'localhost';
$db_user = 'root';
$db_password = ''; // Change if needed
$db_name = 'library_db';

$connection = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch overdue books
$today = date("Y-m-d");
$query = "SELECT * FROM borrow_record WHERE due_date < '$today'";
$result = $connection->query($query);

// Handle notification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notify'])) {
    $customer_email = $_POST['customer_email'];
    $customer_name = $_POST['customer_name'];
    $book_title = $_POST['book_title'];

    // Simulate sending an email (replace this with actual email logic if needed)
    echo "<script>
        alert('Reminder sent to $customer_name ($customer_email) for overdue book: $book_title');
        window.location.href = 'overdue_books.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #d9534f;
            color: white;
        }
        .notify-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .notify-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Overdue Books</h1>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Book Title</th>
                <th>Due Date</th>
                <th>Notify</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['book_title']}</td>
                            <td>{$row['due_date']}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='customer_email' value='{$row['email']}'>
                                    <input type='hidden' name='customer_name' value='{$row['customer_name']}'>
                                    <input type='hidden' name='book_title' value='{$row['book_title']}'>
                                    <button type='submit' name='notify' class='notify-btn'>Notify</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No overdue books</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$connection->close();
?>
