<?php

if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'teacher') {
        header("Location: teacher_dashboard.php");
    } elseif ($user['role'] === 'student') {
        header("Location: student_dashboard.php");
    } elseif ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    }
    exit;
}

?>