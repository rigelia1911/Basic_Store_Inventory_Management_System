<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['flash_error'] = 'ID tidak valid.';
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM transaksi_keluar WHERE id_keluar = ?');
$stmt->execute([$id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
    $_SESSION['flash_error'] = 'Transaksi tidak ditemukan.';
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM transaksi_keluar WHERE id_keluar = ?');
    $stmt->execute([$id]);

    $stmt = $pdo->prepare('UPDATE produk SET stok = stok + ? WHERE id_produk = ?');
    $stmt->execute([$transaksi['jumlah_keluar'], $transaksi['id_produk']]);

    $pdo->commit();
    $_SESSION['flash_success'] = 'Transaksi barang keluar berhasil dihapus.';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash_error'] = 'Gagal menghapus transaksi: ' . $e->getMessage();
}

header('Location: index.php');
exit;
