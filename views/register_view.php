<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Stok Gelang RS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="text-center mb-3 fw-bold">
                        Register Staff RS
                    </h4>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success text-center">
                            <?= htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                        <input type="password" name="confirm" class="form-control mb-3" placeholder="Konfirmasi Password" required>

                        <button name="register" class="btn btn-success w-100">
                            Register
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/index.php">Sudah punya akun? Login</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
