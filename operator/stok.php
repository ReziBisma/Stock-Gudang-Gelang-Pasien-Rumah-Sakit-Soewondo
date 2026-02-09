<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

/* ==============================
   SIMPAN STOK
============================== */
if (isset($_POST['simpan'])) {

    $barang_id = (int) $_POST['barang'];
    $masuk  = (int) $_POST['masuk'];
    $keluar = (int) $_POST['keluar'];

    $q = mysqli_query($conn, "
        SELECT stok_akhir 
        FROM stok 
        WHERE barang_id='$barang_id' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    $d = mysqli_fetch_assoc($q);
    $stok_awal = $d ? $d['stok_akhir'] : 0;
    $stok_akhir = $stok_awal + $masuk - $keluar;

    if ($stok_akhir < 0) {
        $error = "Stok tidak boleh minus!";
    } else {
        mysqli_query($conn, "
            INSERT INTO stok
            (tanggal, barang_id, stok_awal, masuk, keluar, stok_akhir, user_id)
            VALUES
            (CURDATE(), '$barang_id', '$stok_awal', '$masuk', '$keluar', '$stok_akhir', '{$_SESSION['user_id']}')
        ");
        $success = "Data stok berhasil disimpan";
    }
}

/* ==============================
   HAPUS STOK DENGAN VERIFIKASI
============================== */
if (isset($_POST['hapus_stok'])) {
    if (!in_array($_SESSION['role'], ['admin', 'operator'])) {
        die("Akses ditolak");
    }

    $id = (int) $_POST['hapus_id'];

    // ================= ADMIN =================
    if ($_SESSION['role'] === 'admin') {
        mysqli_query($conn, "DELETE FROM stok WHERE id = $id");
        $success = "Data berhasil dihapus.";
    }

    // ================= OPERATOR =================
    else {

        $token     = $_POST['token'];
        $stok_id   = (int) $_POST['hapus_id'];

        // Ambil token dari session admin
        if (!isset($_SESSION['stok_token'][$stok_id]) || $_SESSION['stok_token'][$stok_id] != $token) {
            $error = "Token salah! Data gagal dihapus.";
        } else {
            mysqli_query($conn, "DELETE FROM stok WHERE id = $stok_id");
            unset($_SESSION['stok_token'][$stok_id]); // hapus token setelah dipakai
            $success = "Data berhasil dihapus.";
        }
    }

}



/* ==============================
   FILTER TANGGAL
============================== */
$where = "";
$params = "";

if (!empty($_GET['tanggal'])) {
    $tgl = mysqli_real_escape_string($conn, $_GET['tanggal']);
    $where = "WHERE s.tanggal = '$tgl'";
    $params = "&tanggal=$tgl";
}

/* ==============================
   PAGINATION (FIXED)
============================== */
$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page   = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $limit;

/* TOTAL DATA STOK (PAKE WHERE) */
$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok s
    $where
");
$totalRow  = mysqli_fetch_assoc($totalQuery);
$totalData = $totalRow['total'];
$totalPage = ceil($totalData / $limit);

/* DATA STOK PER HALAMAN */
$data = mysqli_query($conn, "
    SELECT s.*, b.nama_barang
    FROM stok s
    JOIN barang b ON s.barang_id = b.id
    $where
    ORDER BY s.id DESC
    LIMIT $limit OFFSET $offset
");

require __DIR__ . '/../views/stok_view.php';