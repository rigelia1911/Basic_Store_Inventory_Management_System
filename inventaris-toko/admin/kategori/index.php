<?php
$pageTitle = 'Kategori';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$kategori = $pdo->query('SELECT k.*, COUNT(p.id_produk) AS jumlah_produk
    FROM kategori k LEFT JOIN produk p ON k.id_kategori = p.id_kategori
    GROUP BY k.id_kategori ORDER BY k.nama_kategori')->fetchAll();

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
            <div class="empty-state">Belum ada data kategori.</div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
