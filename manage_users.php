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

// Add new user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hashing
    $role     = $_POST['role'];
    $email    = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users(username, password, role) VALUES (?, ?, ?, )");
    $stmt->bind_param("ssss", $username, $password, $role, );

    if ($stmt->execute()) {
        $message = '<div class="success-message">✅ User added successfully!</div>';
    } else {
        $message = '<div class="error-message">❌ Error: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Delete user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    $message = '<div class="success-message">✅ User deleted successfully!</div>';
}

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY role");
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        .container { width: 90%; margin: 40px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; }
        form { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #007bff; color: #fff; border: none; cursor: pointer; margin-top: 15px; }
        input[type="submit"]:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: #fff; }
        .success-message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .error-message { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        a.delete { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Users</h2>
    <?php echo $message; ?>

    <!-- Add User Form -->
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Email:</label>
        <input type="email" name="email">

        <label>Role:</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" name="add_user" value="Add User">
    </form>

    <!-- User List -->
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Role</th><th>Remove</th><th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
               
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><a class="delete" href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a></td>
                <td><a class="update"    href="?update=<?php echo $user['id']; ?>" onclick="return confirm('Change this user\'s role to admin?');"> Make Admin
  </a>
</td>

            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
