<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$result = tambahProduk($pdo, $_POST, $_FILES['gambar'] ?? []);

if (!$result['success']) {
    $_SESSION['flash_error'] = $result['error'];
    header('Location: /inventaris-toko/admin/produk/tambah.php');
    exit;
}

$_SESSION['flash_success'] = $result['message'];
header('Location: /inventaris-toko/admin/produk/index.php');
exit;
