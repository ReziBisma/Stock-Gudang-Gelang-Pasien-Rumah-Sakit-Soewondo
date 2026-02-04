<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek login & role admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

// Header CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=barang.csv');

// Buat file output
$output = fopen('php://output', 'w');

// Tulis header CSV
fputcsv($output, ['ID', 'Nama Barang']);

// Ambil data barang
$result = mysqli_query($conn, "SELECT id, nama_barang FROM barang ORDER BY id ASC");

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$row['id'], $row['nama_barang']]);
}

fclose($output);
exit;
