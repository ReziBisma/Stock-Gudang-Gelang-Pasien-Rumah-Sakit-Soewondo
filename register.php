<?php

session_start();

// kalau sudah login, langsung dashboard
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

// logic register
require_once __DIR__ . '/auth/register_process.php';

// view
require_once __DIR__ . '/views/register_view.php';
