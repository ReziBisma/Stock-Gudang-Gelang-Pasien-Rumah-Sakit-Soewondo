<?php
$conn = mysqli_connect("localhost", "root", "", "stok_gelangrs");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
session_start();
