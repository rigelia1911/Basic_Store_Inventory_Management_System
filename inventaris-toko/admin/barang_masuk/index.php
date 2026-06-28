<?php
$pageTitle = 'Barang Masuk';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$keyword   = trim($_GET['cari'] ?? '');
$transaksi = cariTransaksiMasuk($pdo, $keyword);

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Transaksi Barang Masuk</h1>
            <a href="tambah.php" class="btn btn-success">+ Catat Barang Masuk</a>
        </div>

        <form method="GET" class="card" style="margin-bottom:1rem;padding:1rem;display:flex;gap:0.5rem;align-items:center;">
            <input type="text" name="cari" class="form-control" placeholder="Cari produk atau petugas..."
                   value="<?= htmlspecialchars($keyword) ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

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
                        <td><?= date('d/m/Y', strtotime($t['tanggal_masuk'])) ?></td>
                        <td><?= htmlspecialchars($t['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($t['kode_produk'] ?? '-') ?></td>
                        <td><span class="badge badge-success">+<?= $t['jumlah_masuk'] ?></span></td>
                        <td><?= htmlspecialchars($t['nama_user']) ?></td>
                        <td>
                            <a href="hapus.php?id=<?= $t['id_masuk'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikurangi.')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state"><?= $keyword !== '' ? 'Transaksi tidak ditemukan.' : 'Belum ada transaksi barang masuk.' ?></div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
