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

$stmt = $pdo->prepare('SELECT * FROM transaksi_masuk WHERE id_masuk = ?');
$stmt->execute([$id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
    $_SESSION['flash_error'] = 'Transaksi tidak ditemukan.';
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM transaksi_masuk WHERE id_masuk = ?');
    $stmt->execute([$id]);

    $stmt = $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');
    $stmt->execute([$transaksi['jumlah_masuk'], $transaksi['id_produk']]);

    $pdo->commit();
    $_SESSION['flash_success'] = 'Transaksi barang masuk berhasil dihapus.';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash_error'] = 'Gagal menghapus transaksi: ' . $e->getMessage();
}

header('Location: index.php');
exit;
