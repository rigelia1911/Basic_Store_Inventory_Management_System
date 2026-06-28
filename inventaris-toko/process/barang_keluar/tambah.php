<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';

$id_produk      = (int) ($_POST['id_produk'] ?? 0);
$tanggal_keluar = $_POST['tanggal_keluar'] ?? '';
$jumlah_keluar  = (int) ($_POST['jumlah_keluar'] ?? 0);

if ($id_produk <= 0) {
    $_SESSION['flash_error'] = 'Produk wajib dipilih.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_keluar/tambah.php'
        : getBaseUrl() . '/user/barang_keluar.php';
    header('Location: ' . $redirect);
    exit;
}

if ($tanggal_keluar === '' || !isValidDateString($tanggal_keluar)) {
    $_SESSION['flash_error'] = 'Tanggal keluar wajib diisi dengan format YYYY-MM-DD yang valid.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_keluar/tambah.php'
        : getBaseUrl() . '/user/barang_keluar.php';
    header('Location: ' . $redirect);
    exit;
}

if (!isPositiveInteger($jumlah_keluar)) {
    $_SESSION['flash_error'] = 'Jumlah keluar harus berupa angka bulat positif.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_keluar/tambah.php'
        : getBaseUrl() . '/user/barang_keluar.php';
    header('Location: ' . $redirect);
    exit;
}

$stmt = $pdo->prepare('SELECT stok FROM produk WHERE id_produk = ?');
$stmt->execute([$id_produk]);
$produk = $stmt->fetch();

if (!$produk) {
    $_SESSION['flash_error'] = 'Produk tidak ditemukan.';
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_keluar/tambah.php'
        : getBaseUrl() . '/user/barang_keluar.php';
    header('Location: ' . $redirect);
    exit;
}

if ($produk['stok'] < $jumlah_keluar) {
    $_SESSION['flash_error'] = 'Stok tidak mencukupi. Stok tersedia: ' . $produk['stok'];
    $redirect = ($_SESSION['role'] === 'admin')
        ? getBaseUrl() . '/admin/barang_keluar/tambah.php'
        : getBaseUrl() . '/user/barang_keluar.php';
    header('Location: ' . $redirect);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO transaksi_keluar (id_produk, id_user, tanggal_keluar, jumlah_keluar)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$id_produk, $_SESSION['id_user'], $tanggal_keluar, $jumlah_keluar]);

    $stmt = $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');
    $stmt->execute([$jumlah_keluar, $id_produk]);

    $pdo->commit();
    $_SESSION['flash_success'] = 'Transaksi barang keluar berhasil dicatat.';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash_error'] = 'Gagal menyimpan transaksi: ' . $e->getMessage();
}

$redirect = ($_SESSION['role'] === 'admin')
    ? getBaseUrl() . '/admin/barang_keluar/index.php'
    : getBaseUrl() . '/user/barang_keluar.php';
header('Location: ' . $redirect);
exit;
