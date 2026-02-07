<?php
session_start();

// Only teachers and admins can record lost books
$allowed_roles = ['teacher', 'admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    die("Access denied!");
}

$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id    = $_POST['student_id'];
    $student_name  = $_POST['student_name'];
    $title         = $_POST['title'];
    $author        = $_POST['author'];
    $year          = $_POST['year'];
    $charge_amount = $_POST['charge_amount'];
    $date_lost     = $_POST['date_lost'];
    $issued_by     = $_SESSION['username']; // teacher/admin username

    $stmt = $conn->prepare("INSERT INTO lost_books(student_id, student_name, title, author, year, charge_amount, date_lost, issued_by) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isssidss", $student_id, $student_name, $title, $author, $year, $charge_amount, $date_lost, $issued_by);

    if ($stmt->execute()) {
        $message = '<div class="success-message">✅ Lost book recorded successfully!</div>';
    } else {
        $message = '<div class="error-message">❌ Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lost Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Lost Book</h2>
        <?php echo $message; ?>
        <form method="POST" action="">
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" required>

            <label for="student_name">Student Name:</label>
            <input type="text" name="student_name" required>

            <label for="title">Book Title:</label>
            <input type="text" name="title" required>

            <label for="author">Author:</label>
            <input type="text" name="author">

            <label for="year">Publication Year:</label>
            <input type="number" name="year" min="1000" max="9999">

            <label for="charge_amount">Charge Amount (Fine):</label>
            <input type="number" step="0.01" name="charge_amount" required>

            <label for="date_lost">Date Lost:</label>
            <input type="date" name="date_lost" required>

            <input type="submit" value="Record Lost Book">
        </form>
    </div>
</body>
</html>
