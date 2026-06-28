<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';

$result = tambahBarangKeluar($pdo, $_POST, (int) $_SESSION['id_user']);

$_SESSION[$result['success'] ? 'flash_success' : 'flash_error'] = $result['success']
    ? $result['message']
    : $result['error'];

$redirect = ($_SESSION['role'] === 'admin')
    ? '/inventaris-toko/admin/barang_keluar/index.php'
    : '/inventaris-toko/user/barang_keluar.php';

if (!$result['success']) {
    $redirect = ($_SESSION['role'] === 'admin')
        ? '/inventaris-toko/admin/barang_keluar/tambah.php'
        : '/inventaris-toko/user/barang_keluar.php';
}

header('Location: ' . $redirect);
exit;
