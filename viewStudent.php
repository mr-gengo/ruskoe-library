<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = "";
$student_name = "";
$class = "";

// If search form submitted
if (isset($_POST['search_id'])) {
    $search_id = $_POST['search_id'];

    // Search from students table
    $stmt = $conn->prepare("SELECT student_id, student_name, class FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $search_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_id   = $row['student_id'];
        $student_name = $row['student_name'];
        $class        = $row['class'];
    } else {
        echo '<div style="color:red;">❌ Student not found!</div>';
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Student</title>
</head>
<body>
    <h2>Search Student</h2>

    <!-- Search form -->
    <form method="POST" action="">
        <label for="search_id">Enter Student ID:</label><br>
        <input type="text" name="search_id" required>
        <input type="submit" value="Search">
    </form>

    <hr>

    <!-- Display results -->
    <?php if ($student_id): ?>
        <h3>Student Details</h3>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
        <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
    <?php endif; ?>
</body>
</html>
