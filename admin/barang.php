<?php
session_start();
require_once __DIR__ . '/../config/database.php';

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
   AMBIL DATA BARANG
===================== */
$data = mysqli_query($conn, "SELECT * FROM barang ORDER BY id ASC");

/* =====================
   LOAD VIEW
===================== */
require __DIR__ . '/../views/barang_view.php';
