<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$result = hapusUser($pdo, $id, (int) $_SESSION['id_user']);

$_SESSION[$result['success'] ? 'flash_success' : 'flash_error'] = $result['success']
    ? $result['message']
    : $result['error'];

header('Location: /inventaris-toko/admin/users/index.php');
exit;
