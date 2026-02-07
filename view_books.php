<?php
include 'db.php'; // your DB connection file

// Fetch available books
$sql = "SELECT id, title, author, quantity, available FROM books WHERE available=TRUE";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Books</title>
   <style>
  body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/bg2.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding-top: 80px;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
.container {
    width: 80%;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.book-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.book-table th, .book-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
}

.book-table th {
    background-color: #007BFF;
    color: white;
}

.book-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.book-table tr:hover {
    background-color: #f1f1f1;
}

.btn {
    display: inline-block;
    padding: 8px 12px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
}

.btn:hover {
    background-color: #218838;
}

.no-books {
    text-align: center;
    color: red;
    font-weight: bold;
}

   </style>
</head>
<body>
    <div class="container">
        <h2>📚 Available Books</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table class='book-table'>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['title']."</td>
                        <td>".$row['author']."</td>
                        <td>".$row['quantity']."</td>
                        <td>".($row['available'] ? "Available" : "Not Available")."</td>
                        <td><a class='btn' href='borrow.php?id=".$row['id']."'>Borrow</a></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-books'>No books available right now.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
