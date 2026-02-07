<?php
session_start();

// Restrict access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Panel</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f6f9; }
            .sidebar { width: 200px; float: left; background: #cc6a4d; color: #fff; height: 100vh; padding: 20px; }
            .sidebar a { display: block; color: #fff; padding: 10px; text-decoration: none; margin-bottom: 10px; }
            .sidebar a:hover { background: #99e015; }
            .content { margin-left: 220px; padding: 20px; }
            h2 { color: #333; }
            .card { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

            /* Gallery layout */
.image-gallery {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2 images per row */
  gap: 20px;
  margin-top: 30px;
}

/* Image card styling */
.image-card {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  transform: scale(0.9);
  opacity: 0;
  animation: fadeInUp 1s ease forwards;
}

/* Staggered animation for each card */
.image-card:nth-child(1) { animation-delay: 0.2s; }
.image-card:nth-child(2) { animation-delay: 0.4s; }
.image-card:nth-child(3) { animation-delay: 0.6s; }
.image-card:nth-child(4) { animation-delay: 0.8s; }

.image-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.6s ease, filter 0.6s ease;
}

/* Hover animation */
.image-card:hover img {
  transform: scale(1.1) rotate(2deg);
  filter: brightness(1.2) saturate(1.2);
}

/* Entrance animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

        </style>
    </head>
    <body>
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <a href="manage_users.php">Manage Users</a>
            <a href="account_recovery.php">Account Recovery</a>
            <a href="activity_logs.php">Suspicious Activity</a>
            <a href="Notification.php">Send Notifications</a>
            <a href="view_books.php">View Book Quantities</a>
            <a href="delete_books.php">Delete Lost Books</a>
       
        </div>
        <div class="content">
            <h2>Welcome, Admin</h2>
            <div class="card">
                <p>Select an option from the sidebar to manage the system.</p>
            </div>
        </div>

        <div class="image-gallery">
  <div class="image-card">
    <img src="images/pw3.jpg" alt="Admin Image 1">
  </div>
  <div class="image-card">
    <img src="images/pw2.jpg" alt="Admin Image 2">
  </div>
  <div class="image-card">
    <img src="images/sh.jpg" alt="Admin Image 3">
  </div>
  <div class="image-card">
    <img src="images/sh2.jpg" alt="Admin Image 4">
  </div>
</div>

    </body>
    </html>
    