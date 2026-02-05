<?php

session_start();
require_once 'config/database.php';


if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

require __DIR__ . '/views/dashboard_view.php';