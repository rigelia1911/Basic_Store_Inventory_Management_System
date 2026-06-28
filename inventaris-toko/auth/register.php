<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['id_user'])) {
    header('Location: ' . getLoginRedirectUrl($_SESSION['role']));
    exit;
}

require_once __DIR__ . '/../config/database.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerUser($pdo, $_POST);

    if ($result['success']) {
        $_SESSION['flash_success'] = $result['message'];
        header('Location: login.php');
        exit;
    }

    $error = $result['error'];
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
