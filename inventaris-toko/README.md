Tugas Akhir Mata Kuliah 

Pemrograman Sisi Back-End
# Inventaris Toko

Sistem manajemen inventaris toko sederhana menggunakan HTML5, CSS, dan Native PHP.

## Persyaratan

- PHP 8.0+
- MySQL / MariaDB
- Web server (XAMPP, Laragon, WAMP, dll.)

## Instalasi

1. Salin folder `inventaris-toko` ke direktori web server (misalnya `htdocs` atau `www`).
2. Import database dengan menjalankan file `database.sql` di phpMyAdmin atau MySQL CLI.
3. Sesuaikan konfigurasi di `config/database.php` jika diperlukan.
4. Buka browser: `http://localhost/inventaris-toko/`

## Akun Default

| Username | Password | Role  |
|----------|----------|-------|
| admin    | password | admin |
| kasir    | password | user  |

## Fitur Umum

- **Landing page** — halaman beranda sebelum login
- **Login/Logout** — autentikasi berbasis session
- **Admin** — CRUD kategori, produk, pengguna; transaksi barang masuk/keluar
- **User** — lihat produk, catat barang masuk/keluar
- **Stok otomatis** — stok produk terupdate saat transaksi masuk/keluar


