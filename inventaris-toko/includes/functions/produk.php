<?php

require_once __DIR__ . '/../validasi.php';
require_once __DIR__ . '/../upload_produk.php';

function escapeLikeKeyword(string $keyword): string
{
    return strtr($keyword, [
        '\\' => '\\\\',
        '%'  => '\\%',
        '_'  => '\\_',
    ]);
}

function cariProduk(PDO $pdo, string $keyword = ''): array
{
    $keyword = trim($keyword);
    $sql = 'SELECT p.*, k.nama_kategori
            FROM produk p
            JOIN kategori k ON p.id_kategori = k.id_kategori';

    if ($keyword === '') {
        $sql .= ' ORDER BY p.nama_produk';
        return $pdo->query($sql)->fetchAll();
    }

    $sql .= " WHERE p.nama_produk LIKE ? ESCAPE '\\\\'
              OR p.kode_produk LIKE ? ESCAPE '\\\\'
              OR k.nama_kategori LIKE ? ESCAPE '\\\\'
              ORDER BY p.nama_produk";
    $like = '%' . escapeLikeKeyword($keyword) . '%';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$like, $like, $like]);

    return $stmt->fetchAll();
}

function getProdukById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM produk WHERE id_produk = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch();
    return $row ?: null;
}

function getProdukUntukTransaksi(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id_produk, nama_produk, kode_produk, stok FROM produk ORDER BY nama_produk');
    return $stmt->fetchAll();
}

function tambahProduk(PDO $pdo, array $data, array $file = []): array
{
    if ($error = validasiProduk($data)) {
        return ['success' => false, 'error' => $error];
    }

    $upload = uploadProdukImage($file, true);
    if (!$upload['success']) {
        return $upload;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO produk (id_kategori, nama_produk, kode_produk, harga_beli, harga_jual, stok, path, deskripsi)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        (int) $data['id_kategori'],
        trim($data['nama_produk']),
        trim($data['kode_produk'] ?? '') ?: null,
        (float) $data['harga_beli'],
        (float) $data['harga_jual'],
        (int) $data['stok'],
        $upload['path'],
        trim($data['deskripsi'] ?? '') ?: null,
    ]);

    return ['success' => true, 'message' => 'Produk berhasil ditambahkan.'];
}

function editProduk(PDO $pdo, array $data, array $file = []): array
{
    $id = (int) ($data['id_produk'] ?? 0);

    if ($error = validasiProduk($data, true)) {
        return ['success' => false, 'error' => $error];
    }

    $existing = getProdukById($pdo, $id);
    if (!$existing) {
        return ['success' => false, 'error' => 'Produk tidak ditemukan.'];
    }

    $path = $existing['path'];
    $hasNewImage = isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE;

    if ($hasNewImage) {
        $upload = uploadProdukImage($file, false);
        if (!$upload['success']) {
            return $upload;
        }

        deleteProdukImage($path);
        $path = $upload['path'];
    }

    $stmt = $pdo->prepare(
        'UPDATE produk SET id_kategori = ?, nama_produk = ?, kode_produk = ?, harga_beli = ?,
         harga_jual = ?, stok = ?, path = ?, deskripsi = ? WHERE id_produk = ?'
    );
    $stmt->execute([
        (int) $data['id_kategori'],
        trim($data['nama_produk']),
        trim($data['kode_produk'] ?? '') ?: null,
        (float) $data['harga_beli'],
        (float) $data['harga_jual'],
        (int) $data['stok'],
        $path,
        trim($data['deskripsi'] ?? '') ?: null,
        $id,
    ]);

    return ['success' => true, 'message' => 'Produk berhasil diperbarui.'];
}

function hapusProduk(PDO $pdo, int $id): array
{
    if ($error = validasiIdPositif($id)) {
        return ['success' => false, 'error' => $error];
    }

    $produk = getProdukById($pdo, $id);
    if (!$produk) {
        return ['success' => false, 'error' => 'Produk tidak ditemukan.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_masuk WHERE id_produk = ?');
    $stmt->execute([$id]);
    $masuk = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi_keluar WHERE id_produk = ?');
    $stmt->execute([$id]);
    $keluar = $stmt->fetchColumn();

    if ($masuk > 0 || $keluar > 0) {
        return ['success' => false, 'error' => 'Produk tidak dapat dihapus karena memiliki riwayat transaksi.'];
    }

    deleteProdukImage($produk['path']);

    $stmt = $pdo->prepare('DELETE FROM produk WHERE id_produk = ?');
    $stmt->execute([$id]);

    return ['success' => true, 'message' => 'Produk berhasil dihapus.'];
}

function getProdukStokRendah(PDO $pdo, int $limit = 5): array
{
    $stmt = $pdo->prepare(
        'SELECT p.nama_produk, p.stok, k.nama_kategori
         FROM produk p
         JOIN kategori k ON p.id_kategori = k.id_kategori
         WHERE p.stok <= 5
         ORDER BY p.stok ASC
         LIMIT ?'
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function hitungTotalProduk(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
}

function hitungProdukStokRendah(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM produk WHERE stok <= 5')->fetchColumn();
}
