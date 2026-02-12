<?php
session_start();

/* DEBUG (hapus kalau sudah normal) */
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

include __DIR__ . '/../partials/sidebar.php';

/* =====================
   TAMBAH BARANG
===================== */
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    if ($nama) {
        mysqli_query($conn, "INSERT INTO barang (nama_barang) VALUES ('$nama')");
        $tambah_success = "Barang '$nama' berhasil ditambahkan!";
    }
}

/* =====================
   UPDATE BARANG
===================== */
if (isset($_POST['update'])) {
    $id   = (int) $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    if ($nama) {
        mysqli_query($conn, "UPDATE barang SET nama_barang='$nama' WHERE id='$id'");
        $update_success = "Barang berhasil diperbarui!";
    }
}

/* =====================
   HAPUS BARANG
===================== */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
    $hapus_success = "Barang berhasil dihapus!";
}

/* =====================
   BULK DELETE
===================== */
if (isset($_POST['hapus_massal']) && !empty($_POST['hapus_ids'])) {

    $ids = array_map('intval', $_POST['hapus_ids']);
    $idList = implode(',', $ids);

    mysqli_query($conn, "DELETE FROM barang WHERE id IN ($idList)");

    $hapus_success = "Data terpilih berhasil dihapus!";
}

/* =====================
   IMPORT BARANG TANPA MENGHAPUS DATA LAMA
===================== */
if (isset($_POST['import']) && isset($_FILES['file']) && $_FILES['file']['error'] === 0) {

    $allowed = ['xlsx', 'xls', 'csv'];
    $fileName = $_FILES['file']['name'];
    $fileTmp  = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $import_error = "Format file tidak didukung!";
    } else {

        try {

            $spreadsheet = IOFactory::load($fileTmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            mysqli_begin_transaction($conn);

            foreach ($rows as $index => $row) {

                if ($index == 0) continue; // skip header

                $nama = trim($row[0] ?? '');

                if (!empty($nama)) {
                    $nama = mysqli_real_escape_string($conn, $nama);
                    mysqli_query($conn, "INSERT INTO barang (nama_barang) VALUES ('$nama')");
                }
            }

            mysqli_commit($conn);

            $import_success = "Data berhasil diimport dan ditambahkan ke data lama!";

        } catch (Exception $e) {

            mysqli_rollback($conn);
            $import_error = "Terjadi kesalahan saat import: " . $e->getMessage();
        }
    }
}

/* =====================
   SEARCH
===================== */
$search = '';
$where  = '';

if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where  = "WHERE nama_barang LIKE '%$search%'";
}

/* =====================
   PAGINATION
===================== */
$limit = 10;
$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;

$offset = ($page - 1) * $limit;

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM barang $where");
$totalRow   = mysqli_fetch_assoc($totalQuery);
$totalData  = $totalRow['total'];
$totalPage  = ceil($totalData / $limit);

if ($page > $totalPage && $totalPage > 0) {
    $page = $totalPage;
    $offset = ($page - 1) * $limit;
}

$data = mysqli_query(
    $conn,
    "SELECT * FROM barang
     $where
     ORDER BY id ASC
     LIMIT $limit OFFSET $offset"
);

/* =====================
   LOAD VIEW
===================== */
require __DIR__ . '/../views/barang_view.php';
