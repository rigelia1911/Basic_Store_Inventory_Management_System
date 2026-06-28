<?php

require_once __DIR__ . '/../validasi.php';
require_once __DIR__ . '/produk.php';

function cariTransaksiMasuk(PDO $pdo, string $keyword = '', ?int $idUser = null): array
{
    $keyword = trim($keyword);
    $sql = 'SELECT tm.*, p.nama_produk, p.kode_produk, u.nama AS nama_user
            FROM transaksi_masuk tm
            JOIN produk p ON tm.id_produk = p.id_produk
            JOIN users u ON tm.id_user = u.id_user';
    $params = [];
    $conditions = [];

    if ($idUser !== null) {
        $conditions[] = 'tm.id_user = ?';
        $params[] = $idUser;
    }

    if ($keyword !== '') {
        $conditions[] = '(p.nama_produk LIKE ? OR p.kode_produk LIKE ? OR u.nama LIKE ?)';
        $like = '%' . $keyword . '%';
        array_push($params, $like, $like, $like);
    }

    if ($conditions) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY tm.tanggal_masuk DESC, tm.id_masuk DESC';

    if ($idUser !== null && $keyword === '') {
        $sql .= ' LIMIT 20';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function cariTransaksiKeluar(PDO $pdo, string $keyword = '', ?int $idUser = null): array
{
    $keyword = trim($keyword);
    $sql = 'SELECT tk.*, p.nama_produk, p.kode_produk, u.nama AS nama_user
            FROM transaksi_keluar tk
            JOIN produk p ON tk.id_produk = p.id_produk
            JOIN users u ON tk.id_user = u.id_user';
    $params = [];
    $conditions = [];

    if ($idUser !== null) {
        $conditions[] = 'tk.id_user = ?';
        $params[] = $idUser;
    }

    if ($keyword !== '') {
        $conditions[] = '(p.nama_produk LIKE ? OR p.kode_produk LIKE ? OR u.nama LIKE ?)';
        $like = '%' . $keyword . '%';
        array_push($params, $like, $like, $like);
    }

    if ($conditions) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY tk.tanggal_keluar DESC, tk.id_keluar DESC';

    if ($idUser !== null && $keyword === '') {
        $sql .= ' LIMIT 20';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function tambahBarangMasuk(PDO $pdo, array $data, int $idUser): array
{
    if ($error = validasiBarangMasuk($data)) {
        return ['success' => false, 'error' => $error];
    }

    $idProduk = (int) $data['id_produk'];
    $tanggal  = $data['tanggal_masuk'];
    $jumlah   = (int) $data['jumlah_masuk'];

    if (!getProdukById($pdo, $idProduk)) {
        return ['success' => false, 'error' => 'Produk tidak ditemukan.'];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO transaksi_masuk (id_produk, id_user, tanggal_masuk, jumlah_masuk)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$idProduk, $idUser, $tanggal, $jumlah]);

        $stmt = $pdo->prepare('UPDATE produk SET stok = stok + ? WHERE id_produk = ?');
        $stmt->execute([$jumlah, $idProduk]);

        $pdo->commit();

        return ['success' => true, 'message' => 'Transaksi barang masuk berhasil dicatat.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()];
    }
}

function tambahBarangKeluar(PDO $pdo, array $data, int $idUser): array
{
    if ($error = validasiBarangKeluar($data)) {
        return ['success' => false, 'error' => $error];
    }

    $idProduk = (int) $data['id_produk'];
    $tanggal  = $data['tanggal_keluar'];
    $jumlah   = (int) $data['jumlah_keluar'];

    $produk = getProdukById($pdo, $idProduk);
    if (!$produk) {
        return ['success' => false, 'error' => 'Produk tidak ditemukan.'];
    }

    if ($produk['stok'] < $jumlah) {
        return [
            'success' => false,
            'error'   => 'Stok tidak mencukupi. Stok tersedia: ' . $produk['stok'],
        ];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO transaksi_keluar (id_produk, id_user, tanggal_keluar, jumlah_keluar)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$idProduk, $idUser, $tanggal, $jumlah]);

        $stmt = $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');
        $stmt->execute([$jumlah, $idProduk]);

        $pdo->commit();

        return ['success' => true, 'message' => 'Transaksi barang keluar berhasil dicatat.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()];
    }
}

function hapusBarangMasuk(PDO $pdo, int $id): array
{
    if ($error = validasiIdPositif($id)) {
        return ['success' => false, 'error' => $error];
    }

    $stmt = $pdo->prepare('SELECT * FROM transaksi_masuk WHERE id_masuk = ?');
    $stmt->execute([$id]);
    $transaksi = $stmt->fetch();

    if (!$transaksi) {
        return ['success' => false, 'error' => 'Transaksi tidak ditemukan.'];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('DELETE FROM transaksi_masuk WHERE id_masuk = ?');
        $stmt->execute([$id]);

        $stmt = $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');
        $stmt->execute([$transaksi['jumlah_masuk'], $transaksi['id_produk']]);

        $pdo->commit();

        return ['success' => true, 'message' => 'Transaksi barang masuk berhasil dihapus.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => 'Gagal menghapus transaksi: ' . $e->getMessage()];
    }
}

function hapusBarangKeluar(PDO $pdo, int $id): array
{
    if ($error = validasiIdPositif($id)) {
        return ['success' => false, 'error' => $error];
    }

    $stmt = $pdo->prepare('SELECT * FROM transaksi_keluar WHERE id_keluar = ?');
    $stmt->execute([$id]);
    $transaksi = $stmt->fetch();

    if (!$transaksi) {
        return ['success' => false, 'error' => 'Transaksi tidak ditemukan.'];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('DELETE FROM transaksi_keluar WHERE id_keluar = ?');
        $stmt->execute([$id]);

        $stmt = $pdo->prepare('UPDATE produk SET stok = stok + ? WHERE id_produk = ?');
        $stmt->execute([$transaksi['jumlah_keluar'], $transaksi['id_produk']]);

        $pdo->commit();

        return ['success' => true, 'message' => 'Transaksi barang keluar berhasil dihapus.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => 'Gagal menghapus transaksi: ' . $e->getMessage()];
    }
}

function hitungBarangMasukHariIni(PDO $pdo, ?int $idUser = null): int
{
    if ($idUser === null) {
        return (int) $pdo->query(
            "SELECT COALESCE(SUM(jumlah_masuk), 0) FROM transaksi_masuk WHERE tanggal_masuk = CURDATE()"
        )->fetchColumn();
    }

    $stmt = $pdo->prepare(
        'SELECT COALESCE(SUM(jumlah_masuk), 0) FROM transaksi_masuk WHERE id_user = ? AND tanggal_masuk = CURDATE()'
    );
    $stmt->execute([$idUser]);

    return (int) $stmt->fetchColumn();
}

function hitungBarangKeluarHariIni(PDO $pdo, ?int $idUser = null): int
{
    if ($idUser === null) {
        return (int) $pdo->query(
            "SELECT COALESCE(SUM(jumlah_keluar), 0) FROM transaksi_keluar WHERE tanggal_keluar = CURDATE()"
        )->fetchColumn();
    }

    $stmt = $pdo->prepare(
        'SELECT COALESCE(SUM(jumlah_keluar), 0) FROM transaksi_keluar WHERE id_user = ? AND tanggal_keluar = CURDATE()'
    );
    $stmt->execute([$idUser]);

    return (int) $stmt->fetchColumn();
}
