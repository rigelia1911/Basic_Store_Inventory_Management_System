<?php
require_once __DIR__ . '/../auth/cek_login.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Inventaris Toko';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Inventaris Toko</title>
    <link rel="stylesheet" href="<?= assetUrl('css/style.css') ?>">
</head>
<body>
<div class="dashboard">
