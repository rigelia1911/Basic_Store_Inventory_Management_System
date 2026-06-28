<?php
$pageTitle = 'Produk';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$keyword = trim($_GET['cari'] ?? '');
$produk = cariProduk($pdo, $keyword);

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Data Produk</h1>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Produk</a>
        </div>

        <form method="GET" class="card" style="margin-bottom:1rem;padding:1rem;display:flex;gap:0.5rem;align-items:center;">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama, kode, atau kategori..."
                   value="<?= htmlspecialchars($keyword) ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <div class="table-wrapper">
            <?php if ($produk): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if ($img = produkImageUrl($p['path'] ?? null)): ?>
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="produk-thumb">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['kode_produk'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($p['nama_kategori']) ?></td>
                        <td><?= formatRupiah($p['harga_beli']) ?></td>
                        <td><?= formatRupiah($p['harga_jual']) ?></td>
                        <td>
                            <?php if ($p['stok'] <= 5): ?>
                                <span class="badge badge-danger"><?= $p['stok'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $p['stok'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="edit.php?id=<?= $p['id_produk'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="hapus.php?id=<?= $p['id_produk'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state"><?= $keyword !== '' ? 'Produk tidak ditemukan.' : 'Belum ada data produk.' ?></div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
