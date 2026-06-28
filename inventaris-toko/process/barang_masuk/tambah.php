<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';

$result = tambahBarangMasuk($pdo, $_POST, (int) $_SESSION['id_user']);

$_SESSION[$result['success'] ? 'flash_success' : 'flash_error'] = $result['success']
    ? $result['message']
    : $result['error'];

$redirect = ($_SESSION['role'] === 'admin')
    ? '/inventaris-toko/admin/barang_masuk/index.php'
    : '/inventaris-toko/user/barang_masuk.php';

if (!$result['success']) {
    $redirect = ($_SESSION['role'] === 'admin')
        ? '/inventaris-toko/admin/barang_masuk/tambah.php'
        : '/inventaris-toko/user/barang_masuk.php';
}

header('Location: ' . $redirect);
exit;
