<?php
include 'db.php';
session_start();

// Ensure student_id is set in session
if (!isset($_SESSION['student_id'])) {
    die("<p style='color:red;'>You must be logged in to borrow a book.</p>");
}

$student_id = $_SESSION['student_id'];

// Validate book_id from GET
if (!isset($_GET['id'])) {
    die("<p style='color:red;'>No book selected.</p>");
}

$book_id = intval($_GET['id']); // sanitize input

// Insert into borrowings table
$sql = "INSERT INTO borrowings (student_id, id, borrow_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $student_id, $book_id);

if ($stmt->execute()) {
    // Update book status
    $update = $conn->prepare("UPDATE books SET available = FALSE WHERE id = ?");
    $update->bind_param("i", $book_id);
    $update->execute();

    echo "<p style='color:green;'>Book borrowed successfully!</p>";
    echo "<a href='my_books.php'>Go to My Books</a>";
} else {
    echo "<p style='color:red;'>Error borrowing book: " . $conn->error . "</p>";
}

$stmt->close();
$conn->close();
?>
