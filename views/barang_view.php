<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Barang</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body class="bg-light">

<div class="content">

    <h3 class="mb-4">Kelola Data Barang</h3>

    <!-- ===================== -->
    <!-- ALERT -->
    <!-- ===================== -->

    <?php if (!empty($tambah_success)): ?>
        <div class="alert alert-success"><?= $tambah_success; ?></div>
    <?php endif; ?>

    <?php if (!empty($update_success)): ?>
        <div class="alert alert-success"><?= $update_success; ?></div>
    <?php endif; ?>

    <?php if (!empty($hapus_success)): ?>
        <div class="alert alert-success"><?= $hapus_success; ?></div>
    <?php endif; ?>

    <!-- ===================== -->
    <!-- TAMBAH BARANG -->
    <!-- ===================== -->

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3">Tambah Barang Baru</h5>

            <form method="post" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="col-md-2 d-grid">
                    <button name="tambah" class="btn btn-success">
                        Tambah
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ===================== -->
    <!-- EXPORT -->
    <!-- ===================== -->

    <div class="mb-4 d-flex gap-2">
        <a href="../auth/export_barang.php?format=pdf"
            class="btn btn-danger"
            target="_blank">
            Export PDF
        </a>
    </div>


    <!-- ===================== -->
    <!-- SEARCH -->
    <!-- ===================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" class="row g-2">

                <div class="col-md-10">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama barang..."
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>

                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <!-- ===================== -->
    <!-- BULK DELETE -->
    <!-- ===================== -->

    <form method="post" onsubmit="return confirm('Hapus semua data terpilih?')">

        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">Daftar Barang</h5>

                <table class="table table-bordered table-hover">

                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="40">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th width="60">No</th>
                            <th>Nama Barang</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $rows = [];
                    $no = 1;

                    while ($d = mysqli_fetch_assoc($data)):
                        $rows[] = $d;
                    ?>

                        <tr>

                            <td class="text-center">
                                <input type="checkbox"
                                       name="hapus_ids[]"
                                       value="<?= $d['id']; ?>"
                                       class="row-check">
                            </td>

                            <td class="text-center">
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($d['nama_barang']); ?>
                            </td>

                            <td class="text-center">

                                <!-- EDIT -->
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $d['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <!-- HAPUS -->
                                <a href="?hapus=<?= $d['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>
                </table>

                <div class="mt-3">
                    <button name="hapus_massal" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus Terpilih
                    </button>
                </div>

                <!-- ===================== -->
                <!-- PAGINATION -->
                <!-- ===================== -->

                <?php if ($totalPage > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">

                            <!-- Previous -->
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1&search=<?= urlencode($search); ?>">First</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>">Previous</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">First</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPage, $page + 2);

                            if ($startPage > 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?= $i; ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPage): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <!-- Next -->
                            <?php if ($page < $totalPage): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $totalPage; ?>&search=<?= urlencode($search); ?>">Last</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Last</span>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </nav>

                    <div class="text-center mt-2">
                        <small class="text-muted">Halaman <?= $page; ?> dari <?= $totalPage; ?> | Total data: <?= $totalData; ?></small>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </form>

    <!-- ===================== -->
    <!-- MODAL EDIT -->
    <!-- ===================== -->

    <?php foreach ($rows as $d): ?>

        <div class="modal fade"
             id="editModal<?= $d['id']; ?>"
             tabindex="-1">

            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <form method="post">

                        <input type="hidden" name="id" value="<?= $d['id']; ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Barang</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars($d['nama_barang']); ?>"
                                   required>
                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button name="update"
                                    class="btn btn-primary btn-sm">
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    <?php endforeach; ?>

</div>

<!-- ===================== -->
<!-- SCRIPT -->
<!-- ===================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("checkAll").addEventListener("click", function () {
    document.querySelectorAll(".row-check").forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

</body>
</html>