<?php

require_once __DIR__ . '/../config/database.php';

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // lebih aman pakai prepared statement
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['login']    = true;
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
