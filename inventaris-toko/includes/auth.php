<?php

require_once __DIR__ . '/validasi.php';

function loginUser(PDO $pdo, string $username, string $password): array
{
    $username = trim($username);

    if ($error = validasiLogin($username, $password)) {
        return ['success' => false, 'error' => $error];
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'error' => 'Username atau password salah.'];
    }

    $_SESSION['id_user']  = $user['id_user'];
    $_SESSION['nama']     = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    return [
        'success' => true,
        'message' => 'Login berhasil.',
        'role'    => $user['role'],
        'user'    => $user,
    ];
}

function logoutUser(): void
{
    session_unset();
    session_destroy();
}

function registerUser(PDO $pdo, array $data): array
{
    if ($error = validasiRegister($data)) {
        return ['success' => false, 'error' => $error];
    }

    $nama     = trim($data['nama']);
    $username = trim($data['username']);
    $password = $data['password'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
    $stmt->execute([$username]);

    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'Username sudah digunakan, silakan pilih username lain.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nama, $username, $hash, 'user']);

    return ['success' => true, 'message' => 'Registrasi berhasil! Silakan login.'];
}

function getLoginRedirectUrl(string $role): string
{
    return $role === 'admin'
        ? '/inventaris-toko/admin/index.php'
        : '/inventaris-toko/user/index.php';
}
