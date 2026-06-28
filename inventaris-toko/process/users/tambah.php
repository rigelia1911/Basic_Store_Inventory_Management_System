<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
require_once __DIR__ . '/../../includes/validasi.php';
requireAdmin();

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

if ($nama === '' || $username === '' || $password === '') {
    $_SESSION['flash_error'] = 'Nama, username, dan password wajib diisi.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

if (strlen($nama) < 3 || strlen($nama) > 100) {
    $_SESSION['flash_error'] = 'Nama lengkap minimal 3 karakter dan maksimal 100 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

if (strlen($username) < 3 || strlen($username) > 50) {
    $_SESSION['flash_error'] = 'Username minimal 3 karakter dan maksimal 50 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

if (strlen($password) < 6 || strlen($password) > 64) {
    $_SESSION['flash_error'] = 'Password minimal 6 karakter dan maksimal 64 karakter.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

if (!in_array($role, ['admin', 'user'], true)) {
    $_SESSION['flash_error'] = 'Role tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['flash_error'] = 'Username sudah digunakan.';
    header('Location: ' . getBaseUrl() . '/admin/users/tambah.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)');
$stmt->execute([$nama, $username, $hash, $role]);

$_SESSION['flash_success'] = 'Pengguna berhasil ditambahkan.';
header('Location: ' . getBaseUrl() . '/admin/users/index.php');
exit;
