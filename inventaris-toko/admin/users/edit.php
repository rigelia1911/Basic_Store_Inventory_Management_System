<?php
$pageTitle = 'Edit Pengguna';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id_user = ?');
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash_error'] = 'Pengguna tidak ditemukan.';
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Edit Pengguna</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <?php flashMessage(); ?>

        <div class="card" style="max-width:500px;">
            <form method="POST" action="<?= getBaseUrl() ?>/process/users/edit.php">
                <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control"
                           value="<?= htmlspecialchars($user['nama']) ?>" autofocus>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= htmlspecialchars($user['username']) ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
