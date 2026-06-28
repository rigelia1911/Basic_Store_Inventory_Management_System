<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';

$id_produk     = (int) ($_POST['id_produk'] ?? 0);
$tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
$jumlah_masuk  = (int) ($_POST['jumlah_masuk'] ?? 0);

if ($id_produk <= 0) {
    $_SESSION['flash_error'] = 'Produk wajib dipilih.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_masuk/tambah.php'
        : getBaseUrl() . '/user/barang_masuk.php';
    header('Location: ' . $redirect);
    exit;
}

if ($tanggal_masuk === '' || !isValidDateString($tanggal_masuk)) {
    $_SESSION['flash_error'] = 'Tanggal masuk wajib diisi dengan format YYYY-MM-DD yang valid.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_masuk/tambah.php'
        : getBaseUrl() . '/user/barang_masuk.php';
    header('Location: ' . $redirect);
    exit;
}

if (!isPositiveInteger($jumlah_masuk)) {
    $_SESSION['flash_error'] = 'Jumlah masuk harus berupa angka bulat positif.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_masuk/tambah.php'
        : getBaseUrl() . '/user/barang_masuk.php';
    header('Location: ' . $redirect);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO transaksi_masuk (id_produk, id_user, tanggal_masuk, jumlah_masuk)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$id_produk, $_SESSION['id_user'], $tanggal_masuk, $jumlah_masuk]);

    $stmt = $pdo->prepare('UPDATE produk SET stok = stok + ? WHERE id_produk = ?');
    $stmt->execute([$jumlah_masuk, $id_produk]);

    $pdo->commit();
    $_SESSION['flash_success'] = 'Transaksi barang masuk berhasil dicatat.';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash_error'] = 'Gagal menyimpan transaksi: ' . $e->getMessage();
}

$redirect = ($_SESSION['role'] === 'admin')
    ? getBaseUrl() . '/admin/barang_masuk/index.php'
    : getBaseUrl() . '/user/barang_masuk.php';
header('Location: ' . $redirect);
exit;
