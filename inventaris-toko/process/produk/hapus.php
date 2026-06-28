<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$result = hapusProduk($pdo, $id);

$_SESSION[$result['success'] ? 'flash_success' : 'flash_error'] = $result['success']
    ? $result['message']
    : $result['error'];

header('Location: /inventaris-toko/admin/produk/index.php');
exit;
