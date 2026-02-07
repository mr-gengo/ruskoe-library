<?php
session_start();
include 'db.php';

// Restrict access
if ($_SESSION['role'] != 'teacher' && $_SESSION['role'] != 'admin') {
    die("Access denied!");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title    = trim($_POST['title']);
    $author   = trim($_POST['author']);
    $quantity = intval($_POST['quantity']);
    $year     = intval($_POST['year']);

    $stmt = $conn->prepare("INSERT INTO books (title, author, quantity, year) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssii", $title, $author, $quantity, $year);

    if ($stmt->execute()) {
        $message = "✅ Book added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <style>
        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/bg3.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
        .form-container {
            background: #fff;
            padding: 2rem 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            width: 350px;
            animation: fadeIn 0.8s ease-in-out;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        input:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 8px rgba(74,144,226,0.4);
        }
        button {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #4a90e2, #9013fe);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #357ABD, #6A0DAD);
        }
        .message {
            text-align: center;
            margin-top: 1rem;
            font-weight: bold;
            color: #333;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body> 
      <img src="images/icon.jpg" alt="School Logo" class="logo">
         
    <div class="form-container">
        <h2>Add a New Book</h2>
        <form method="POST" action="">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title" required>

            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>

            <label>Quantity:</label> 
            <input type="number" name="quantity" min="1" required><br>

            <label for="year">Year</label> 
            <input type="number" id="year" name="year" min="1000" max="9999" required>
            
            <button type="submit">Add Book</button>
        </form>
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
    </div>
</body>
</html>
