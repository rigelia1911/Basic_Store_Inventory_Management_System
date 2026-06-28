<?php
$pageTitle = 'Kategori';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$keyword  = trim($_GET['cari'] ?? '');
$kategori = cariKategori($pdo, $keyword);

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Data Kategori</h1>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Kategori</a>
        </div>

        <form method="GET" class="card" style="margin-bottom:1rem;padding:1rem;display:flex;gap:0.5rem;align-items:center;">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama kategori..."
                   value="<?= htmlspecialchars($keyword) ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <div class="table-wrapper">
            <?php if ($kategori): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kategori as $i => $k): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($k['nama_kategori']) ?></td>
                        <td><?= $k['jumlah_produk'] ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?= $k['id_kategori'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="hapus.php?id=<?= $k['id_kategori'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state"><?= $keyword !== '' ? 'Kategori tidak ditemukan.' : 'Belum ada data kategori.' ?></div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
