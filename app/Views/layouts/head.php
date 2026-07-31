<head>
    <?php
    $site_title = $metaTitle ?? $_settings['browser_title'] ?? '';
    $site_description = $metaDescription ?? $_settings['meta_tag'] ?? '';
    $site_keywords = $_settings['meta_keyword'] ?? '';
    $site_og_title = $_settings['og_title'] ?? $site_title;
    $site_og_des = $_settings['og_des'] ?? $site_description;
    
    $site_og_image = '';
    if (!empty($ogImage)) {
        $site_og_image = $ogImage;
    } elseif (!empty($_settings['og_img'])) {
        $site_og_image = base_url('uploads/setting/' . $_settings['og_img']);
    }
    
    $site_og_url = $_settings['og_url'] ?? '';
    $site_og_site = $_settings['og_site'] ?? '';
    $favicon = !empty($_settings['favico']) ? base_url('uploads/setting/' . $_settings['favico']) : base_url('favicon.ico');
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    
    <?php if (!empty($site_description)): ?>
    <meta name="description" content="<?= esc($site_description) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_keywords)): ?>
    <meta name="keywords" content="<?= esc($site_keywords) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_og_title)): ?>
    <meta property="og:title" content="<?= esc($site_og_title) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_og_des)): ?>
    <meta property="og:description" content="<?= esc($site_og_des) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_og_image)): ?>
    <meta property="og:image" content="<?= esc($site_og_image) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_og_url)): ?>
    <meta property="og:url" content="<?= esc($site_og_url) ?>">
    <?php endif; ?>
    
    <?php if (!empty($site_og_site)): ?>
    <meta property="og:site_name" content="<?= esc($site_og_site) ?>">
    <?php endif; ?>
    
    <meta property="og:type" content="website">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">
    
    <?php if (!empty($site_title)): ?>
    <title><?= esc($site_title) ?></title>
    <?php endif; ?>
    
    <?php if (!empty($_settings['schema_jsonld'])): ?>
    <?= $_settings['schema_jsonld'] ?>
    <?php endif; ?>

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
        'thermo', 'results', 'turn', 'faq', 'contact', 'quickbar',
        'pc', // luôn để cuối: ghi đè toàn bộ section cho màn >= 992px
    ];
    foreach ($btlCss as $css):
        $cssPath = FCPATH . 'assets/css/btl/' . $css . '.css';
        $cssVer  = is_file($cssPath) ? filemtime($cssPath) : null; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/btl/' . $css . '.css') . ($cssVer ? '?v=' . $cssVer : '') ?>">
    <?php endforeach; ?>
</head>
