<?php

$conn = mysqli_connect("localhost", "stokuser", "stok123", "stok_gelangrs");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

