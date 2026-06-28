<?php
$pageTitle = 'Catat Barang Keluar';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$produk = $pdo->query('SELECT id_produk, nama_produk, kode_produk, stok FROM produk ORDER BY nama_produk')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Catat Barang Keluar</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <?php flashMessage(); ?>

        <div class="card" style="max-width:500px;">
            <?php if (!$produk): ?>
                <div class="alert alert-warning">Tambahkan produk terlebih dahulu.</div>
            <?php else: ?>
            <form method="POST" action="<?= getBaseUrl() ?>/process/barang_keluar/tambah.php">
                <div class="form-group">
                    <label for="id_produk">Produk</label>
                    <select id="id_produk" name="id_produk" class="form-control">
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produk as $p): ?>
                        <option value="<?= $p['id_produk'] ?>">
                            <?= htmlspecialchars($p['nama_produk']) ?>
                            (Stok: <?= $p['stok'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="form-control"
                           value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="jumlah_keluar">Jumlah Keluar</label>
                    <input type="number" id="jumlah_keluar" name="jumlah_keluar" class="form-control" min="1">
                </div>
                <button type="submit" class="btn btn-danger">Simpan Transaksi</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
