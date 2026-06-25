<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

if ($nama === '' || $username === '' || $password === '' || !in_array($role, ['admin', 'user'])) {
    $_SESSION['flash_error'] = 'Data pengguna tidak lengkap.';
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
