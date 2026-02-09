<?php

session_start();
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // default role
    $role = 'operator';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn,
        "INSERT INTO users (username, password, role) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPassword, $role);

    if (mysqli_stmt_execute($stmt)) {

        $user_id = mysqli_insert_id($conn);

        // AUTO LOGIN
        $_SESSION['login']    = true;
        $_SESSION['user_id']  = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role']     = $role;

        header("Location: /dashboard.php");
        exit;

    } else {
        $error = "Registrasi gagal!";
    }
}

