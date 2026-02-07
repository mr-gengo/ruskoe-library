<?php
session_start();

// Restrict access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style> /* General page styling */
 body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/bg2.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

/* Heading */
h1 {
    position: absolute;   /* fixes it relative to the page */
    top: 20px;            /* distance from the top */
    left: 20px;           /* distance from the left edge */
    color: #fff;
    font-size: 28px;
    margin: 0;            /* remove default margin */
    text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
}

/* Navigation container */
.admin-nav {
    width: 250px;
    background: #fff;
    border-right: 1px solid #ddd;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 80px; /* space below header */
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}

/* Navigation list */
.admin-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Navigation items */
.admin-nav ul li {
    margin: 0;
}

/* Links */
.admin-nav ul li a {
    display: block;
    padding: 15px 20px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

/* Hover effects */
.admin-nav ul li a:hover {
    background: #f0f4ff;
    color: #4a90e2;
    border-left: 4px solid #4a90e2;
    transform: translateX(5px);
}

/* Active state (optional) */
.admin-nav ul li a.active {
    background: #e6ecff;
    color: #9013fe;
    border-left: 4px solid #9013fe;
}
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Center the menu */
.teacher-menu {
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80vh; /* centers vertically */
    gap: 20px; /* spacing between buttons */
}

/* Button-like links */
.teacher-menu li a {
    display: inline-block;
    width: 250px;
    text-align: center;
    padding: 15px 20px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Hover effects */
.teacher-menu li a:hover {
    background: linear-gradient(135deg, #27ae60, #1e8449);
    transform: translateY(-3px);
    box-shadow: 0 10px 18px rgba(0,0,0,0.25);
}
/* Image gallery layout */
.image-gallery {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin: 40px auto;
  max-width: 1000px;
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
    <h1>Welcome, Teacher <?php echo $_SESSION["username"]; ?></h1>
   
  <ul class="teacher-menu">
    <li><a href="admin_add_book.php">Add Books</a></li>
     <li><a href="e_books.php">Add E-Books</a></li>
    <li><a href="issue_books.php">Issue Books to Learners</a></li>
    <li><a rel="" href="inventory.php">Manage Inventory</a></li>
     <li><a rel="" href="lostbooks.php">Add Lost books</a></li>
       <li><a rel="" href="viewStudent.php">View Students</a></li>
</ul>

<div class="image-gallery">
  <div class="image-card">
    <img src="images/pw.jpg" alt="Library Image 1">
  </div>
  <div class="image-card">
    <img src="images/tr.jpg" alt="Library Image 2">
  </div>
  <div class="image-card">
    <img src="images/sh.jpg" alt="Library Image 3">
  </div>
  <div class="image-card">
    <img src="images/st.jpg" alt="Library Image 4">
  </div>
</div>


</body>
</html>
