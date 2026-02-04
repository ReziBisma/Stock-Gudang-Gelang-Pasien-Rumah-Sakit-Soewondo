<?php
session_start();

// jika sudah login, langsung ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

// include logic login
require_once __DIR__ . '/auth/login_process.php';

// include tampilan
require_once __DIR__ . '/views/login_view.php';
