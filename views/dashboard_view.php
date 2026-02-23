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
            background-color: #f8f9fa;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .dashboard-title {
            font-weight: 600;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .card {
            border-radius: 15px;
        }

        .table thead {
            font-size: 14px;
        }

        .table tbody td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

<?php include 'partials/sidebar.php'; ?>

<div class="content">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="dashboard-title">
            <i class="bi bi-speedometer2"></i> Dashboard
        </h3>

    </div>

    <!-- MENU CARD -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5><i class="bi bi-box-seam"></i> Stok Gelang</h5>
                    <p class="text-muted small">
                        Input stok masuk & keluar gelang rumah sakit.
                    </p>
                    <a href="operator/stok.php" class="btn btn-primary btn-sm">
                        Kelola Stok
                    </a>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5><i class="bi bi-tags"></i> Data Barang</h5>
                    <p class="text-muted small">
                        Tambah, ubah, dan hapus barang gelang.
                    </p>
                    <a href="admin/barang.php" class="btn btn-success btn-sm">
                        Kelola Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5><i class="bi bi-people"></i> Manajemen User</h5>
                    <p class="text-muted small">
                        Kelola akun operator dan admin.
                    </p>
                    <a href="admin/users.php" class="btn btn-warning btn-sm">
                        Kelola User
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted">Total Stok</h6>
                    <h2 class="fw-bold"><?= $totalStok ?></h2>
                    <small class="text-muted">Seluruh barang</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted">Stok Masuk Hari Ini</h6>
                    <h2 class="fw-bold text-success"><?= $totalMasuk ?></h2>
                    <small class="text-muted"><?= date('d M Y') ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted">Stok Keluar Hari Ini</h6>
                    <h2 class="fw-bold text-danger"><?= $totalKeluar ?></h2>
                    <small class="text-muted"><?= date('d M Y') ?></small>
                </div>
            </div>
        </div>

    </div>

    <!-- AKTIVITAS STOK -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3">
                <i class="bi bi-clock-history"></i> Aktivitas Stok Terbaru
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($a = mysqli_fetch_assoc($qAktivitas)): ?>
                        <tr>
                            <td><?= $a['tanggal'] ?></td>
                            <td><?= $a['nama_barang'] ?></td>
                            <td>
                                <?php if($a['masuk'] > 0): ?>
                                    <span class="badge bg-success">
                                        +<?= $a['masuk'] ?>
                                    </span>
                                <?php endif; ?>

                                <?php if($a['keluar'] > 0): ?>
                                    <span class="badge bg-danger">
                                        -<?= $a['keluar'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- AKTIVITAS BARANG -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3">
                <i class="bi bi-box"></i> Aktivitas Data Barang
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($b = mysqli_fetch_assoc($qBarangAktivitas)): ?>
                        <tr>
                            <td><?= $b['tanggal'] ?></td>
                            <td><?= $b['nama_barang'] ?></td>
                            <td>
                                <span class="badge bg-info">
                                    Barang mulai digunakan
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>