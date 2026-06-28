<?php

function isValidEmail(string $value): bool
{
    return $value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidDateString(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $date instanceof DateTimeImmutable && $date->format('Y-m-d') === $value;
}

function isPositiveInteger($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
}

function isNonNegativeInteger($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) !== false;
}

function isPositiveNumber($value): bool
{
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false && (float) $value > 0;
}

function isValidRole(string $role): bool
{
    return in_array($role, ['admin', 'user'], true);
}

function validasiStringWajib(string $value, string $label, int $min, int $max): ?string
{
    if ($value === '') {
        return "$label wajib diisi.";
    }

    $length = strlen($value);
    if ($length < $min || $length > $max) {
        return "$label minimal $min karakter dan maksimal $max karakter.";
    }

    return null;
}

function validasiLogin(string $username, string $password): ?string
{
    if ($username === '' || $password === '') {
        return 'Username dan password wajib diisi.';
    }

    if ($error = validasiStringWajib($username, 'Username', 3, 50)) {
        return $error;
    }

    if (strlen($password) < 6 || strlen($password) > 64) {
        return 'Password minimal 6 karakter dan maksimal 64 karakter.';
    }

    return null;
}

function validasiRegister(array $data): ?string
{
    $nama            = trim($data['nama'] ?? '');
    $username        = trim($data['username'] ?? '');
    $email           = trim($data['email'] ?? '');
    $password        = $data['password'] ?? '';
    $confirmPassword = $data['confirm_password'] ?? '';

    if ($nama === '' || $username === '' || $password === '' || $confirmPassword === '') {
        return 'Semua field wajib diisi.';
    }

    if ($error = validasiStringWajib($nama, 'Nama lengkap', 3, 100)) {
        return $error;
    }

    if ($error = validasiStringWajib($username, 'Username', 3, 50)) {
        return $error;
    }

    if ($email !== '' && !isValidEmail($email)) {
        return 'Format email tidak valid.';
    }

    if (strlen($password) < 6 || strlen($password) > 64) {
        return 'Password minimal 6 karakter dan maksimal 64 karakter.';
    }

    if ($password !== $confirmPassword) {
        return 'Konfirmasi password tidak cocok.';
    }

    return null;
}

function validasiKategori(array $data): ?string
{
    $nama = trim($data['nama_kategori'] ?? '');

    return validasiStringWajib($nama, 'Nama kategori', 3, 100);
}

function validasiProduk(array $data, bool $isEdit = false): ?string
{
    $id_kategori = (int) ($data['id_kategori'] ?? 0);
    $nama        = trim($data['nama_produk'] ?? '');
    $kode        = trim($data['kode_produk'] ?? '');
    $hargaBeli   = (float) ($data['harga_beli'] ?? 0);
    $hargaJual   = (float) ($data['harga_jual'] ?? 0);
    $stok        = (int) ($data['stok'] ?? 0);
    $deskripsi   = trim($data['deskripsi'] ?? '');

    if ($isEdit && (int) ($data['id_produk'] ?? 0) <= 0) {
        return 'ID produk tidak valid.';
    }

    if ($id_kategori <= 0) {
        return 'Kategori produk wajib dipilih.';
    }

    if ($error = validasiStringWajib($nama, 'Nama produk', 3, 100)) {
        return $error;
    }

    if ($kode !== '' && strlen($kode) > 50) {
        return 'Kode produk maksimal 50 karakter.';
    }

    if (!isPositiveNumber($hargaBeli)) {
        return 'Harga beli harus berupa angka positif.';
    }

    if (!isPositiveNumber($hargaJual)) {
        return 'Harga jual harus berupa angka positif.';
    }

    if (!isNonNegativeInteger($stok)) {
        return 'Stok harus berupa angka bulat non-negatif.';
    }

    if ($deskripsi !== '' && strlen($deskripsi) > 500) {
        return 'Deskripsi maksimal 500 karakter.';
    }

    return null;
}

function validasiUser(array $data, bool $isEdit = false): ?string
{
    $nama     = trim($data['nama'] ?? '');
    $username = trim($data['username'] ?? '');
    $password = $data['password'] ?? '';
    $role     = $data['role'] ?? 'user';

    if ($isEdit && (int) ($data['id_user'] ?? 0) <= 0) {
        return 'ID pengguna tidak valid.';
    }

    if ($nama === '' || $username === '') {
        return 'Nama dan username wajib diisi.';
    }

    if ($error = validasiStringWajib($nama, 'Nama lengkap', 3, 100)) {
        return $error;
    }

    if ($error = validasiStringWajib($username, 'Username', 3, 50)) {
        return $error;
    }

    if (!$isEdit && $password === '') {
        return 'Nama, username, dan password wajib diisi.';
    }

    if ($password !== '' && (strlen($password) < 6 || strlen($password) > 64)) {
        return 'Password minimal 6 karakter dan maksimal 64 karakter.';
    }

    if (!isValidRole($role)) {
        return 'Role tidak valid.';
    }

    return null;
}

function validasiBarangMasuk(array $data): ?string
{
    $idProduk = (int) ($data['id_produk'] ?? 0);
    $tanggal  = $data['tanggal_masuk'] ?? '';
    $jumlah   = (int) ($data['jumlah_masuk'] ?? 0);

    if ($idProduk <= 0) {
        return 'Produk wajib dipilih.';
    }

    if ($tanggal === '' || !isValidDateString($tanggal)) {
        return 'Tanggal masuk wajib diisi dengan format YYYY-MM-DD yang valid.';
    }

    if (!isPositiveInteger($jumlah)) {
        return 'Jumlah masuk harus berupa angka bulat positif.';
    }

    return null;
}

function validasiBarangKeluar(array $data): ?string
{
    $idProduk = (int) ($data['id_produk'] ?? 0);
    $tanggal  = $data['tanggal_keluar'] ?? '';
    $jumlah   = (int) ($data['jumlah_keluar'] ?? 0);

    if ($idProduk <= 0) {
        return 'Produk wajib dipilih.';
    }

    if ($tanggal === '' || !isValidDateString($tanggal)) {
        return 'Tanggal keluar wajib diisi dengan format YYYY-MM-DD yang valid.';
    }

    if (!isPositiveInteger($jumlah)) {
        return 'Jumlah keluar harus berupa angka bulat positif.';
    }

    return null;
}

function validasiIdPositif(int $id, string $label = 'ID'): ?string
{
    if ($id <= 0) {
        return "$label tidak valid.";
    }

    return null;
}
