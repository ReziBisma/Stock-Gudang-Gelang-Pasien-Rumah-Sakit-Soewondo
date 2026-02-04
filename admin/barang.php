<?php
session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

include __DIR__ . '/../partials/sidebar.php';

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    mysqli_query($conn, "INSERT INTO barang (nama_barang) VALUES ('$nama')");
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Barang</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar-fixed {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #fff;
            border-right: 1px solid #ddd;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body class="bg-light">



<!-- KONTEN -->
<div class="content">

    <h3 class="mb-4">Kelola Data Barang</h3>

    <!-- CARD INPUT -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Tambah Barang Baru</h5>

            <form method="post" class="row g-3">
                <div class="col-md-10">
                    <input type="text"
                           name="nama"
                           class="form-control"
                           placeholder="Nama Barang (Gelang Rumah Sakit)"
                           required>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" name="tambah" class="btn btn-success">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Daftar Barang</h5>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="60">No</th>
                        <th>Nama Barang</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT * FROM barang");
                    while ($d = mysqli_fetch_assoc($data)) {
                ?>

                <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($d['nama_barang']); ?></td>
                        <td class="text-center">
                            <a href="?hapus=<?= $d['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus barang ini?')">
                               <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
