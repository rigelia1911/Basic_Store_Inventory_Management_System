<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$result = tambahUser($pdo, $_POST);

if (!$result['success']) {
    $_SESSION['flash_error'] = $result['error'];
    header('Location: /inventaris-toko/admin/users/tambah.php');
    exit;
}

$_SESSION['flash_success'] = $result['message'];
header('Location: /inventaris-toko/admin/users/index.php');
exit;
