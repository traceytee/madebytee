<!DOCTYPE html>
<html>
<head>
    <title>Library Admin Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        label {
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="submit"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-book"></i> Library Admin Management</h2>

        <h3>Add New Book</h3>
        <form action="admin.php" method="POST">
            <label for="title"><i class="fas fa-book-open"></i> Title:</label>
            <input type="text" id="title" name="title">
            <label for="author"><i class="fas fa-user"></i> Author:</label>
            <input type="text" id="author" name="author">
            <label for="isbn"><i class="fas fa-barcode"></i> ISBN:</label>
            <input type="text" id="isbn" name="isbn">
            <label for="publish_year"><i class="fas fa-calendar-alt"></i> Publish Year:</label>
            <input type="number" id="publish_year" name="publish_year">
            <label for="genre"><i class="fas fa-tags"></i> Genre:</label>
            <input type="text" id="genre" name="genre">
            <input type="submit" name="add_book" value="Add Book">
        </form>

        <?php
        $servername = "localhost";
        $username = "root";   // Change to your MySQL username if different
        $password = "";       // Leave empty if using XAMPP default
        $dbname = "library_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Add Book
        if (isset($_POST['add_book'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $isbn = $_POST['isbn'];
            $publish_year = $_POST['publish_year'];
            $genre = $_POST['genre'];

            $sql = "INSERT INTO book (title, author, isbn, publish_year, genre) VALUES ('$title', '$author', '$isbn', '$publish_year', '$genre')";

            if ($conn->query($sql) === TRUE) {
                echo "New book added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Delete Book
        if (isset($_POST['delete_book'])) {
            $book_id = $_POST['book_id'];

            $sql = "DELETE FROM book WHERE id = $book_id";

            if ($conn->query($sql) === TRUE) {
                echo "Book deleted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Update Borrow Status
        if (isset($_POST['update_status'])) {
            $borrow_id = $_POST['borrow_id'];
            $status = $_POST['status'];
            $returndate = $_POST['returndate'];

            $sql = "UPDATE borrow_record SET status = '$status', returndate = '$returndate' WHERE id = $borrow_id";

            if ($conn->query($sql) === TRUE) {
                echo "Borrow status updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        echo "<h3>Borrowed Books Status</h3>";
        $sql = "SELECT borrow_record.id as borrow_id, book.title, book.author, customer.name, borrow_record.due_date, borrow_record.return_date, borrow_record.status, borrow_record.transaction_id 
                FROM borrow_record 
                JOIN book ON borrow_record.book_id = book.id 
                JOIN customer ON borrow_record.customer_id = customer.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Transaction ID</th><th>Title</th><th>Author</th><th>Customer</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Update Status</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["transactionid"]. "</td>
                        <td>" . $row["title"]. "</td>
                        <td>" . $row["author"]. "</td>
                        <td>" . $row["name"]. "</td>
                        <td>" . $row["due_date"]. "</td>
                        <td>" . $row["returndate"]. "</td>
                        <td>" . $row["status"]. "</td>
                        <td>
                            <form action='admin.php' method='POST'>
                                <input type='hidden' name='borrow_id' value='" . $row["borrow_id"] . "'>
                                <label for='returndate'>Return Date:</label>
                                <input type='date' name='returndate'>
                                <select name='status'>
                                    <option value='borrowed'>Borrowed</option>
                                    <option value='returned'>Returned</option>
                                    <option value='overdue'>Overdue</option>
                                </select>
                                <input type='submit' name='update_status' value='Update'>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No borrowed books.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
