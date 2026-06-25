<header class="navbar">
    <div>
        <strong><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></strong>
    </div>
    <div class="navbar-user">
        Halo, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
        &nbsp;|&nbsp;
        <a href="<?= getBaseUrl() ?>/auth/logout.php">Keluar</a>
    </div>
</header>
