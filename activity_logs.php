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

// Delete suspicious log
if (isset($_GET['delete'])) {
    $log_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM activity_logs WHERE log_id = ?");
    $stmt->bind_param("i", $log_id);
    $stmt->execute();
    $stmt->close();
    $message = '<div class="success-message">✅ Suspicious log deleted successfully!</div>';
}

// Fetch all logs
$result = $conn->query("SELECT activity_logs.*, users.username, users.role 
                        FROM activity_logs 
                        JOIN users ON activity_logs.user_id = users.user_id 
                        ORDER BY timestamp DESC");
$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        .container { width: 95%; margin: 40px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: #fff; }
        .success-message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        a.delete { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Activity Logs</h2>
    <?php echo $message; ?>

    <table>
        <tr>
            <th>Log ID</th>
            <th>User</th>
            <th>Role</th>
            <th>Action</th>
            <th>Timestamp</th>
            <th>IP Address</th>
            <th>Action</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo htmlspecialchars($log['log_id']); ?></td>
                <td><?php echo htmlspecialchars($log['username']); ?></td>
                <td><?php echo htmlspecialchars($log['role']); ?></td>
                <td><?php echo htmlspecialchars($log['action']); ?></td>
                <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                <td><a class="delete" href="?delete=<?php echo $log['log_id']; ?>" onclick="return confirm('Delete this log?');">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
