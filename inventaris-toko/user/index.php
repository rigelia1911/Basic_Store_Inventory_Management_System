<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';

if ($_SESSION['role'] === 'admin') {
    header('Location: ' . getBaseUrl() . '/admin/index.php');
    exit;
}

$totalProduk = $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
$stokRendah  = $pdo->query('SELECT COUNT(*) FROM produk WHERE stok <= 5')->fetchColumn();

$masukSaya = $pdo->prepare(
    'SELECT COALESCE(SUM(jumlah_masuk), 0) FROM transaksi_masuk WHERE id_user = ? AND tanggal_masuk = CURDATE()'
);
$masukSaya->execute([$_SESSION['id_user']]);
$masukHariIni = $masukSaya->fetchColumn();

$keluarSaya = $pdo->prepare(
    'SELECT COALESCE(SUM(jumlah_keluar), 0) FROM transaksi_keluar WHERE id_user = ? AND tanggal_keluar = CURDATE()'
);
$keluarSaya->execute([$_SESSION['id_user']]);
$keluarHariIni = $keluarSaya->fetchColumn();

$produk = $pdo->query(
    'SELECT p.nama_produk, p.stok, k.nama_kategori
     FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori
     ORDER BY p.nama_produk LIMIT 10'
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
