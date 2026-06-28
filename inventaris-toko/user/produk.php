<?php
$pageTitle = 'Daftar Produk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SESSION['role'] === 'admin') {
    header('Location: /inventaris-toko/admin/produk/index.php');
    exit;
}

$keyword = trim($_GET['cari'] ?? '');
$produk  = cariProduk($pdo, $keyword);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Daftar Produk</h1>
        </div>

        <form method="GET" class="card" style="margin-bottom:1rem;padding:1rem;display:flex;gap:0.5rem;align-items:center;">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama, kode, atau kategori..."
                   value="<?= htmlspecialchars($keyword) ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="produk.php" class="btn btn-secondary">Reset</a>
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
                        <th>Harga Jual</th>
                        <th>Stok</th>
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
                        <td><?= formatRupiah($p['harga_jual']) ?></td>
                        <td>
                            <?php if ($p['stok'] <= 5): ?>
                                <span class="badge badge-danger"><?= $p['stok'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $p['stok'] ?></span>
                            <?php endif; ?>
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
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
