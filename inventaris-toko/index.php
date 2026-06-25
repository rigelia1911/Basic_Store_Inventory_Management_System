<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Toko</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="landing">
        <header class="landing-header">
            <div class="landing-logo">Inventaris Toko</div>
            <a href="auth/login.php" class="btn btn-primary">Masuk</a>
            <a href="auth/register.php" class="btn btn-secondary" style="margin-left:0.5rem;">Daftar</a>
        </header>

        <section class="landing-hero">
            <h1>Kelola Inventaris Toko dengan Mudah</h1>
            <p>
                Sistem manajemen inventaris sederhana untuk mencatat produk,
                stok barang masuk dan keluar secara efisien.
            </p>
            <a href="auth/login.php" class="btn btn-primary">Mulai Sekarang</a>

            <div class="landing-features">
                <div class="feature-card">
                    <h3>Manajemen Produk</h3>
                    <p>Kelola data produk, kategori, harga, dan stok barang.</p>
                </div>
                <div class="feature-card">
                    <h3>Transaksi Masuk</h3>
                    <p>Catat barang yang masuk dari supplier ke gudang.</p>
                </div>
                <div class="feature-card">
                    <h3>Transaksi Keluar</h3>
                    <p>Pantau barang yang keluar saat penjualan.</p>
                </div>
            </div>
        </section>

        <footer class="landing-footer">
            &copy; <?= date('Y') ?> Inventaris Toko. Sistem Manajemen Inventaris Sederhana.
        </footer>
    </div>
</body>
</html>
