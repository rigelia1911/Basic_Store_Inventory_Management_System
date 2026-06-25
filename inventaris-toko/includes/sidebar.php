<?php
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

function navActive(string $dir, string $page = 'index.php'): string
{
    global $currentDir, $currentPage;
    return ($currentDir === $dir && $currentPage === $page) ? 'active' : '';
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Inventaris Toko</h2>
        <small><?= $isAdmin ? 'Panel Admin' : 'Panel User' ?></small>
    </div>
    <ul class="sidebar-nav">
        <?php if ($isAdmin): ?>
            <li><a href="<?= getBaseUrl() ?>/admin/index.php" class="<?= navActive('admin') ?>">Dashboard</a></li>

            <li class="nav-section">Master Data</li>
            <li><a href="<?= getBaseUrl() ?>/admin/kategori/index.php" class="<?= navActive('kategori') ?>">Kategori</a></li>
            <li><a href="<?= getBaseUrl() ?>/admin/produk/index.php" class="<?= navActive('produk') ?>">Produk</a></li>
            <li><a href="<?= getBaseUrl() ?>/admin/users/index.php" class="<?= navActive('users') ?>">Pengguna</a></li>

            <li class="nav-section">Transaksi</li>
            <li><a href="<?= getBaseUrl() ?>/admin/barang_masuk/index.php" class="<?= navActive('barang_masuk') ?>">Barang Masuk</a></li>
            <li><a href="<?= getBaseUrl() ?>/admin/barang_keluar/index.php" class="<?= navActive('barang_keluar') ?>">Barang Keluar</a></li>
        <?php else: ?>
            <li><a href="<?= getBaseUrl() ?>/user/index.php" class="<?= navActive('user') ?>">Dashboard</a></li>
            <li><a href="<?= getBaseUrl() ?>/user/produk.php" class="<?= navActive('user', 'produk.php') ?>">Daftar Produk</a></li>
            <li><a href="<?= getBaseUrl() ?>/user/barang_masuk.php" class="<?= navActive('user', 'barang_masuk.php') ?>">Barang Masuk</a></li>
            <li><a href="<?= getBaseUrl() ?>/user/barang_keluar.php" class="<?= navActive('user', 'barang_keluar.php') ?>">Barang Keluar</a></li>
        <?php endif; ?>
    </ul>
</aside>
