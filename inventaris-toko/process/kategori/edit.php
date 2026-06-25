<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id_kategori   = (int) ($_POST['id_kategori'] ?? 0);
$nama_kategori = trim($_POST['nama_kategori'] ?? '');

if ($id_kategori <= 0 || $nama_kategori === '') {
    $_SESSION['flash_error'] = 'Data tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
    exit;
}

$stmt = $pdo->prepare('UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?');
$stmt->execute([$nama_kategori, $id_kategori]);

$_SESSION['flash_success'] = 'Kategori berhasil diperbarui.';
header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
exit;
