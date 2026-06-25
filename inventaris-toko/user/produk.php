<?php
$pageTitle = 'Daftar Produk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';

if ($_SESSION['role'] === 'admin') {
    header('Location: ' . getBaseUrl() . '/admin/produk/index.php');
    exit;
}

$produk = $pdo->query(
    'SELECT p.*, k.nama_kategori FROM produk p
     JOIN kategori k ON p.id_kategori = k.id_kategori
     ORDER BY p.nama_produk'
)->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h1>Daftar Produk</h1>
        </div>

        <div class="table-wrapper">
            <?php if ($produk): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
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
            <div class="empty-state">Belum ada data produk.</div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
