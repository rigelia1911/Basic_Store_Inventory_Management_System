<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';
requireAdmin();

$totalProduk   = $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
$totalKategori = $pdo->query('SELECT COUNT(*) FROM kategori')->fetchColumn();
$totalUsers    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$stokRendah    = $pdo->query('SELECT COUNT(*) FROM produk WHERE stok <= 5')->fetchColumn();

$masukHariIni = $pdo->query(
    "SELECT COALESCE(SUM(jumlah_masuk), 0) FROM transaksi_masuk WHERE tanggal_masuk = CURDATE()"
)->fetchColumn();

$keluarHariIni = $pdo->query(
    "SELECT COALESCE(SUM(jumlah_keluar), 0) FROM transaksi_keluar WHERE tanggal_keluar = CURDATE()"
)->fetchColumn();

$produkStokRendah = $pdo->query(
    'SELECT p.nama_produk, p.stok, k.nama_kategori
     FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori
     WHERE p.stok <= 5 ORDER BY p.stok ASC LIMIT 5'
)->fetchAll();

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
