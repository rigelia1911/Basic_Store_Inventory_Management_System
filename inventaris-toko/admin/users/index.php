<?php
$pageTitle = 'Pengguna';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-content">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="page-content">
        <?php flashMessage(); ?>

        <div class="page-header">
            <h1>Data Pengguna</h1>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Pengguna</a>
        </div>

        <div class="table-wrapper">
            <?php if ($users): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($u['nama']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td>
                            <span class="badge badge-<?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?= $u['id_user'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <?php if ($u['id_user'] != $_SESSION['id_user']): ?>
                            <a href="hapus.php?id=<?= $u['id_user'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">Belum ada data pengguna.</div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
