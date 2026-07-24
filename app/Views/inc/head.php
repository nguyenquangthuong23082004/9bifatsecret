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
    <style>
        :root { font-family: Inter, system-ui, sans-serif; color:#111; background:#f7fafc; }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin:0; min-height:100vh; line-height:1.5; background:#f7fafc; }
        a { color: inherit; text-decoration: none; }
        .page-wrapper { display:flex; flex-direction:column; min-height:100vh; }
        .site-header { background:#fff; border-bottom:1px solid #e5e7eb; }
        .header-inner { max-width:1200px; margin:0 auto; padding:20px 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
        .site-logo { font-size:1rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; }
        .site-nav { display:flex; gap:18px; flex-wrap:wrap; }
        .site-nav a { font-size:.95rem; color:#374151; }
        .section { max-width:1200px; margin:0 auto; padding:64px 24px; }
        .hero { display:grid; grid-template-columns:1.1fr .9fr; gap:48px; align-items:center; min-height:calc(100vh - 160px); }
        .hero-copy h1 { margin:0 0 20px; font-size:clamp(2.6rem, 4vw, 4.6rem); line-height:1.05; }
        .hero-copy p { margin:0 0 26px; color:#4b5563; font-size:1.05rem; max-width:650px; }
        .cta-group { display:flex; gap:14px; flex-wrap:wrap; }
        .cta-group a { display:inline-flex; align-items:center; justify-content:center; padding:16px 28px; border-radius:999px; font-weight:600; transition: transform .2s ease, box-shadow .2s ease; }
        .cta-primary { background:#2563eb; color:#fff; }
        .cta-secondary { background:#e5e7eb; color:#111; }
        .cta-group a:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(37,99,235,.18); }
        .hero-visual { border-radius:32px; background:linear-gradient(180deg,#eff6ff,#fff); padding:24px; box-shadow:0 24px 72px rgba(15,23,42,.08); }
        .hero-card { min-height:360px; border-radius:24px; background:#fff; display:grid; place-items:center; padding:24px; color:#111; text-align:center; }
        .grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:24px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:24px; padding:28px; box-shadow:0 10px 30px rgba(15,23,42,.04); }
        .card h3 { margin:0 0 16px; font-size:1.15rem; }
        .card p { margin:0; color:#4b5563; }
        .site-footer { background:#111827; color:#f9fafb; padding:32px 24px; text-align:center; }
        .site-footer p { margin:0; font-size:.95rem; color:#9ca3af; }
        @media (max-width:900px) { .hero { grid-template-columns:1fr; } .section { padding:48px 18px; } }
        @media (max-width:640px) { .header-inner { flex-direction:column; align-items:flex-start; } .site-nav { gap:12px; } .hero-card { min-height:260px; } }
    </style>
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
