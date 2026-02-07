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

// Handle notification submission
if (isset($_POST['send_notification'])) {
    $recipient_role = $_POST['recipient_role'];
    $notif_message  = $_POST['message'];
    $created_by     = $_SESSION['username']; // admin username

    $stmt = $conn->prepare("INSERT INTO notifications(recipient_role, message, created_by) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $recipient_role, $notif_message, $created_by);

    if ($stmt->execute()) {
        $message = '<div class="success-message">✅ Notification sent successfully!</div>';
    } else {
        $message = '<div class="error-message">❌ Error: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Fetch all notifications
$result = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Send Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        .container { width: 80%; margin: 40px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        form { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        textarea, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { margin-top: 15px; width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        input[type="submit"]:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: #fff; }
        .success-message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .error-message { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Send Notifications</h2>
    <?php echo $message; ?>

    <!-- Notification Form -->
    <form method="POST" action="">
        <label for="recipient_role">Recipient Role:</label>
        <select name="recipient_role" required>
            <option value="student">Students</option>
            <option value="teacher">Teachers</option>
            <option value="admin">Admins</option>
            <option value="all">All Users</option>
        </select>

        <label for="message">Message:</label>
        <textarea name="message" rows="4" required></textarea>

        <input type="submit" name="send_notification" value="Send Notification">
    </form>

    <!-- Notifications List -->
    <h3>All Notifications</h3>
    <table>
        <tr>
            <th>ID</th><th>Recipient</th><th>Message</th><th>Created By</th><th>Date</th>
        </tr>
        <?php foreach ($notifications as $notif): ?>
            <tr>
                <td><?php echo htmlspecialchars($notif['notification_id']); ?></td>
                <td><?php echo htmlspecialchars($notif['recipient_role']); ?></td>
                <td><?php echo htmlspecialchars($notif['message']); ?></td>
                <td><?php echo htmlspecialchars($notif['created_by']); ?></td>
                <td><?php echo htmlspecialchars($notif['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
