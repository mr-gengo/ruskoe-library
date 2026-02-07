<?php
// Folder where e-books will be stored
$targetDir = "E-books/";

// Ensure the folder exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileName = basename($_FILES["ebook"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Allowed file types
    $allowedTypes = array('pdf', 'doc', 'docx');
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if (in_array($fileType, $allowedTypes)) {
     if (move_uploaded_file($_FILES["ebook"]["tmp_name"], $targetFilePath)) {
    echo "<div class='msg success'>✅ The e-book <b>" . htmlspecialchars($fileName) . "</b> has been uploaded successfully.</div>";
} else {
    echo "<div class='msg error'>❌ Sorry, there was an error uploading your file.</div>";
}
} else {
    echo "<div class='msg warning'>⚠️ Only PDF, DOC, and DOCX files are allowed.</div>";
}

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>E-books</title>
    <style> 
    /* Base message styling */
.msg {
    margin: 20px auto;
    padding: 15px 25px;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: bold;
    text-align: center;
    width: fit-content;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    animation: fadeIn 0.8s ease-out;
}

/* Success message */
.msg.success {
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
    color: #fff;
}

/* Error message */
.msg.error {
    background: linear-gradient(135deg, #ff6a6a, #ff3c3c);
    color: #fff;
}

/* Warning message */
.msg.warning {
    background: linear-gradient(135deg, #f9d423, #ff4e50);
    color: #333;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}

        /* General page styling */
        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('images/tb.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

        /* Upload form container */
        form {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Heading */
        form::before {
            content: "📚 Upload E-book";
            display: block;
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        /* File input styling */
        input[type="file"] {
            display: block;
            margin: 20px auto;
            padding: 10px;
            border: 2px dashed #74ebd5;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        input[type="file"]:hover {
            border-color: #ACB6E5;
            background: #eef6ff;
        }

        /* Upload button */
        input[type="submit"] {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="submit"]:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

         /* Success message styling */
        .success-message {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            margin: 20px auto;
            width: fit-content;
            animation: slideIn 0.8s ease-out;
        }

        /* Animation for message */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Button styling */
        .action-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #fff;
            color: #333;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #f0f0f0;
            transform: scale(1.05);
        }
/* Layout: form + book list side by side */
.layout {
  display: flex;
  gap: 40px;
  align-items: flex-start;
}

/* Book list container */
.book-list {
  width: 400px;
  height: 250px;
  overflow: hidden;
  background: rgba(241, 207, 9, 0.8);
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  padding: 15px;
}

/* Scrolling animation */
.book-list ul {
  list-style: none;
  margin: 0;
  padding: 0;
  animation: scrollBooks 12s linear infinite;
}

.book-list li {
  font-size: 1em;
  margin: 10px 0;
  color: #333;
}

/* Keyframes for scrolling */
@keyframes scrollBooks {
  0%   { transform: translateY(0); }
  100% { transform: translateY(-100%); }
}


        
    </style>
</head>
<body>
    
    <div class="layout">
  <!-- Upload form -->
  <form action="" method="post" enctype="multipart/form-data">
      Select e-book to upload:
      <input type="file" name="ebook" required>
      <input type="submit" value="Upload E-book">
  </form>

  <!-- Scrolling book list -->
  <div class="book-list">
    <ul>
      <li>📖 Computer Science book 1</li>
      <li>📖Computer Science book 2</li>
      <li>📖 Computer Science book 3</li>
      <li>📖 Computer Science book 4</li>
      <li>📖 Kapondeni</li>
      <li>📖 Master Computer Science</li>
      <li>📖 Introduction to Visual BAsic</li>
      <li>📖 WEB Designing</li>
      <li>📖 W3Schools</li>
      <li>📖 Database</li>
      <li>📖 Understand Data Representation</li>
    </ul>
  </div>
</div>


   
</body>
</html>




  

    


