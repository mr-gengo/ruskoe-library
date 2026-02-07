<?php
session_start();

// Only admins can access this page
if ($_SESSION['role'] !== 'admin') {
    die("Access denied!");
}

$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle password reset
if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $new_password, $username);

    if ($stmt->execute()) {
        $message = '<div class="success-message">✅ Password reset successfully for user: ' . htmlspecialchars($username) . '</div>';
    } else {
        $message = '<div class="error-message">❌ Error: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Handle account status update (disable/enable)
if (isset($_POST['update_status'])) {
    $username = $_POST['username'];
    $status   = $_POST['status'];

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $status, $username);

    if ($stmt->execute()) {
        $message = '<div class="success-message">✅ Account status updated for user: ' . htmlspecialchars($username) . '</div>';
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
    <title>Account Recovery</title>
    <style>
         body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/lok.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
        .container { width: 500px; margin: 50px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        label { font-weight: bold; display: block; margin-top: 10px; color: #555; }
        input[type="text"], input[type="password"], select {
            width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] {
            margin-top: 20px; width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;
        }
        input[type="submit"]:hover { background: #0056b3; }
        .success-message { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .error-message { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Recovery</h2>
        <?php echo $message; ?>

        <!-- Reset Password Form -->
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>

            <input type="submit" name="reset_password" value="Reset Password">
        </form>

        <hr>

        <!-- Update Account Status Form -->
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="status">Account Status:</label>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="disabled">Disabled</option>
            </select>

            <input type="submit" name="update_status" value="Update Status">
        </form>
    </div>
</body>
</html>
