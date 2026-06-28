<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$totalProduk      = hitungTotalProduk($pdo);
$totalKategori    = hitungTotalKategori($pdo);
$totalUsers       = hitungTotalUser($pdo);
$stokRendah       = hitungProdukStokRendah($pdo);
$masukHariIni     = hitungBarangMasukHariIni($pdo);
$keluarHariIni    = hitungBarangKeluarHariIni($pdo);
$produkStokRendah = getProdukStokRendah($pdo);

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
                <div class="card-title">Total Kategori</div>
                <div class="card-value"><?= $totalKategori ?></div>
            </div>
            <div class="card">
                <div class="card-title">Total Pengguna</div>
                <div class="card-value"><?= $totalUsers ?></div>
            </div>
            <div class="card">
                <div class="card-title">Stok Rendah (&le;5)</div>
                <div class="card-value" style="color:var(--warning)"><?= $stokRendah ?></div>
            </div>
            <div class="card">
                <div class="card-title">Barang Masuk Hari Ini</div>
                <div class="card-value" style="color:var(--success)"><?= $masukHariIni ?></div>
            </div>
            <div class="card">
                <div class="card-title">Barang Keluar Hari Ini</div>
                <div class="card-value" style="color:var(--danger)"><?= $keluarHariIni ?></div>
            </div>
        </div>

        <?php if ($produkStokRendah): ?>
        <div class="card">
            <h3 style="margin-bottom:1rem;font-size:1rem;">Produk dengan Stok Rendah</h3>
            <div class="table-wrapper" style="border:none;">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produkStokRendah as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($p['nama_kategori']) ?></td>
                            <td><span class="badge badge-danger"><?= $p['stok'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
