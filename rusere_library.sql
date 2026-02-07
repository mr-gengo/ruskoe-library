CREATE DATABASE rusere_library;

USE rusere_library;

-- Users table (students/teachers/admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('student','teacher','admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Books table
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(25) NOT NULL,
    author VARCHAR(25) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    available BOOLEAN DEFAULT TRUE
);


-- issued books
CREATE TABLE issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    class VARCHAR(50) NOT NULL,
    book_title VARCHAR(200) NOT NULL,
    issue_date DATE NOT NULL,
    quantity INT NOT NULL DEFAULT 1
);


-- Login logs
CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    logout_time TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Students table
CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY,   -- e.g. RHS0_001
    student_name VARCHAR(20) NOT NULL,
    class VARCHAR(50) NOT NULL
);


-- Create lost_books table
CREATE TABLE lost_books (
    lost_id INT AUTO_INCREMENT PRIMARY KEY,   -- unique ID for each lost record
    student_id INT NOT NULL,                  -- student who lost the book
    title VARCHAR(255) NOT NULL,              -- book title
    author VARCHAR(255),                      -- book author
    year INT,                                 -- publication year
    charge_amount DECIMAL(10,2) NOT NULL,     -- fine/charge for the lost book
    date_lost DATE NOT NULL,                  -- when the book was reported lost
    status ENUM('pending','paid') DEFAULT 'pending', -- payment status
    issued_by VARCHAR(100) NOT NULL           -- teacher/admin who recorded the loss
);



