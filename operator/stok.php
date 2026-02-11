<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    $stok_awal  = $d ? $d['stok_akhir'] : 0;
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
   IMPORT EXCEL (PAKAI NAMA BARANG)
============================== */
if (isset($_POST['import_excel']) && $_SESSION['role'] === 'admin') {

    if (!empty($_FILES['excel']['tmp_name'])) {

        $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        unset($rows[0]); // hapus header

        foreach ($rows as $row) {

            $tanggal      = mysqli_real_escape_string($conn, $row[0]);
            $nama_barang  = mysqli_real_escape_string($conn, $row[1]);
            $masuk        = (int)$row[2];
            $keluar       = (int)$row[3];

            // cari barang_id dari nama
            $barangQ = mysqli_query($conn, "
                SELECT id FROM barang
                WHERE nama_barang = '$nama_barang'
                LIMIT 1
            ");

            if (mysqli_num_rows($barangQ) === 0) {
                continue; // skip jika barang tidak ditemukan
            }

            $barang = mysqli_fetch_assoc($barangQ);
            $barang_id = $barang['id'];

            // ambil stok terakhir
            $q = mysqli_query($conn, "
                SELECT stok_akhir 
                FROM stok 
                WHERE barang_id='$barang_id'
                ORDER BY id DESC
                LIMIT 1
            ");

            $d = mysqli_fetch_assoc($q);
            $stok_awal  = $d ? $d['stok_akhir'] : 0;
            $stok_akhir = $stok_awal + $masuk - $keluar;

            if ($stok_akhir < 0) continue;

            mysqli_query($conn, "
                INSERT INTO stok
                (tanggal, barang_id, stok_awal, masuk, keluar, stok_akhir, user_id)
                VALUES
                ('$tanggal', '$barang_id', '$stok_awal', '$masuk', '$keluar', '$stok_akhir', '{$_SESSION['user_id']}')
            ");
        }

        $success = "Import Excel berhasil (pakai nama barang)!";
    }
}



/* ==============================
   HAPUS STOK (ADMIN / OPERATOR)
============================== */
if (isset($_POST['hapus_stok'])) {

    if (!in_array($_SESSION['role'], ['admin', 'operator'])) {
        die("Akses ditolak");
    }

    $stok_id = (int) $_POST['hapus_id'];

    // ========= ADMIN =========
    if ($_SESSION['role'] === 'admin') {

        mysqli_query($conn, "DELETE FROM stok WHERE id = $stok_id");

        $success = "Data berhasil dihapus.";
    }

    // ========= OPERATOR =========
    elseif ($_SESSION['role'] === 'operator') {

        $approve_token = mysqli_real_escape_string(
            $conn,
            $_POST['approve_token'] ?? ''
        );

        $q = mysqli_query($conn, "
            SELECT id 
            FROM otp 
            WHERE stok_id = $stok_id 
            AND kode = '$approve_token'
            LIMIT 1
        ");

        if (mysqli_num_rows($q) === 0) {

            $error = "Token salah atau sudah dipakai!";

        } else {

            mysqli_query($conn, "DELETE FROM stok WHERE id = $stok_id");

            $otp = mysqli_fetch_assoc($q);
            mysqli_query($conn, "DELETE FROM otp WHERE id = {$otp['id']}");

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
   PAGINATION
============================== */
$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page   = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $limit;

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok s
    $where
");

$totalRow  = mysqli_fetch_assoc($totalQuery);
$totalData = $totalRow['total'];
$totalPage = ceil($totalData / $limit);

$data = mysqli_query($conn, "
    SELECT s.*, b.nama_barang
    FROM stok s
    JOIN barang b ON s.barang_id = b.id
    $where
    ORDER BY s.id DESC
    LIMIT $limit OFFSET $offset
");


require __DIR__ . '/../views/stok_view.php';
