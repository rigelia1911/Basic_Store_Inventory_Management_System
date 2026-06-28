<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';
require_once __DIR__ . '/../../includes/upload_produk.php';
requireAdmin();

$id_produk    = (int) ($_POST['id_produk'] ?? 0);
$id_kategori  = (int) ($_POST['id_kategori'] ?? 0);
$nama_produk  = trim($_POST['nama_produk'] ?? '');
$kode_produk  = trim($_POST['kode_produk'] ?? '');
$harga_beli   = (float) ($_POST['harga_beli'] ?? 0);
$harga_jual   = (float) ($_POST['harga_jual'] ?? 0);
$stok         = (int) ($_POST['stok'] ?? 0);
$deskripsi    = trim($_POST['deskripsi'] ?? '');

if ($id_produk <= 0) {
    $_SESSION['flash_error'] = 'ID produk tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/produk/index.php');
    exit;
}

if ($id_kategori <= 0) {
    $_SESSION['flash_error'] = 'Kategori produk wajib dipilih.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if ($nama_produk === '') {
    $_SESSION['flash_error'] = 'Nama produk wajib diisi.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if (strlen($nama_produk) < 3 || strlen($nama_produk) > 100) {
    $_SESSION['flash_error'] = 'Nama produk minimal 3 karakter dan maksimal 100 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if ($kode_produk !== '' && strlen($kode_produk) > 50) {
    $_SESSION['flash_error'] = 'Kode produk maksimal 50 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if (!isPositiveNumber($harga_beli)) {
    $_SESSION['flash_error'] = 'Harga beli harus berupa angka positif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if (!isPositiveNumber($harga_jual)) {
    $_SESSION['flash_error'] = 'Harga jual harus berupa angka positif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if (!isNonNegativeInteger($stok)) {
    $_SESSION['flash_error'] = 'Stok harus berupa angka bulat non-negatif.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

if ($deskripsi !== '' && strlen($deskripsi) > 500) {
    $_SESSION['flash_error'] = 'Deskripsi maksimal 500 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
    exit;
}

$stmt = $pdo->prepare('SELECT path FROM produk WHERE id_produk = ?');
$stmt->execute([$id_produk]);
$existing = $stmt->fetch();

if (!$existing) {
    $_SESSION['flash_error'] = 'Produk tidak ditemukan.';
    header('Location: ' . getBaseUrl() . '/admin/produk/index.php');
    exit;
}

$path = $existing['path'];
$hasNewImage = isset($_FILES['gambar']) && ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

if ($hasNewImage) {
    $newPath = uploadProdukImage($_FILES['gambar'], false);
    if ($newPath === null) {
        header('Location: ' . getBaseUrl() . '/admin/produk/edit.php?id=' . $id_produk);
        exit;
    }
    deleteProdukImage($path);
    $path = $newPath;
}

$stmt = $pdo->prepare(
    'UPDATE produk SET id_kategori = ?, nama_produk = ?, kode_produk = ?, harga_beli = ?,
     harga_jual = ?, stok = ?, path = ?, deskripsi = ? WHERE id_produk = ?'
);
$stmt->execute([$id_kategori, $nama_produk, $kode_produk ?: null, $harga_beli, $harga_jual, $stok, $path, $deskripsi ?: null, $id_produk]);

$_SESSION['flash_success'] = 'Produk berhasil diperbarui.';
header('Location: ' . getBaseUrl() . '/admin/produk/index.php');
exit;
