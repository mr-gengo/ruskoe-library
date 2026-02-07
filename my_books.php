<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$books = [];
$student_id = null;

// If learner submits their student ID
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    $stmt = $conn->prepare("SELECT book_title, class, issue_date, quantity, issued_by 
                            FROM issued_books 
                            WHERE student_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
}
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    $stmt->close();


$conn->close();
?>




<!DOCTYPE html>
<html>
<head>
    <title>My Borrowed Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        .no-books {
            text-align: center;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>My Borrowed Books</h2>

    <!-- Student ID input form -->
    <form method="POST" action="">
        <input type="text" name="student_id" placeholder="Enter Your Student ID" required>
        <input type="submit" value="View My Books">
    </form>

    <?php if ($student_id): ?>
        <?php if (count($books) > 0): ?>
            <table>
                <tr>
                    <th>Book Title</th>
                    <th>Class</th>
                    <th>Issue Date</th>
                    <th>Quantity</th>
                    <th>Issued By</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                        <td><?php echo htmlspecialchars($book['class']); ?></td>
                        <td><?php echo htmlspecialchars($book['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($book['issued_by']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="no-books">No borrowed books found for Student ID: <?php echo htmlspecialchars($student_id); ?></div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
