<!DOCTYPE html>
<html>
<head>
    <title>Book Entry Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
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
        }
        label {
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-book"></i> Enter Book Information</h2>
        <form action="book_entry.php" method="POST">
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
            <input type="submit" value="Addbook">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "username";
            $password = "password";
            $dbname = "library_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO book (title, author, isbn, publish_year, genre) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $title, $author, $isbn, $publish_year, $genre);

            // Set parameters and execute
            $title = $_POST['title'];
            $author = $_POST['author'];
            $isbn = $_POST['isbn'];
            $publish_year = $_POST['publish_year'];
            $genre = $_POST['genre'];
            $stmt->execute();

            echo "New book record created successfully";

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
