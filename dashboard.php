<?php

session_start();
require_once 'config/database.php';


if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

/* ==============================
   DASHBOARD STATISTIK
============================== */

// total stok terakhir semua barang
$qTotalStok = mysqli_query($conn, "
    SELECT SUM(stok_akhir) AS total
    FROM (
        SELECT barang_id, MAX(id) id
        FROM stok
        GROUP BY barang_id
    ) last
    JOIN stok s ON s.id = last.id
");
$totalStok = mysqli_fetch_assoc($qTotalStok)['total'] ?? 0;


// total stok masuk hari ini
$qMasuk = mysqli_query($conn, "
    SELECT SUM(masuk) total
    FROM stok
    WHERE tanggal = CURDATE()
");
$totalMasuk = mysqli_fetch_assoc($qMasuk)['total'] ?? 0;


// total stok keluar hari ini
$qKeluar = mysqli_query($conn, "
    SELECT SUM(keluar) total
    FROM stok
    WHERE tanggal = CURDATE()
");
$totalKeluar = mysqli_fetch_assoc($qKeluar)['total'] ?? 0;

$qAktivitas = mysqli_query($conn, "
    SELECT s.tanggal, b.nama_barang, s.masuk, s.keluar
    FROM stok s
    JOIN barang b ON s.barang_id = b.id
    ORDER BY s.id DESC
    LIMIT 5
");

$qBarangAktivitas = mysqli_query($conn, "
    SELECT 
        s.tanggal,
        b.nama_barang,
        s.masuk,
        s.keluar
    FROM stok s
    JOIN barang b ON s.barang_id = b.id
    WHERE s.stok_awal = 0 AND s.masuk > 0
    ORDER BY s.id DESC
    LIMIT 5
");


require __DIR__ . '/views/dashboard_view.php';