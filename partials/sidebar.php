<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$isAdmin    = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isOperator = isset($_SESSION['role']) && $_SESSION['role'] === 'operator';
?>

<div class="d-flex flex-column vh-100 bg-white shadow-sm" style="width:260px; position:fixed;">
    
    <!-- HEADER -->
    <div class="bg-primary text-white text-center py-4">
        <h5 class="fw-bold mb-0">SISTEM STOK</h5>
        <small>Rumah Sakit</small>
    </div>

    <!-- MENU -->
    <div class="p-3 flex-grow-1">
        <ul class="nav nav-pills flex-column gap-2">

            <!-- DASHBOARD -->
            <li class="nav-item">
                <a href="<?= $base_url ?>/dashboard.php"
                   class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                    🏠 Dashboard
                </a>
            </li>

            <!-- STOK (ADMIN & OPERATOR) -->
            <?php if ($isAdmin || $isOperator): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>/operator/stok.php"
                   class="nav-link <?= ($current_page == 'stok.php') ? 'active' : '' ?>">
                    📦 Manajemen Stok
                </a>
            </li>
            <?php endif; ?>

            <!-- BARANG (ADMIN ONLY) -->
            <?php if ($isAdmin): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>/admin/barang.php"
                   class="nav-link <?= ($current_page == 'barang.php') ? 'active' : '' ?>">
                    🧾 Data Barang
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
            <a href="<?= $base_url ?>/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</div>
