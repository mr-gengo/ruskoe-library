<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "rusere_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all lost books
$sql = "SELECT id, book_title, author, year, status FROM lost_books";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost Books Management</title>
<style>

    body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 20px;
}

.container {
  width: 800px;
  margin: auto;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px #ccc;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

table th, table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}

table th {
  background-color: #f2f2f2;
}

.btn-delete {
  display: inline-block;
  padding: 6px 10px;
  color: #fff;
  background-color: #e74c3c;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.btn-delete:hover {
  background-color: #c0392b;
}

.disabled {
  color: #aaa;
  font-style: italic;
}
</style>


</head>
<body>
  <div class="container">
    <h2>Lost Books Management</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Year</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['year']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
              <?php if ($row['status'] === 'paid'): ?>
                <a class="btn-delete" 
                   href="delete_lost_book.php?id=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this record?');">
                   Delete
                </a>
              <?php else: ?>
                <span class="disabled">Not Paid</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No lost books found.</td></tr>
      <?php endif; ?>
    </table>
  </div>
</body>
</html>
