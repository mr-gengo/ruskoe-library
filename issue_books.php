<?php
session_start();

// Only teachers and admins can issue books
$allowed_roles = ['teacher', 'admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    die("Access denied!");
}

$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = "";
$student_name = "";
$class = "";
$book_title = "";
$quantity = 1;

// If search form submitted
if (isset($_POST['search_id'])) {
    $search_id = $_POST['search_id'];

    $stmt = $conn->prepare("SELECT student_id, student_name, class FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $search_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_id   = $row['student_id'];
        $student_name = $row['student_name'];
        $class        = $row['class'];
    } else {
        echo '<div class="error-message">❌ Student not found!</div>';
    }
    $stmt->close();
}


// If issuing book
if (isset($_POST['issue'])) {
    $student_id   = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $class        = $_POST['class'];
    $book_title   = $_POST['book_title'];
    $quantity     = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $issue_date   = date('Y-m-d');
    $issued_by    = $_SESSION['username']; // teacher/admin username

    $stmt = $conn->prepare("INSERT INTO issued_books(student_id, student_name, class, book_title, issue_date, quantity, issued_by) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssis", $student_id, $student_name, $class, $book_title, $issue_date, $quantity, $issued_by);

    if ($stmt->execute()) {
        echo '<div class="success-message">✅ Book issued successfully!<br>
              Quantity: ' . htmlspecialchars($quantity) . '<br>
              Issued By: ' . htmlspecialchars($issued_by) . '</div>';
    } else {
        echo '<div class="error-message">❌ Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

$conn->close();
?>




<!DOCTYPE html> <html>
<head>
    <title>Teacher Dashboard</title>
    <style>
     body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/bg2.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    h1 {
    position: center;   /* fixes it relative to the page */
    top: 80px;            /* distance from the top */
    left: 120px;           /* distance from the left edge */
    color: #080808;
    font-size: 28px;
    margin: 0;            /* remove default margin */
    text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
}
form {
    max-width: 800px;              /* wider form */
    margin: 20px auto;             /* center horizontally */
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);

    display: flex;                 /* use flexbox */
    flex-wrap: wrap;               /* allow wrapping if needed */
    justify-content: space-between;/* spread fields across */
    gap: 20px;                     /* spacing between inputs */
}

form label {
    flex: 1 1 100%;                /* labels take full width */
    font-weight: bold;
    color: #555;
}

form input[type="text"],
form input[type="password"],
form input[type="number"],
form input[type="date"] {
    flex: 1 1 45%;                 /* inputs side by side */
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

.button {
    flex: 1 1 45%;                 /* buttons side by side */
    padding: 12px;
    background: #3498db;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.button:hover {
    background: #2980b9;
    transform: scale(1.03);
}
   .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin: 20px 0;
            position:top;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .error-message 
        { background-color: #f8d7da; 
        color: #721c24;
         border: 1px solid #f5c6cb; 
        padding: 15px; 
        margin: 20px 0; border-radius: 5px; 
        font-family: Arial, sans-serif;
         text-align: center;
          }
</style>

    </head>
<body>

    <!-- Search form -->
    <form method="POST" action="">
        <label for="search_id">Enter Student ID:</label><br>
        <input type="text" name="search_id" required>
        <input type="submit" value="Search">
    </form>

    <hr>

    <!-- Issue form -->
    <!-- Issue form -->
    <form method="POST" action="">
        <p> Issue books By student_id</p>
        <label for="student_id">Student ID:</label><br>
        <input type="text" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" readonly><br><br>

        <label for="student_name">Student Name:</label><br>
        <input type="text" name="student_name" value="<?php echo htmlspecialchars($student_name); ?>" readonly><br><br>

        <label for="class">Class:</label><br>
        <input type="text" name="class" value="<?php echo htmlspecialchars($class); ?>" readonly><br><br>

        <label for="book_title">Book Title:</label><br>
        <input type="text" name="book_title" required><br><br>

        <label for="quantity">Quantity:</label><br>
        <input type="number" name="quantity" min="1" value="1" required><br><br>

        <label for="issued_by">Issued By:</label><br>
<input type="text" name="issued_by" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly><br><br>


        <input type="submit" name="issue" value="Issue Book" class="button">
    </form>
</body>
</html>

    

