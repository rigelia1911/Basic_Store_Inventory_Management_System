<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/cek_login.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
header('Location: /inventaris-toko/process/kategori/hapus.php?id=' . $id);
exit;
