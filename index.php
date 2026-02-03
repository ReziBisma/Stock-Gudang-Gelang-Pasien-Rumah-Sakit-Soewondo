<?php
require_once 'config/database.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login']   = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']    = $user['role']; // admin / operator

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Stok Gelang RS</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="text-center mb-3 fw-bold">
                        Login Staff RS
                    </h4>
                    <p class="text-center text-muted mb-4">
                        Sistem Manajemen Stok Gelang
                    </p>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username"
                                   class="form-control"
                                   placeholder="Masukkan username"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control"
                                   placeholder="Masukkan password"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button name="login" class="btn btn-primary">
                                Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted mt-3">
                © <?= date('Y'); ?> Rumah Sakit
            </p>

        </div>
    </div>
</div>

</body>
</html>
