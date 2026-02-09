<?php

session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

// Ambil stok_id dari GET
$stok_id = isset($_GET['stok_id']) ? (int) $_GET['stok_id'] : 0;

if ($stok_id <= 0) {
    die("Data tidak valid");
}

// Generate token 6 digit
$token = random_int(100000, 999999);

// Simpan token di tabel otp
$expire_minutes = 5; // token berlaku 5 menit
$expired_at = date('Y-m-d H:i:s', strtotime("+$expire_minutes minutes"));

mysqli_query($conn, "
    INSERT INTO otp (stok_id, kode, user_id, created_at)
    VALUES ($stok_id, '$token', '{$_SESSION['user_id']}', NOW())
");


// Tampilkan token
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Token Hapus Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="card shadow-sm" style="max-width: 400px; margin: auto;">
        <div class="card-body text-center">
            <h5 class="card-title">Token Hapus Stok</h5>
            <p class="card-text">Token untuk stok ID <strong><?= $stok_id ?></strong></p>
            <h2 class="text-danger"><?= $token ?></h2>
            <p>Berikan token ini ke operator untuk menghapus stok.</p>
            <a href="javascript:window.close()" class="btn btn-primary">Tutup</a>
        </div>
    </div>
</body>
</html>
