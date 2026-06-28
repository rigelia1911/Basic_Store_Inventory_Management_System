<?php
$pageTitle = 'Barang Keluar';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../auth/cek_login.php';

require_once __DIR__ . '/../includes/functions.php';

if ($_SESSION['role'] === 'admin') {
    header('Location: /inventaris-toko/admin/barang_keluar/index.php');
    exit;
}

$produk  = getProdukUntukTransaksi($pdo);
$riwayat = cariTransaksiKeluar($pdo, '', (int) $_SESSION['id_user']);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Barang Keluar</h1>
        </div>

        <div class="card" style="max-width:500px;margin-bottom:2rem;">
            <h3 style="margin-bottom:1rem;font-size:1rem;">Catat Barang Keluar</h3>
            <?php if (!$produk): ?>
                <div class="alert alert-warning">Belum ada produk tersedia.</div>
            <?php else: ?>
            <form method="POST" action="/inventaris-toko/process/barang_keluar/tambah.php">
                <div class="form-group">
                    <label for="id_produk">Produk</label>
                    <select id="id_produk" name="id_produk" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produk as $p): ?>
                        <option value="<?= $p['id_produk'] ?>">
                            <?= htmlspecialchars($p['nama_produk']) ?> (Stok: <?= $p['stok'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="form-control"
                           value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label for="jumlah_keluar">Jumlah Keluar</label>
                    <input type="number" id="jumlah_keluar" name="jumlah_keluar" class="form-control" min="1" required>
                </div>
                <button type="submit" class="btn btn-danger">Simpan</button>
            </form>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 style="margin-bottom:1rem;font-size:1rem;">Riwayat Barang Keluar Saya</h3>
            <div class="table-wrapper" style="border:none;">
                <?php if ($riwayat): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $t): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['tanggal_keluar'])) ?></td>
                            <td><?= htmlspecialchars($t['nama_produk']) ?></td>
                            <td><span class="badge badge-danger">-<?= $t['jumlah_keluar'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">Belum ada riwayat transaksi.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
