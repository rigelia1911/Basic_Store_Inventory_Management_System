<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = (int) ($_POST['id_user'] ?? 0);
$result = editUser($pdo, $_POST);

if (!$result['success']) {
    $_SESSION['flash_error'] = $result['error'];
    $redirect = $id > 0
        ? '/inventaris-toko/admin/users/edit.php?id=' . $id
        : '/inventaris-toko/admin/users/index.php';
    header('Location: ' . $redirect);
    exit;
}

$_SESSION['flash_success'] = $result['message'];
header('Location: /inventaris-toko/admin/users/index.php');
exit;
