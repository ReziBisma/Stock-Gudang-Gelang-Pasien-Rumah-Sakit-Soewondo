<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { overflow-x: hidden; }
        .content { margin-left: 250px; padding: 20px; }
    </style>
</head>

<body class="bg-light">

<div class="content">

    <h3 class="mb-4">Kelola Data Barang</h3>

    <!-- ALERT -->
    <?php if(!empty($tambah_success)): ?>
        <div class="alert alert-success"><?= $tambah_success; ?></div>
    <?php endif; ?>
    <?php if(!empty($update_success)): ?>
        <div class="alert alert-success"><?= $update_success; ?></div>
    <?php endif; ?>
    <?php if(!empty($hapus_success)): ?>
        <div class="alert alert-success"><?= $hapus_success; ?></div>
    <?php endif; ?>

    <!-- TAMBAH BARANG -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Tambah Barang Baru</h5>
            <form method="post" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button name="tambah" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EXPORT -->
    <div class="mb-4">
        <a href="../auth/export_barang.php" class="btn btn-success" target="_blank">
            Export CSV
        </a>
    </div>

    <!-- TABEL -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Daftar Barang</h5>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="60">No</th>
                        <th>Nama Barang</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $no = $offset + 1;
                while ($d = mysqli_fetch_assoc($data)):
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($d['nama_barang']); ?></td>
                        <td class="text-center">

                            <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $d['id']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <a href="?hapus=<?= $d['id']; ?>&page=<?= $page; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus barang ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="editModal<?= $d['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= $d['id']; ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="nama"
                                               class="form-control"
                                               value="<?= htmlspecialchars($d['nama_barang']); ?>" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button name="update" class="btn btn-primary btn-sm">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <?php if ($totalPage > 1): ?>
            <hr>
            <nav class="d-flex justify-content-center">
                <ul class="pagination">

                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>">&laquo;</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>">&raquo;</a>
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