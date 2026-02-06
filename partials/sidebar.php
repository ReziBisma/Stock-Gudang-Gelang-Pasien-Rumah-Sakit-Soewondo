<?php
$current_page = basename($_SERVER['PHP_SELF']);

$isAdmin    = ($_SESSION['role'] ?? '') === 'admin';
$isOperator = ($_SESSION['role'] ?? '') === 'operator';
?>

<style>
/* warna aktif menu jadi hijau */
.nav-pills .nav-link.active {
    background-color: #198754 !important;
}

.nav-link {
    color: #198754;
}

.nav-link:hover {
    background-color: #d1e7dd;
}
</style>

<div class="d-flex flex-column vh-100 bg-white shadow-sm" style="width:260px; position:fixed;">
    
    <!-- HEADER -->
    <div class="bg-success text-white text-center py-4">
        <h5 class="fw-bold mb-0">SISTEM STOK</h5>
        <small>Rumah Sakit</small>
    </div>

    <!-- MENU -->
    <div class="p-3 flex-grow-1">
        <ul class="nav nav-pills flex-column gap-2">

            <!-- DASHBOARD -->
            <li class="nav-item">
                <a href="/dashboard.php"
                   class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                    🏠 Dashboard
                </a>
            </li>

            <!-- STOK -->
            <?php if ($isAdmin || $isOperator): ?>
            <li class="nav-item">
                <a href="/operator/stok.php"
                   class="nav-link <?= ($current_page == 'stok.php') ? 'active' : '' ?>">
                    📦 Manajemen Stok
                </a>
            </li>
            <?php endif; ?>

            <!-- ADMIN MENU -->
            <?php if ($isAdmin): ?>
            <li class="nav-item">
                <a href="/admin/barang.php"
                   class="nav-link <?= ($current_page == 'barang.php') ? 'active' : '' ?>">
                    🧾 Data Barang
                </a>
            </li>

            <li class="nav-item">
                <a href="/admin/users.php"
                   class="nav-link <?= ($current_page == 'users.php') ? 'active' : '' ?>">
                    👤 Data User
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>

    <!-- FOOTER -->
    <div class="p-3 border-top text-center">
        <small class="text-muted">Login sebagai</small><br>
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
        <div class="d-grid mt-2">
            <a href="/logout.php" class="btn btn-success btn-sm">Logout</a>
        </div>
    </div>
</div>
