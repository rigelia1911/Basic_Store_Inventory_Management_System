<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['flash_error'] = 'ID tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/users/index.php');
    exit;
}

if ($id === (int) $_SESSION['id_user']) {
    $_SESSION['flash_error'] = 'Tidak dapat menghapus akun yang sedang aktif.';
    header('Location: ' . getBaseUrl() . '/admin/users/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_masuk WHERE id_user = ?');
$stmt->execute([$id]);
$masuk = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_keluar WHERE id_user = ?');
$stmt->execute([$id]);
$keluar = $stmt->fetchColumn();

if ($masuk > 0 || $keluar > 0) {
    $_SESSION['flash_error'] = 'Pengguna tidak dapat dihapus karena memiliki riwayat transaksi.';
    header('Location: ' . getBaseUrl() . '/admin/users/index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM users WHERE id_user = ?');
$stmt->execute([$id]);

$_SESSION['flash_success'] = 'Pengguna berhasil dihapus.';
header('Location: ' . getBaseUrl() . '/admin/users/index.php');
exit;
