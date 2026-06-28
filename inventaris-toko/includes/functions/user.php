<?php

require_once __DIR__ . '/../validasi.php';

function cariUser(PDO $pdo, string $keyword = ''): array
{
    $keyword = trim($keyword);

    if ($keyword === '') {
        $stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    $like = '%' . $keyword . '%';
    $stmt = $pdo->prepare(
        'SELECT * FROM users
         WHERE nama LIKE ? OR username LIKE ? OR role LIKE ?
         ORDER BY created_at DESC'
    );
    $stmt->execute([$like, $like, $like]);

    return $stmt->fetchAll();
}

function getUserById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id_user = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch();
    return $row ?: null;
}

function tambahUser(PDO $pdo, array $data): array
{
    if ($error = validasiUser($data)) {
        return ['success' => false, 'error' => $error];
    }

    $nama     = trim($data['nama']);
    $username = trim($data['username']);
    $password = $data['password'];
    $role     = $data['role'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
    $stmt->execute([$username]);

    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'Username sudah digunakan.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nama, $username, $hash, $role]);

    return ['success' => true, 'message' => 'Pengguna berhasil ditambahkan.'];
}

function editUser(PDO $pdo, array $data): array
{
    $id = (int) ($data['id_user'] ?? 0);

    if ($error = validasiUser($data, true)) {
        return ['success' => false, 'error' => $error];
    }

    if (!getUserById($pdo, $id)) {
        return ['success' => false, 'error' => 'Pengguna tidak ditemukan.'];
    }

    $nama     = trim($data['nama']);
    $username = trim($data['username']);
    $password = $data['password'] ?? '';
    $role     = $data['role'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? AND id_user != ?');
    $stmt->execute([$username, $id]);

    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'Username sudah digunakan.'];
    }

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET nama = ?, username = ?, password = ?, role = ? WHERE id_user = ?');
        $stmt->execute([$nama, $username, $hash, $role, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET nama = ?, username = ?, role = ? WHERE id_user = ?');
        $stmt->execute([$nama, $username, $role, $id]);
    }

    return ['success' => true, 'message' => 'Pengguna berhasil diperbarui.'];
}

function hapusUser(PDO $pdo, int $id, int $currentUserId): array
{
    if ($error = validasiIdPositif($id)) {
        return ['success' => false, 'error' => $error];
    }

    if ($id === $currentUserId) {
        return ['success' => false, 'error' => 'Tidak dapat menghapus akun yang sedang aktif.'];
    }

    if (!getUserById($pdo, $id)) {
        return ['success' => false, 'error' => 'Pengguna tidak ditemukan.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_masuk WHERE id_user = ?');
    $stmt->execute([$id]);
    $masuk = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_keluar WHERE id_user = ?');
    $stmt->execute([$id]);
    $keluar = $stmt->fetchColumn();

    if ($masuk > 0 || $keluar > 0) {
        return ['success' => false, 'error' => 'Pengguna tidak dapat dihapus karena memiliki riwayat transaksi.'];
    }

    $stmt = $pdo->prepare('DELETE FROM users WHERE id_user = ?');
    $stmt->execute([$id]);

    return ['success' => true, 'message' => 'Pengguna berhasil dihapus.'];
}

function hitungTotalUser(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
}

function hitungTotalKategori(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM kategori')->fetchColumn();
}
