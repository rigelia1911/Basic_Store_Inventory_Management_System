<?php

define('PRODUK_UPLOAD_DIR', __DIR__ . '/../assets/images/produk/');
define('PRODUK_UPLOAD_MAX_BYTES', 2 * 1024 * 1024);
define('PRODUK_ALLOWED_TYPES', [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
]);

function produkHasImage(?string $path): bool
{
    return $path && file_exists(__DIR__ . '/../assets/images/' . $path);
}

function produkImageUrl(?string $path): ?string
{
    if (produkHasImage($path)) {
        return assetUrl('images/' . $path);
    }
    return null;
}

function uploadProdukImage(array $file, bool $required = true): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            $_SESSION['flash_error'] = 'Gambar produk wajib diunggah.';
        }
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        $_SESSION['flash_error'] = 'Gagal mengunggah gambar produk.';
        return null;
    }

    if (($file['size'] ?? 0) > PRODUK_UPLOAD_MAX_BYTES) {
        $_SESSION['flash_error'] = 'Ukuran gambar maksimal 2 MB.';
        return null;
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        $_SESSION['flash_error'] = 'File yang diunggah bukan gambar yang valid.';
        return null;
    }

    $mime = $info['mime'] ?? mime_content_type($file['tmp_name']);
    if (!isset(PRODUK_ALLOWED_TYPES[$mime])) {
        $_SESSION['flash_error'] = 'Format gambar harus JPG, PNG, GIF, atau WEBP.';
        return null;
    }

    if (!is_dir(PRODUK_UPLOAD_DIR)) {
        mkdir(PRODUK_UPLOAD_DIR, 0755, true);
    }

    $filename = uniqid('produk_', true) . '.' . PRODUK_ALLOWED_TYPES[$mime];
    $destination = PRODUK_UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $_SESSION['flash_error'] = 'Gagal menyimpan gambar produk.';
        return null;
    }

    return 'produk/' . $filename;
}

function deleteProdukImage(?string $path): void
{
    if (!$path) {
        return;
    }

    $fullPath = __DIR__ . '/../assets/images/' . $path;
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}
