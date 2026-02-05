<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { overflow-x: hidden; }
        .sidebar-wrapper {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #ddd;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body class="bg-light">

<div class="sidebar-wrapper">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
</div>

<div class="content-wrapper">

    <h3 class="mb-4">Manajemen Stok Gelang</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <!-- FORM INPUT -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Tambah Transaksi Stok</h5>

            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Barang</label>
                    <select name="barang" class="form-select" required>
                        <?php
                        $barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
                        while ($b = mysqli_fetch_assoc($barang)):
                        ?>
                            <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Masuk</label>
                    <input type="number" name="masuk" value="0" min="0" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Keluar</label>
                    <input type="number" name="keluar" value="0" min="0" class="form-control">
                </div>

                <div class="col-md-2 d-grid align-items-end">
                    <button name="simpan" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <h5>Riwayat Stok</h5>

                <form method="get" class="d-flex gap-2">
                    <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>" class="form-control form-control-sm">
                    <button class="btn btn-primary btn-sm">Cari</button>
                    <a href="?" class="btn btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <table class="table table-bordered table-hover table-sm">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = $offset + 1;
                while ($d = mysqli_fetch_assoc($data)):
                ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= $d['tanggal'] ?></td>
                        <td><?= $d['nama_barang'] ?></td>
                        <td><?= $d['stok_awal'] ?></td>
                        <td><?= $d['masuk'] ?></td>
                        <td><?= $d['keluar'] ?></td>
                        <td><strong><?= $d['stok_akhir'] ?></strong></td>
                        <td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="?hapus=<?= $d['id'] ?>&page=<?= $page ?><?= $params ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                Hapus
                                </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endwhile; ?>

                <?php if ($totalData == 0): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Tidak ada data
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <?php if ($totalPage > 1): ?>
            <nav class="d-flex justify-content-center">
                <ul class="pagination">

                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?><?= $params ?>">&laquo;</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $params ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?><?= $params ?>">&raquo;</a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
