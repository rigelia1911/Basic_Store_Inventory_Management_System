<?php
$pageTitle = 'Tambah Produk';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/upload_produk.php';
requireAdmin();

$kategori = $pdo->query('SELECT * FROM kategori ORDER BY nama_kategori')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Tambah Produk</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <div class="card" style="max-width:600px;">
            <?php if (!$kategori): ?>
                <div class="alert alert-warning">Tambahkan kategori terlebih dahulu sebelum menambah produk.</div>
            <?php else: ?>
            <form method="POST" action="<?= getBaseUrl() ?>/process/produk/tambah.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama_produk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="kode_produk">Kode Produk</label>
                    <input type="text" id="kode_produk" name="kode_produk" class="form-control" placeholder="Opsional">
                </div>
                <div class="form-group">
                    <label for="harga_beli">Harga Beli</label>
                    <input type="number" id="harga_beli" name="harga_beli" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" id="harga_jual" name="harga_jual" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stok Awal</label>
                    <input type="number" id="stok" name="stok" class="form-control" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar Produk</label>
                    <input type="file" id="gambar" name="gambar" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" required>
                    <small class="form-hint">Format: JPG, PNG, GIF, WEBP. Maksimal 2 MB.</small>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Opsional"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
