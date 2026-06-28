<?php
$pageTitle = 'Pengguna';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$keyword = trim($_GET['cari'] ?? '');
$users   = cariUser($pdo, $keyword);

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

        <form method="GET" class="card" style="margin-bottom:1rem;padding:1rem;display:flex;gap:0.5rem;align-items:center;">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama, username, atau role..."
                   value="<?= htmlspecialchars($keyword) ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

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
            <div class="empty-state"><?= $keyword !== '' ? 'Pengguna tidak ditemukan.' : 'Belum ada data pengguna.' ?></div>
            <?php endif; ?>
        </div>
    </div>
    <footer class="page-footer">&copy; <?= date('Y') ?> Inventaris Toko</footer>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
