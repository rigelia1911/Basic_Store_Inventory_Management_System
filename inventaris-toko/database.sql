CREATE DATABASE IF NOT EXISTS inventaris_toko;
USE inventaris_toko;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    nama_produk VARCHAR(150) NOT NULL,
    kode_produk VARCHAR(30) UNIQUE,
    harga_beli DECIMAL(12,2) NOT NULL,
    harga_jual DECIMAL(12,2) NOT NULL,
    stok INT DEFAULT 0,
    path VARCHAR(255) DEFAULT NULL,
    deskripsi TEXT,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE transaksi_masuk (
    id_masuk INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    jumlah_masuk INT NOT NULL,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE transaksi_keluar (
    id_keluar INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    tanggal_keluar DATE NOT NULL,
    jumlah_keluar INT NOT NULL,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Data awal
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Kasir Satu', 'kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO kategori (nama_kategori) VALUES
('Makanan'),
('Minuman'),
('Kebutuhan Rumah');

INSERT INTO produk (id_kategori, nama_produk, kode_produk, harga_beli, harga_jual, stok, deskripsi) VALUES
(1, 'Beras 5kg', 'BR-001', 55000, 65000, 20, 'Beras premium 5 kilogram'),
(2, 'Air Mineral 600ml', 'AM-001', 2500, 4000, 50, 'Air mineral kemasan botol'),
(3, 'Sabun Cuci Piring', 'SC-001', 8000, 12000, 30, 'Sabun cuci piring 400ml');

-- Password default: password
