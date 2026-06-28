<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header('Location: /inventaris-toko/auth/login.php');
    exit;
}

function requireAdmin(): void
{
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /inventaris-toko/user/index.php');
        exit;
    }
}

function assetUrl(string $path): string
{
    return '/inventaris-toko/assets/' . ltrim($path, '/');
}

function formatRupiah(float|int|string $angka): string
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function flashMessage(): void
{
    if (!empty($_SESSION['flash_success'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['flash_success']) . '</div>';
        unset($_SESSION['flash_success']);
    }
    if (!empty($_SESSION['flash_error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['flash_error']) . '</div>';
        unset($_SESSION['flash_error']);
    }
}
