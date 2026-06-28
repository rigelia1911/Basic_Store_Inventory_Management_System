<?php
$pageTitle = 'Barang Keluar';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$transaksi = $pdo->query(
    'SELECT tk.*, p.nama_produk, p.kode_produk, u.nama AS nama_user
     FROM transaksi_keluar tk
     JOIN produk p ON tk.id_produk = p.id_produk
     JOIN users u ON tk.id_user = u.id_user
     ORDER BY tk.tanggal_keluar DESC, tk.id_keluar DESC'
)->fetchAll();


require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Transaksi Barang Keluar</h1>
            <a href="tambah.php" class="btn btn-danger">+ Catat Barang Keluar</a>
        </div>

        <div class="table-wrapper">
            <?php if ($transaksi): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Kode</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi as $i => $t): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= date('d/m/Y', strtotime($t['tanggal_keluar'])) ?></td>
                        <td><?= htmlspecialchars($t['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($t['kode_produk'] ?? '-') ?></td>
                        <td><span class="badge badge-danger">-<?= $t['jumlah_keluar'] ?></span></td>
                        <td><?= htmlspecialchars($t['nama_user']) ?></td>
                        <td>
                            <a href="hapus.php?id=<?= $t['id_keluar'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan.')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">Belum ada transaksi barang keluar.</div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
