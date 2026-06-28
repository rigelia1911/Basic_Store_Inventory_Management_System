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

function uploadFile(
    array $file,
    string $destinationDir,
    string $pathPrefix,
    array $allowedTypes,
    int $maxBytes,
    bool $required = true,
    string $fieldLabel = 'File'
): array {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            return ['success' => false, 'error' => "$fieldLabel wajib diunggah."];
        }

        return ['success' => true, 'path' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => "Gagal mengunggah $fieldLabel."];
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        $maxMb = round($maxBytes / (1024 * 1024), 1);
        return ['success' => false, 'error' => "Ukuran $fieldLabel maksimal {$maxMb} MB."];
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        return ['success' => false, 'error' => 'File yang diunggah bukan gambar yang valid.'];
    }

    $mime = $info['mime'] ?? mime_content_type($file['tmp_name']);
    if (!isset($allowedTypes[$mime])) {
        return ['success' => false, 'error' => 'Format gambar harus JPG, PNG, GIF, atau WEBP.'];
    }

    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $filename = uniqid('produk_', true) . '.' . $allowedTypes[$mime];
    $destination = rtrim($destinationDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => "Gagal menyimpan $fieldLabel."];
    }

    return ['success' => true, 'path' => rtrim($pathPrefix, '/') . '/' . $filename];
}

function uploadProdukImage(array $file, bool $required = true): array
{
    return uploadFile(
        $file,
        PRODUK_UPLOAD_DIR,
        'produk',
        PRODUK_ALLOWED_TYPES,
        PRODUK_UPLOAD_MAX_BYTES,
        $required,
        'Gambar produk'
    );
}

function deleteUploadedFile(?string $path): void
{
    if (!$path) {
        return;
    }

    $fullPath = __DIR__ . '/../assets/images/' . $path;
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}

function deleteProdukImage(?string $path): void
{
    deleteUploadedFile($path);
}
