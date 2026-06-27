<?php
$pageTitle = 'Edit Produk';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/upload_produk.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM produk WHERE id_produk = ?');
$stmt->execute([$id]);
$produk = $stmt->fetch();

if (!$produk) {
    $_SESSION['flash_error'] = 'Produk tidak ditemukan.';
    header('Location: index.php');
    exit;
}

$kategori = $pdo->query('SELECT * FROM kategori ORDER BY nama_kategori')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Edit Produk</h1>
            <a href="index.php" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <div class="card" style="max-width:600px;">
            <form method="POST" action="<?= getBaseUrl() ?>/process/produk/edit.php" enctype="multipart/form-data">
                <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori" class="form-control" required>
                        <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"
                            <?= $k['id_kategori'] == $produk['id_kategori'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama_produk" class="form-control"
                           value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="kode_produk">Kode Produk</label>
                    <input type="text" id="kode_produk" name="kode_produk" class="form-control"
                           value="<?= htmlspecialchars($produk['kode_produk'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="harga_beli">Harga Beli</label>
                    <input type="number" id="harga_beli" name="harga_beli" class="form-control" min="1"
                           value="<?= $produk['harga_beli'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" id="harga_jual" name="harga_jual" class="form-control" min="1"
                           value="<?= $produk['harga_jual'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control" min="0"
                           value="<?= $produk['stok'] ?>">
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar Produk</label>
                    <?php if ($img = produkImageUrl($produk['path'] ?? null)): ?>
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>" class="produk-preview">
                    <?php endif; ?>
                    <input type="file" id="gambar" name="gambar" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                    <small class="form-hint">Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.</small>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control"><?= htmlspecialchars($produk['deskripsi'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
