<?php
session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

/* ==============================
   SIMPAN STOK
============================== */
if (isset($_POST['simpan'])) {

    $barang_id = $_POST['barang'];
    $masuk  = (int) $_POST['masuk'];
    $keluar = (int) $_POST['keluar'];

    // Ambil stok terakhir barang
    $q = mysqli_query($conn, "
        SELECT stok_akhir 
        FROM stok 
        WHERE barang_id='$barang_id' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    $d = mysqli_fetch_assoc($q);
    $stok_awal = $d ? $d['stok_akhir'] : 0;

    $stok_akhir = $stok_awal + $masuk - $keluar;

    if ($stok_akhir < 0) {

        $error = "Stok tidak boleh minus!";

    } else {

        mysqli_query($conn, "
            INSERT INTO stok 
            (tanggal, barang_id, stok_awal, masuk, keluar, stok_akhir, user_id)
            VALUES 
            (CURDATE(), '$barang_id', '$stok_awal', '$masuk', '$keluar', '$stok_akhir', '{$_SESSION['user_id']}')
        ");

        $success = "Data stok berhasil disimpan";
    }
}

/* ==============================
   FILTER SEARCH TANGGAL
============================== */
$where = "";

if (!empty($_GET['tanggal'])) {

    $tgl = mysqli_real_escape_string($conn, $_GET['tanggal']);
    $where = "WHERE s.tanggal = '$tgl'";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Input Stok Gelang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .sidebar-wrapper {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #fff;
            border-right: 1px solid #e5e5e5;
        }

        .content-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
    </style>
</head>

<body class="bg-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar-wrapper">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    </div>

    <!-- CONTENT -->
    <div class="content-wrapper">

        <div class="container mt-4">

            <h3 class="mb-4">Input Stok Gelang Rumah Sakit</h3>

            <!-- ALERT -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <!-- =========================
                 FORM INPUT STOK
            ========================== -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <h5 class="mb-3">Tambah Transaksi Stok</h5>

                    <form method="post" class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Barang</label>
                            <select name="barang" class="form-select" required>
                                <?php
                                $barang = mysqli_query($conn, "SELECT * FROM barang");
                                while ($b = mysqli_fetch_assoc($barang)) {
                                    echo "<option value='{$b['id']}'>{$b['nama_barang']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Stok Masuk</label>
                            <input type="number" name="masuk" class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Stok Keluar</label>
                            <input type="number" name="keluar" class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-2 d-grid align-items-end">
                            <button type="submit" name="simpan" class="btn btn-success">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- EXPORT -->
            <a href="../auth/export_stok.php" class="btn btn-success mb-3">
                Export Riwayat Stok CSV
            </a>

            <!-- =========================
                 TABEL + SEARCH
            ========================== -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- HEADER + SEARCH -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                        <h5 class="mb-0">Riwayat Stok</h5>

                        <form method="get" class="d-flex gap-2">

                            <input type="date"
                                   name="tanggal"
                                   value="<?= $_GET['tanggal'] ?? '' ?>"
                                   class="form-control form-control-sm">

                            <button class="btn btn-primary btn-sm">
                                Cari
                            </button>

                            <a href="?" class="btn btn-secondary btn-sm">
                                Reset
                            </a>

                        </form>

                    </div>

                    <!-- TABEL -->
                    <table class="table table-bordered table-hover table-sm">

                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Stok Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stok Akhir</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        $data = mysqli_query($conn, "
                            SELECT s.*, b.nama_barang 
                            FROM stok s
                            JOIN barang b ON s.barang_id = b.id
                            $where
                            ORDER BY s.id DESC
                        ");

                        while ($d = mysqli_fetch_assoc($data)):
                        ?>

                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td><?= $d['tanggal']; ?></td>
                            <td><?= $d['nama_barang']; ?></td>
                            <td><?= $d['stok_awal']; ?></td>
                            <td><?= $d['masuk']; ?></td>
                            <td><?= $d['keluar']; ?></td>
                            <td><strong><?= $d['stok_akhir']; ?></strong></td>
                        </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>
