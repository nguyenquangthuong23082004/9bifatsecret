<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="<?= esc($metaDescription ?? '부산 프리미엄 체험관리 다이어트 전문 센터 비티엘. 19년 노하우, BTL 전문가와 1:1 맞춤 체형관리.') ?>">
    <meta property="og:title" content="<?= esc($metaTitle ?? '비티엘 다이어트 (BITIEL)') ?>">
    <meta property="og:description" content="<?= esc($metaDescription ?? '부산 프리미엄 체험관리 다이어트 전문 센터') ?>">
    <meta property="og:image" content="<?= esc($ogImage ?? base_url('assets/images/btl/main-visual.webp')) ?>">
    <meta property="og:type" content="website">
    <title><?= esc($metaTitle ?? '비티엘 다이어트 (BITIEL)') ?></title>

    <!-- Bootstrap 5 (reboot + grid utilities) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Landing styles (modular; common first, then header/footer + sections).
         For production these can be concatenated/minified into one file. -->
    <?php
    $btlCss = [
        'common', 'header', 'footer',
        'main-visual', 'philosophy', 'career', 'program', 'dual',
        'ticket', 'consult', 'yoyo', 'features', 'menopause',
    ];
    foreach ($btlCss as $css): ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/btl/' . $css . '.css') ?>">
    <?php endforeach; ?>
</head>
