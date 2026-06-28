<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';
requireAdmin();

$nama_kategori = trim($_POST['nama_kategori'] ?? '');

if ($nama_kategori === '') {
    $_SESSION['flash_error'] = 'Nama kategori wajib diisi.';
    header('Location: ' . getBaseUrl() . '/admin/kategori/tambah.php');
    exit;
}

if (strlen($nama_kategori) < 3 || strlen($nama_kategori) > 100) {
    $_SESSION['flash_error'] = 'Nama kategori minimal 3 karakter dan maksimal 100 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/kategori/tambah.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO kategori (nama_kategori) VALUES (?)');
$stmt->execute([$nama_kategori]);

$_SESSION['flash_success'] = 'Kategori berhasil ditambahkan.';
header('Location: ' . getBaseUrl() . '/admin/kategori/index.php');
exit;
