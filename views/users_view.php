<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
.content { margin-left:250px; padding:20px; }
</style>
</head>

<body class="bg-light">

<div class="content">

<h3 class="mb-4">Manajemen User</h3>

<?php foreach (['tambah_success','update_success','hapus_success'] as $msg): ?>
<?php if (!empty($$msg)): ?>
<div class="alert alert-success"><?= $$msg ?></div>
<?php endif; endforeach; ?>

<!-- TAMBAH USER -->
<div class="card mb-4 shadow-sm">
<div class="card-body">

<form method="post" class="row g-2">

<div class="col-md-3">
<input name="username" class="form-control" placeholder="Username" required>
</div>

<div class="col-md-3">
<input name="password" type="password" class="form-control" placeholder="Password" required>
</div>

<div class="col-md-3">
<select name="role" class="form-control">
<option value="operator">Operator</option>
<option value="admin">Admin</option>
</select>
</div>

<div class="col-md-3 d-grid">
<button name="tambah" class="btn btn-success">Tambah</button>
</div>

</form>

</div>
</div>

<!-- SEARCH -->
<form class="mb-3">
<input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari user..." class="form-control">
</form>

<form method="post" onsubmit="return confirm('Hapus user terpilih?')">

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered">

<thead>
<tr>
<th width="40"><input type="checkbox" id="checkAll"></th>
<th>ID</th>
<th>Username</th>
<th>Role</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php $rows=[]; while($u=mysqli_fetch_assoc($data)): $rows[]=$u; ?>

<tr>
<td><input type="checkbox" name="hapus_ids[]" value="<?= $u['id'] ?>" class="row-check"></td>
<td><?= $u['id'] ?></td>
<td><?= htmlspecialchars($u['username']) ?></td>
<td><?= $u['role'] ?></td>

<td>

<button class="btn btn-warning btn-sm"
type="button"
data-bs-toggle="modal"
data-bs-target="#edit<?= $u['id'] ?>">
<i class="bi bi-pencil"></i>
</button>

<a href="?hapus=<?= $u['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus user?')">
<i class="bi bi-trash"></i>
</a>

</td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

<button name="hapus_massal" class="btn btn-danger btn-sm">
Hapus Terpilih
</button>

</div>
</div>
</form>

<!-- PAGINATION -->
<?php if ($totalPage>1): ?>
<nav class="mt-3">
<ul class="pagination justify-content-center">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>">
<a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>
<?php endif; ?>

<!-- MODAL EDIT -->
<?php foreach($rows as $u): ?>

<div class="modal fade" id="edit<?= $u['id'] ?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="post">

<input type="hidden" name="id" value="<?= $u['id'] ?>">

<div class="modal-header">
<h5>Edit User</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input name="username"
class="form-control mb-2"
value="<?= htmlspecialchars($u['username']) ?>"
required>

<input name="password"
type="password"
class="form-control mb-2"
placeholder="Kosongkan jika tidak diubah">

<select name="role" class="form-control">
<option <?= $u['role']=='admin'?'selected':'' ?>>admin</option>
<option <?= $u['role']=='operator'?'selected':'' ?>>operator</option>
</select>

</div>

<div class="modal-footer">
<button name="update" class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("checkAll").onclick=()=>{
document.querySelectorAll(".row-check").forEach(cb=>cb.checked=checkAll.checked)
}
</script>

</body>
</html>
