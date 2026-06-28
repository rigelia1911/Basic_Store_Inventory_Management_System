<?php

require_once __DIR__ . '/../validasi.php';

function cariKategori(PDO $pdo, string $keyword = ''): array
{
    $keyword = trim($keyword);

    if ($keyword === '') {
        $stmt = $pdo->query(
            'SELECT k.*, COUNT(p.id_produk) AS jumlah_produk
             FROM kategori k
             LEFT JOIN produk p ON k.id_kategori = p.id_kategori
             GROUP BY k.id_kategori
             ORDER BY k.nama_kategori'
        );
        return $stmt->fetchAll();
    }

    $stmt = $pdo->prepare(
        'SELECT k.*, COUNT(p.id_produk) AS jumlah_produk
         FROM kategori k
         LEFT JOIN produk p ON k.id_kategori = p.id_kategori
         WHERE k.nama_kategori LIKE ?
         GROUP BY k.id_kategori
         ORDER BY k.nama_kategori'
    );
    $stmt->execute(['%' . $keyword . '%']);

    return $stmt->fetchAll();
}

function getKategoriById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM kategori WHERE id_kategori = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch();
    return $row ?: null;
}

function getAllKategori(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM kategori ORDER BY nama_kategori');
    return $stmt->fetchAll();
}

function tambahKategori(PDO $pdo, array $data): array
{
    if ($error = validasiKategori($data)) {
        return ['success' => false, 'error' => $error];
    }

    $nama = trim($data['nama_kategori']);
    $stmt = $pdo->prepare('INSERT INTO kategori (nama_kategori) VALUES (?)');
    $stmt->execute([$nama]);

    return ['success' => true, 'message' => 'Kategori berhasil ditambahkan.'];
}

function editKategori(PDO $pdo, array $data): array
{
    $id = (int) ($data['id_kategori'] ?? 0);

    if ($error = validasiIdPositif($id, 'ID kategori')) {
        return ['success' => false, 'error' => $error];
    }

    if ($error = validasiKategori($data)) {
        return ['success' => false, 'error' => $error];
    }

    if (!getKategoriById($pdo, $id)) {
        return ['success' => false, 'error' => 'Kategori tidak ditemukan.'];
    }

    $nama = trim($data['nama_kategori']);
    $stmt = $pdo->prepare('UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?');
    $stmt->execute([$nama, $id]);

    return ['success' => true, 'message' => 'Kategori berhasil diperbarui.'];
}

function hapusKategori(PDO $pdo, int $id): array
{
    if ($error = validasiIdPositif($id)) {
        return ['success' => false, 'error' => $error];
    }

    if (!getKategoriById($pdo, $id)) {
        return ['success' => false, 'error' => 'Kategori tidak ditemukan.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM produk WHERE id_kategori = ?');
    $stmt->execute([$id]);

    if ($stmt->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'Kategori tidak dapat dihapus karena masih memiliki produk.'];
    }

    $stmt = $pdo->prepare('DELETE FROM kategori WHERE id_kategori = ?');
    $stmt->execute([$id]);

    return ['success' => true, 'message' => 'Kategori berhasil dihapus.'];
}
