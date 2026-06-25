<?php
$pageTitle = 'Edit Kategori';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM kategori WHERE id_kategori = ?');
$stmt->execute([$id]);
$kategori = $stmt->fetch();

if (!$kategori) {
    $_SESSION['flash_error'] = 'Kategori tidak ditemukan.';
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
            <h1>Edit Kategori</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <div class="card" style="max-width:500px;">
            <form method="POST" action="<?= getBaseUrl() ?>/process/kategori/edit.php">
                <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori'] ?>">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" class="form-control"
                           value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
