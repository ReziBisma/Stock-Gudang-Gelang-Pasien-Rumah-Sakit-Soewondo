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


    <!-- EXPORT -->
<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h5>Export Laporan PDF</h5>

        <form method="get" action="../auth/export_stok.php" target="_blank" class="row g-3">

            <div class="col-md-3">
                <label>Dari Tanggal</label>
                <input type="date" name="tgl_awal" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Jenis Gelang</label>
                <select name="barang" class="form-select">
                    <option value="">Semua</option>
                    <?php
                    $barang = mysqli_query($conn, "SELECT * FROM barang");
                    while($b = mysqli_fetch_assoc($barang)){
                        echo "<option value='{$b['id']}'>{$b['nama_barang']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3 d-grid">
                <label>&nbsp;</label>
                <button class="btn btn-danger">
                    Export PDF
                </button>
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
                            <form method="post" onsubmit="return confirm('Yakin hapus data ini?')">
                                <input type="hidden" name="hapus_id" value="<?= $d['id'] ?>">
                                <button type="submit" name="hapus_stok" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                            <form method="get" action="../auth/stok_generate_token.php" target="_blank" class="mt-1">
                            <input type="hidden" name="stok_id" value="<?= $d['id'] ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Generate Token</button>
                            </form>

                        <?php endif; ?>

                        <?php if ($_SESSION['role'] === 'operator'): ?>
                            <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal<?= $d['id'] ?>">
                                Hapus
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- MODAL UNTUK OPERATOR MINTA APPROVAL ADMIN -->
                <?php if ($_SESSION['role'] === 'operator'): ?>
                <div class="modal fade" id="hapusModal<?= $d['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Token Approval</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p class="text-danger">Masukkan token yang diberikan admin</p>
                                <input type="text" name="approve_token" class="form-control" required>
                                <input type="hidden" name="hapus_id" value="<?= $d['id'] ?>">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="hapus_stok" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>



                <?php endwhile; ?>

                
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>
