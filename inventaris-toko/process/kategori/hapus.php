<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['flash_error'] = 'ID tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM produk WHERE id_kategori = ?');
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['flash_error'] = 'Kategori tidak dapat dihapus karena masih memiliki produk.';
    header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM kategori WHERE id_kategori = ?');
$stmt->execute([$id]);

$_SESSION['flash_success'] = 'Kategori berhasil dihapus.';
header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
exit;
