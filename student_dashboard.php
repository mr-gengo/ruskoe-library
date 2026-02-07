<?php
session_start();

// Restrict access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        /* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Page background */
body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/bg2.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding-top: 80px;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

/* Heading */
h1 {
    position: fixed;       /* keeps it stuck at the top */
    top: 0;                /* aligns to the very top */
    left: 0;               /* spans from the left edge */
    width: 100%;           /* full width */
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: #fff;
    margin: 0;
    font-size: 1.8rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 1000;         /* ensures it stays above other elements */
}


/* Centered menu */
.student-menu {
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80vh; /* centers vertically */
    gap: 20px; /* spacing between buttons */
}

/* Button-like links */
.student-menu li a {
    display: inline-block;
    width: 260px;
    text-align: center;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Hover effects */
.student-menu li a:hover {
    background: linear-gradient(135deg, #e67e22, #d35400);
    transform: translateY(-3px);
    box-shadow: 0 10px 18px rgba(0,0,0,0.25);
}
</style>
</head>
<body>
    <h1>Welcome, Our Hardworking Student <?php echo $_SESSION["username"]; ?></h1>
    <nav>
        <ul class="student-menu">
              <li><a href="viewE_books.php">E-books</a></li>
            <li><a href="view_books.php">View Available Books</a></li>
            <li><a href="my_books.php">My Borrowed Books</a></li>
            <li><a href="borrow.php">Borrow new book</a></li>
            <li><a href="penalties.php">View Penalties</a></li>
        </ul>
    </nav>
</body>

</html>
