<?php
$pageTitle = 'Tambah Kategori';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Tambah Kategori</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <div class="card" style="max-width:500px;">
            <form method="POST" action="<?= getBaseUrl() ?>/process/kategori/tambah.php">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
