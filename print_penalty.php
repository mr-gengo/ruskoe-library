<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example: get lost book record by ID passed in URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT student_name, class, book_title, amount, due_date 
            FROM lost_books 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $studentName = $row['student_name'];
        $class       = $row['class'];
        $bookTitle   = $row['book_title'];
        $amount      = $row['amount'];
        $dueDate     = $row['due_date'];
    } else {
        die("No record found.");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Penalty Slip</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .slip {
            border: 1px solid #000;
            padding: 20px;
            width: 400px;
            margin: 20px auto;
        }
        h2 { text-align: center; }
        .details p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="slip">
        <h2>Penalty Slip</h2>
        <div class="details">
            <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
            <p><strong>Book Title:</strong> <?php echo htmlspecialchars($title); ?></p>
            <p><strong>Penalty Amount:</strong> $<?php echo number_format($charge_amount, 2); ?></p>
            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($dueDate); ?></p>
        </div>
        <p style="margin-top:20px; text-align:center;">
            Please settle this penalty before the due date.
        </p>
    </div>
</body>
</html>
