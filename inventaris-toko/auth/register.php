<?php
session_start();

if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../user/index.php');
    }
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/validasi.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama            = trim($_POST['nama'] ?? '');
    $username        = trim($_POST['username'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($nama === '' || $username === '' || $password === '' || $confirmPassword === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = 'Nama lengkap minimal 3 karakter dan maksimal 100 karakter.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username minimal 3 karakter dan maksimal 50 karakter.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6 || strlen($password) > 64) {
        $error = 'Password minimal 6 karakter dan maksimal 64 karakter.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetchColumn() > 0) {
            $error = 'Username sudah digunakan, silakan pilih username lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $pdo->prepare(
                'INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$nama, $username, $hash, $role]);

            $_SESSION['flash_success'] = 'Registrasi berhasil! Silakan login.';
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Inventaris Toko</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h2>Daftar Akun</h2>
            <p style="text-align:center;color:var(--text-muted);font-size:0.875rem;margin-bottom:1.25rem;">
                Buat akun baru untuk mengakses sistem inventaris
            </p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" autofocus>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Opsional">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem;">Daftar</button>
            </form>

            <p style="text-align:center;margin-top:1rem;font-size:0.85rem;">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </p>
            <p style="text-align:center;margin-top:0.5rem;font-size:0.85rem;">
                <a href="../index.php">&larr; Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</html>
