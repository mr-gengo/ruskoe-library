<?php
session_start();

// Allow only teachers and admins
$allowed_roles = ['teacher', 'admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    die("Access denied!");
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Book

if (isset($_POST['delete'])) {
    $id = (int)$_POST['delete_book_id']; // cast to int for safety

    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Book deleted successfully!";
    } else {
        echo "Error deleting book: " . $stmt->error;
    }

    $stmt->close();
}


// Handle Update Book
if (isset($_POST['update'])) {
    $book_id = $_POST['update_book_id'];
    $title   = $_POST['new_title'];
    $author  = $_POST['new_author'];
    $year    = $_POST['new_year'];

    $sql = "UPDATE books SET title='$title', author='$author', year='$year' WHERE book_id='$book_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully!";
    } else {
        echo "Error updating book: " . $conn->error;
    }
}

// Handle Charge for Lost Book
if (isset($_POST['charge'])) {
    $student_id = $_POST['lost_student_id'];
    $book_id    = $_POST['lost_book_id'];
    $amount     = $_POST['charge_amount'];

    $sql = "INSERT INTO lost_books (student_id, book_id, charge_amount, date) 
            VALUES ('$student_id', '$book_id', '$amount', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "Charge recorded successfully!";
    } else {
        echo "Error recording charge: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html> <html>
    <head>
         <title>  Admin Dashboard</title>
         <style>
/* Background with overlay */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: url('images/bg2.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5); /* dark overlay for readability */
    z-index: -1;
}

/* Container for forms */
.forms-container {
    display: flex;
    gap: 30px;
    align-items: flex-start;
    margin-top: 40px;
}

/* Each form styled as a card */
form {
    flex: 1;
    max-width: 400px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

form:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.25);
}

/* Decorative gradient border effect */
form::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 12px;
    padding: 2px;
    background: linear-gradient(135deg, #3498db, #9b59b6);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: destination-out;
    mask-composite: exclude;
    pointer-events: none;
}

/* Headings */
h2 {
    text-align: center;
    color: #fff;
    margin-top: 20px;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.7);
}

h3 {
    color: #34495e;
    margin-bottom: 20px;
    font-weight: bold;
    text-align: center;
}

/* Labels */
label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 8px;
}

/* Inputs */
input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.5);
    outline: none;
}

/* Buttons */
button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
}

button:hover {
    background: linear-gradient(135deg, #2980b9, #1f6391);
    transform: scale(1.03);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: -400px; /* hidden off-screen */
    padding: 14px 22px;
    border-radius: 8px;
    font-weight: bold;
    opacity: 0;
    transition: right 0.5s ease, opacity 0.5s ease;
    z-index: 9999;
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.notification.show {
    right: 20px; /* slides in */
    opacity: 1;
}

.success {
    background: #2ecc71;
    color: #fff;
}

.error {
    background: #e74c3c;
    color: #fff;
}


</style>

 </head>
 <body>
<h2>Library Inventory Management</h2>
<div class="forms-container">
<!-- Delete Book -->
<form method="POST" action="inventory.php">
    <h3>Delete Book</h3>

    <label for="delete_book_id">Book ID:</label><br>
    <input type="text" id="delete_book_id" name="delete_book_id"><br><br>

    <label for="delete_book_title">Book Title:</label><br>
    <input type="text" id="delete_book_title" name="delete_book_title"><br><br>

    <button type="submit" name="delete">Delete Book</button>
    <!-- Notification area -->
<div id="notification" class="notification"></div>
</form>




<!-- Update Book -->
<form method="POST" action="inventory.php">
    <h3>Update Book</h3>
    <label for="update_book_id">Book ID:</label><br>
    <input type="text" id="update_book_id" name="update_book_id" required><br><br>

    <label for="new_title">New Title:</label><br>
    <input type="text" id="new_title" name="new_title"><br><br>

    <label for="new_author">New Author:</label><br>
    <input type="text" id="new_author" name="new_author"><br><br>

    <label for="new_year">New Year:</label><br>
    <input type="text" id="new_year" name="new_year"><br><br>

    <button type="submit" name="update">Update Book</button>
</form>

<hr>

<!-- Charge for Lost Book -->
<form method="POST" action="inventory.php">
    <h3>Charge for Lost Book</h3>
    <label for="lost_student_id">Student ID:</label><br>
    <input type="text" id="lost_student_id" name="lost_student_id" required><br><br>

    <label for="lost_book_id">Book ID:</label><br>
    <input type="text" id="lost_book_id" name="lost_book_id" required><br><br>

    <label for="charge_amount">Charge Amount:</label><br>
    <input type="number" id="charge_amount" name="charge_amount" required><br><br>

    <button type="submit" name="charge">Charge Student</button>
</form>
</div>
<script>
    // Form validation and feedback
    document.getElementById("updateForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let bookId = document.getElementById("update_book_id").value.trim();
        let year = document.getElementById("new_year").value.trim();
        let message = document.getElementById("updateMessage");

        if (year && isNaN(year)) {
            message.textContent = "Year must be a number!";
            message.className = "message error";
        } else {
            message.textContent = "Book update submitted successfully!";
            message.className = "message success";
            this.submit(); // proceed with form submission
        }
    });

    document.getElementById("chargeForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let amount = document.getElementById("charge_amount").value;
        let message = document.getElementById("chargeMessage");

        if (amount <= 0) {
            message.textContent = "Charge amount must be greater than 0!";
            message.className = "message error";
        } else {
            message.textContent = "Charge submitted successfully!";
            message.className = "message success";
            this.submit();
        }
    });

    <script>
const deleteBtn = document.getElementById("deleteBtn");
const notification = document.getElementById("notification");

function showNotification(message, type) {
    notification.textContent = message;
    notification.className = "notification " + type + " show";
    setTimeout(() => {
        notification.classList.remove("show");
    }, 3000);
}

deleteBtn.addEventListener("click", function() {
    // Yes/No confirmation popup
    let confirmDelete = confirm("Are you sure you want to delete Book ID 2?");
    if (confirmDelete) {
        // Simulate successful deletion (replace with AJAX call to PHP)
        showNotification("Book deleted successfully!", "success");

        // Example: send request to server
        /*
        fetch('delete.php?id=2', { method: 'POST' })
            .then(res => res.text())
            .then(data => showNotification(data, "success"))
            .catch(err => showNotification("Error deleting book!", "error"));
        */
    } else {
        showNotification("Deletion cancelled.", "error");
    }
});
</script>


</body>
</html>






