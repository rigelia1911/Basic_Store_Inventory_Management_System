<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id_user  = (int) ($_POST['id_user'] ?? 0);
$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

if ($id_user <= 0 || $nama === '' || $username === '' || !in_array($role, ['admin', 'user'])) {
    $_SESSION['flash_error'] = 'Data pengguna tidak valid.';
    header('Location: ' . getBaseUrl() . '/admin/users/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? AND id_user != ?');
$stmt->execute([$username, $id_user]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['flash_error'] = 'Username sudah digunakan.';
    header('Location: ' . getBaseUrl() . '/admin/users/edit.php?id=' . $id_user);
    exit;
}

if ($password !== '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('UPDATE users SET nama = ?, username = ?, password = ?, role = ? WHERE id_user = ?');
    $stmt->execute([$nama, $username, $hash, $role, $id_user]);
} else {
    $stmt = $pdo->prepare('UPDATE users SET nama = ?, username = ?, role = ? WHERE id_user = ?');
    $stmt->execute([$nama, $username, $role, $id_user]);
}

$_SESSION['flash_success'] = 'Pengguna berhasil diperbarui.';
header('Location: ' . getBaseUrl() . '/admin/users/index.php');
exit;
