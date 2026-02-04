<?php
$host = "localhost";  // Host XAMPP (default: localhost)
$user = "stokuser";       // Username database (default: root)
$pass = "stok123";           // Password database (kosong secara default)
$db   = "stok_gelangrs";  // Nama database yang Anda buat

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>


