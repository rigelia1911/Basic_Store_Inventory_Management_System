<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SESSION['role'] === 'admin') {
    header('Location: /inventaris-toko/admin/index.php');
    exit;
}

$totalProduk    = hitungTotalProduk($pdo);
$stokRendah     = hitungProdukStokRendah($pdo);
$masukHariIni   = hitungBarangMasukHariIni($pdo, (int) $_SESSION['id_user']);
$keluarHariIni  = hitungBarangKeluarHariIni($pdo, (int) $_SESSION['id_user']);
$produk         = cariProduk($pdo);
$produk         = array_slice($produk, 0, 10);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="stats-grid">
            <div class="card">
                <div class="card-title">Total Produk</div>
                <div class="card-value"><?= $totalProduk ?></div>
            </div>
            <div class="card">
                <div class="card-title">Stok Rendah (&le;5)</div>
                <div class="card-value" style="color:var(--warning)"><?= $stokRendah ?></div>
            </div>
            <div class="card">
                <div class="card-title">Barang Masuk Saya Hari Ini</div>
                <div class="card-value" style="color:var(--success)"><?= $masukHariIni ?></div>
            </div>
            <div class="card">
                <div class="card-title">Barang Keluar Saya Hari Ini</div>
                <div class="card-value" style="color:var(--danger)"><?= $keluarHariIni ?></div>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom:1rem;font-size:1rem;">Daftar Produk</h3>
            <div class="table-wrapper" style="border:none;">
                <?php if ($produk): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($p['nama_kategori']) ?></td>
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
                <div class="empty-state">Belum ada produk.</div>
                <?php endif; ?>
            </div>
            <p style="margin-top:1rem;">
                <a href="produk.php">Lihat semua produk &rarr;</a>
            </p>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
