<!DOCTYPE html>
<html lang="<?= esc(service('request')->getLocale() ?: 'en') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($metaDescription ?? 'A clean landing page.') ?>">
    <meta property="og:title" content="<?= esc($metaTitle ?? 'Landing Page') ?>">
    <meta property="og:description" content="<?= esc($metaDescription ?? 'A clean landing page.') ?>">
    <meta property="og:image" content="<?= esc($ogImage ?? base_url('favicon.ico')) ?>">
    <meta property="og:type" content="website">
    <title><?= esc($metaTitle ?? 'Landing Page') ?></title>
</head>
<body>
    <div class="page-wrapper">
        <?= $this->include('inc/header') ?>
        <main class="page-main">
            <?= $this->renderSection('content') ?>
        </main>
        <?= $this->include('inc/footer') ?>
    </div>
</body>
</html>
