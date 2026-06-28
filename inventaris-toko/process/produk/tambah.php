<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';
require_once __DIR__ . '/../../includes/upload_produk.php';
requireAdmin();

$id_kategori  = (int) ($_POST['id_kategori'] ?? 0);
$nama_produk  = trim($_POST['nama_produk'] ?? '');
$kode_produk  = trim($_POST['kode_produk'] ?? '');
$harga_beli   = (float) ($_POST['harga_beli'] ?? 0);
$harga_jual   = (float) ($_POST['harga_jual'] ?? 0);
$stok         = (int) ($_POST['stok'] ?? 0);
$deskripsi    = trim($_POST['deskripsi'] ?? '');

if ($id_kategori <= 0) {
    $_SESSION['flash_error'] = 'Kategori produk wajib dipilih.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if ($nama_produk === '') {
    $_SESSION['flash_error'] = 'Nama produk wajib diisi.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if (strlen($nama_produk) < 3 || strlen($nama_produk) > 100) {
    $_SESSION['flash_error'] = 'Nama produk minimal 3 karakter dan maksimal 100 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if ($kode_produk !== '' && strlen($kode_produk) > 50) {
    $_SESSION['flash_error'] = 'Kode produk maksimal 50 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if (!isPositiveNumber($harga_beli)) {
    $_SESSION['flash_error'] = 'Harga beli harus berupa angka positif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if (!isPositiveNumber($harga_jual)) {
    $_SESSION['flash_error'] = 'Harga jual harus berupa angka positif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if (!isNonNegativeInteger($stok)) {
    $_SESSION['flash_error'] = 'Stok harus berupa angka bulat non-negatif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

if ($deskripsi !== '' && strlen($deskripsi) > 500) {
    $_SESSION['flash_error'] = 'Deskripsi maksimal 500 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

$path = uploadProdukImage($_FILES['gambar'] ?? [], true);
if ($path === null) {
    header('Location: ' . getBaseUrl() . '/admin/produk/tambah.php');
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO produk (id_kategori, nama_produk, kode_produk, harga_beli, harga_jual, stok, path, deskripsi)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$id_kategori, $nama_produk, $kode_produk ?: null, $harga_beli, $harga_jual, $stok, $path, $deskripsi ?: null]);

$_SESSION['flash_success'] = 'Produk berhasil ditambahkan.';
header('Location: ' . getBaseUrl() . '/admin/produk/index.php');
exit;
