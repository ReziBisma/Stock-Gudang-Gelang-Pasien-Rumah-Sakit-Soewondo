<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// cek login
if (!isset($_SESSION['login'])) {
    die("Akses ditolak");
}

// header CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="stok.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
fputcsv($output, ['Tanggal', 'Barang', 'Stok Awal', 'Masuk', 'Keluar', 'Stok Akhir']); // header CSV

$result = mysqli_query($conn, "
    SELECT s.tanggal, b.nama_barang, s.stok_awal, s.masuk, s.keluar, s.stok_akhir
    FROM stok s
    JOIN barang b ON s.barang_id = b.id
    ORDER BY s.id ASC
");

while($row = mysqli_fetch_assoc($result)){
    fputcsv($output, $row);
}

fclose($output);
exit;
