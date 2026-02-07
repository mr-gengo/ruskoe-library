<?php
// Path to the E-books folder
$dir = "E-books/";

// Get all files in the folder
$files = array_diff(scandir($dir), array('.', '..'));

// Handle search query
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library - E-books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in-out;
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            padding: 20px;
            text-align: center;
            animation: slideUp 0.8s ease-out;
        }

        .book-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #444;
        }

        .book-actions a {
            display: inline-block;
            margin: 8px;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .view-btn {
            background: #74ebd5;
            color: #fff;
        }

        .download-btn {
            background: #ACB6E5;
            color: #fff;
        }

        .book-actions a:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        /* Rating stars */
        .stars {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .star {
            font-size: 1.5em;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star:hover,
        .star.active {
            color: gold;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-form input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 2px solid #007BFF;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background: #007BFF;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-form input[type="submit"]:hover {
            background: #0056b3;
        }

        .no-results {
            text-align: center;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>📚 Learners' E-book Library</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="get">
            <input type="text" name="search" placeholder="Search by book title..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <!-- Book List -->
    <div class="book-list">
        <?php
        $found = false;
        foreach ($files as $file) {
            if ($search === '' || strpos(strtolower($file), $search) !== false) {
                $found = true;
                ?>
                <div class="book-card">
                    <div class="book-title"><?php echo htmlspecialchars($file); ?></div>
                    <div class="book-actions">
                        <a class="view-btn" href="<?php echo $dir . $file; ?>" target="_blank">View</a>
                        <a class="download-btn" href="<?php echo $dir . $file; ?>" download>Download</a>
                    </div>
                    <div class="stars" data-file="<?php echo htmlspecialchars($file); ?>">
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                        <span class="star">&#9733;</span>
                    </div>
                </div>
                <?php
            }
        }
        if (!$found) {
            echo "<div class='no-results'>No books found.</div>";
        }
        ?>
    </div>

    <script>
        // Star rating system
        document.querySelectorAll('.stars').forEach(starContainer => {
            const stars = starContainer.querySelectorAll('.star');
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('active', i <= index);
                    });
                    const bookName = starContainer.getAttribute('data-file');
                    alert("You rated " + bookName + " with " + (index+1) + " stars!");
                });
            });
        });
    </script>
</body>
</html>
