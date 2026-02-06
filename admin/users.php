<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

include __DIR__ . '/../partials/sidebar.php';

/* =====================
   TAMBAH USER
===================== */
if (isset($_POST['tambah'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    if ($username && $password) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "INSERT INTO users (username, password, role)
             VALUES ('$username', '$hash', '$role')"
        );

        $tambah_success = "User berhasil ditambahkan!";
    }
}

/* =====================
   UPDATE USER
===================== */
if (isset($_POST['update'])) {

    $id       = (int) $_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($_POST['password'])) {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE users
             SET username='$username',
                 password='$hash',
                 role='$role'
             WHERE id='$id'"
        );
    } else {

        mysqli_query($conn,
            "UPDATE users
             SET username='$username',
                 role='$role'
             WHERE id='$id'"
        );
    }

    $update_success = "User berhasil diperbarui!";
}

/* =====================
   HAPUS USER
===================== */
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    if ($id != $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
        $hapus_success = "User berhasil dihapus!";
    }
}

/* =====================
   BULK DELETE
===================== */
if (isset($_POST['hapus_massal']) && !empty($_POST['hapus_ids'])) {

    $ids = array_map('intval', $_POST['hapus_ids']);
    $ids = array_filter($ids, fn($i) => $i != $_SESSION['user_id']);

    if ($ids) {
        $idList = implode(',', $ids);
        mysqli_query($conn, "DELETE FROM users WHERE id IN ($idList)");
        $hapus_success = "User terpilih dihapus!";
    }
}

/* =====================
   SEARCH
===================== */
$search = '';
$where  = '';

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where  = "WHERE username LIKE '%$search%'";
}

/* =====================
   PAGINATION
===================== */
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);

$offset = ($page - 1) * $limit;

$totalQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM users $where"
);

$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

$data = mysqli_query($conn,
    "SELECT * FROM users
     $where
     ORDER BY id ASC
     LIMIT $limit OFFSET $offset"
);

/* =====================
   LOAD VIEW
===================== */
require __DIR__ . '/../views/users_view.php';
