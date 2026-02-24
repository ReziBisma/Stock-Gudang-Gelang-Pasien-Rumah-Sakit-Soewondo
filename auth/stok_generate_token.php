<?php

session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak'
    ]);
    exit;
}

$stok_id = isset($_GET['stok_id']) ? (int) $_GET['stok_id'] : 0;

if ($stok_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak valid'
    ]);
    exit;
}

$token = random_int(100000, 999999);

mysqli_query($conn, "
    INSERT INTO otp (stok_id, kode, user_id, created_at)
    VALUES ($stok_id, '$token', '{$_SESSION['user_id']}', NOW())
");

echo json_encode([
    'status' => 'success',
    'stok_id' => $stok_id,
    'token' => $token
]);
