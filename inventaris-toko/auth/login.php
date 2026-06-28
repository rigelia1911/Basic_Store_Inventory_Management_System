<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['id_user'])) {
    header('Location: ' . getLoginRedirectUrl($_SESSION['role']));
    exit;
}

require_once __DIR__ . '/../config/database.php';

$error = '';

if (!empty($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
} else {
    $success = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = loginUser($pdo, $_POST['username'] ?? '', $_POST['password'] ?? '');

    if ($result['success']) {
        header('Location: ' . getLoginRedirectUrl($result['role']));
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
    <title>Login - Inventaris Toko</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h2>Inventaris Toko</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem;">Masuk</button>
            </form>

            <p style="text-align:center;margin-top:1rem;font-size:0.85rem;">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
            <p style="text-align:center;margin-top:0.5rem;font-size:0.85rem;">
                <a href="../index.php">&larr; Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</html>
