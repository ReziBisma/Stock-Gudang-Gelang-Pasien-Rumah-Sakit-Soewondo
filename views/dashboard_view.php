<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Stok Gelang RS</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar-fixed {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
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

<!-- SIDEBAR -->
<?php include 'partials/sidebar.php'; ?>

<!-- KONTEN -->
<div class="content">

    <h3 class="mb-3">Dashboard</h3>

    <div class="alert alert-info">
        Login sebagai <b><?= htmlspecialchars($_SESSION['role']); ?></b>
    </div>

    <div class="row">

        <!-- CARD STOK -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-box-seam"></i> Stok Gelang
                    </h5>
                    <p class="card-text">
                        Input stok masuk & keluar gelang rumah sakit.
                    </p>
                    <a href="operator/stok.php" class="btn btn-primary btn-sm">
                        Kelola Stok
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD ADMIN -->
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-tags"></i> Data Barang
                        </h5>
                        <p class="card-text">
                            Tambah, ubah, dan hapus barang gelang.
                        </p>
                        <a href="admin/barang.php" class="btn btn-success btn-sm">
                            Kelola Barang
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-people"></i> Manajemen User
                        </h5>
                        <p class="card-text">
                            Kelola akun operator dan admin.
                        </p>
                        <a href="admin/users.php" class="btn btn-warning btn-sm">
                            Kelola User
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <h6>Total Stok</h6>
                <h3><?= $totalStok ?></h3>
                <small class="text-muted">Seluruh barang</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-start border-success border-4">
            <div class="card-body">
                <h6>Stok Masuk Hari Ini</h6>
                <h3><?= $totalMasuk ?></h3>
                <small class="text-muted"><?= date('d M Y') ?></small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-start border-danger border-4">
            <div class="card-body">
                <h6>Stok Keluar Hari Ini</h6>
                <h3><?= $totalKeluar ?></h3>
                <small class="text-muted"><?= date('d M Y') ?></small>
            </div>
        </div>
    </div>

</div>


    </div>

</div>

</body>
</html>
