<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare statement
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Save session data
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            // Redirect based on role
            if ($role === "admin") {
                header("Location: Admin.php");
                exit();
            } elseif ($role === "teacher") {
                header("Location: teacher_dashboard.php");
                exit();
            } elseif ($role === "student") {
                header("Location: student_dashboard.php");
                exit();
            } else {
                echo "Unknown role.";
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}
$conn->close();
?>
