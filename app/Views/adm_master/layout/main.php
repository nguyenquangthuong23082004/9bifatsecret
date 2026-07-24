<!DOCTYPE HTML>
<html lang="ko">

<head>
    <?php 
    $site_title = !empty($_settings['title']) ? $_settings['title'] : '오토스타일';
    $favicon = !empty($_settings['favico']) ? base_url('uploads/setting/' . $_settings['favico']) : base_url('favicon.ico');
    ?>
    <title><?= $title ?? 'Admin Panel' ?> - <?= $site_title ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">

    <!-- Legacy CSS -->
    <link rel="stylesheet" href="<?= base_url('adm_assets/_common/css/import.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('adm_assets/_common/css/pop.css') ?>" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Summernote BS5 -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">

    <!-- Legacy Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-ko-KR.min.js"></script>

    <link rel="stylesheet" href="<?= base_url('adm_assets/_common/css/style_mazar.css') ?>" type="text/css" />

    <script>
        // Legacy PopUp functions
        function PopUp(url, wName, width, height) {
            var LeftPosition = (screen.width / 2) - (width / 2);
            var TopPosition = (screen.height / 2) - (height / 2);
            var win = window.open(url, wName, "left=" + LeftPosition + ",top=" + TopPosition + ",width=" + width + ",height=" + height);
            if (win == null) { alert("팝업차단을 해제해주세요!"); } else { win.focus(); }
        }

        function PopUpWithScroll(url, wName, width, height) {
            var LeftPosition = (screen.width / 2) - (width / 2);
            var TopPosition = (screen.height / 2) - (height / 2);
            var win = window.open(url, wName, "left=" + LeftPosition + ",top=" + TopPosition + ",width=" + width + ",height=" + height + ",scrollbars=yes");
            if (win == null) { alert("팝업차단을 해제해주세요!"); } else { win.focus(); }
        }

        // Printing functions
        var printpp;
        function bp() { printpp = document.body.innerHTML; document.body.innerHTML = document.getElementById('print_this').innerHTML; }
        function ap() { document.body.innerHTML = printpp; }
        function pp() { window.print(); }
    </script>
</head>

<body>
    <div id="ajax_loader" class="wrap-loading display-none">
        <div><img src="<?= base_url('adm_assets/_images/common/ajax-loader.gif') ?>" /></div>
    </div>

    <div id="wrap">
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm border-bottom">
            <div class="container-fluid">
                <button class="btn btn-link text-dark me-2 d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <!-- Removed navbar brand -->

                <div class="d-flex align-items-center ms-3">
                    <a href="<?= base_url() ?>" target="_blank"
                        class="btn btn-primary btn-sm px-3 shadow-sm text-white">
                        <i class="bi bi-house-door me-1"></i> 홈페이지 바로가기
                    </a>
                </div>

                <div class="ms-auto d-flex align-items-center text-dark">
                    <span class="me-3 d-none d-sm-inline small">
                        <i class="bi bi-person-circle me-1 text-primary"></i>
                        <strong><?= session()->get('member')['name'] ?? '관리자' ?></strong>님 접속중
                    </span>
                    <a href="<?= base_url('AdmMaster/logout') ?>"
                        class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-2">
                        <i class="bi bi-box-arrow-right me-1"></i> 로그아웃
                    </a>
                </div>
            </div>
        </nav>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar shadow" id="sidebar">
            <div class="sidebar-brand border-bottom mb-3 px-3">
                <a href="<?= base_url('AdmMaster') ?>" class="d-flex align-items-center justify-content-center">
                    <img src="<?= !empty($_settings['logos']) ? base_url('uploads/setting/' . $_settings['logos']) : base_url('images/header_logo.png') ?>" alt="AUTOSTYLE" style="max-height: 38px; width: auto; display: block;">
                </a>
            </div>
            <div class="py-0">
                <?php
                $uri = service('uri');
                $path = $uri->getPath();

                // Helper to check if current path matches segments
                $isActive = function ($segment) use ($uri) {
                    return strpos($uri->getPath(), $segment) !== false;
                };

                // Improved check to include sub-paths (form, view, etc.)
                $isPath = function ($target) use ($uri) {
                    $currentPath = trim($uri->getPath(), '/');
                    $targetPath = trim($target, '/');
                    return $currentPath == $targetPath || strpos($currentPath, $targetPath . '/') === 0;
                };

                // Define specific active states
                $isNotice = $isPath('AdmMaster/bbs/notice');
                $isNoticeEn = $isPath('AdmMaster/bbs/notice_en');
                $isNews = $isPath('AdmMaster/bbs/news');
                $isNewsEn = $isPath('AdmMaster/bbs/news_en');
                $isBanners = $isActive('AdmMaster/banners');
                $isLineCard = $isPath('AdmMaster/line_card');
                $isBbsParent = $isNotice || $isNoticeEn || $isNews || $isNewsEn || $isBanners || $isLineCard;

                $isInquiry1 = $isPath('AdmMaster/inquiry/1');
                $isInquiry2 = $isPath('AdmMaster/inquiry/2');
                $isInquiryParent = $isInquiry1 || $isInquiry2;

                $isAppList = $isActive('AdmMaster/bbs/app');
                $isCategory = $isActive('AdmMaster/category');
                $isGoods = $isActive('AdmMaster/goods');
                $isAppParent = $isAppList || $isCategory || $isGoods;

                $isPopups = $isActive('AdmMaster/popups');
                $isProfile = $isActive('AdmMaster/profile');
                $isFaq = $isPath('AdmMaster/bbs/faq');
                $isPolicy = $isPath('AdmMaster/bbs/policy');
                $isBoardParent = $isInquiry1 || $isFaq;
                ?>
                <div class="nav flex-column" id="sidebarAccordion">
                    <!-- 게시판 관리 Section -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle <?= $isBoardParent ? 'active' : '' ?>" href="#menuBoard"
                             data-bs-toggle="collapse" role="button"
                             aria-expanded="<?= $isBoardParent ? 'true' : 'false' ?>" aria-controls="menuBoard">
                            <i class="bi bi-chat-dots"></i> 게시판 관리
                        </a>
                        <div class="collapse <?= $isBoardParent ? 'show' : '' ?> submenu" id="menuBoard"
                             data-bs-parent="#sidebarAccordion">
                             <div class="nav flex-column">
                                 <a class="nav-link <?= $isInquiry1 ? 'active' : '' ?>"
                                     href="<?= base_url('AdmMaster/inquiry/1') ?>">문의관리</a>
                                 <a class="nav-link <?= $isFaq ? 'active' : '' ?>"
                                     href="<?= base_url('AdmMaster/bbs/faq') ?>">FAQ관리</a>
                             </div>
                        </div>
                    </div>

                    <!-- 팝업관리 -->
                    <div class="nav-item">
                        <a class="nav-link <?= $isPopups ? 'active' : '' ?>" href="<?= base_url('AdmMaster/popups') ?>">
                            <i class="bi bi-window"></i> 팝업관리
                        </a>
                    </div>

                    <!-- 약관/방침 관리 -->
                    <div class="nav-item">
                        <a class="nav-link <?= $isPolicy ? 'active' : '' ?>" href="<?= base_url('AdmMaster/bbs/policy') ?>">
                            <i class="bi bi-file-earmark-text"></i> 약관/방침 관리
                        </a>
                    </div>

                    <!-- 관리자정보 -->
                    <div class="nav-item border-top mt-2 pt-2 border-light">
                        <a class="nav-link <?= $isProfile ? 'active' : '' ?>"
                            href="<?= base_url('AdmMaster/profile') ?>">
                            <i class="bi bi-person-gear"></i> 관리자정보
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <div id="container">
            <span id="print_this">
                <header id="headerContainer">
                    <div class="inner">
                        <h2><?= $title ?? '관리자 패널' ?></h2>
                        <div class="menus">
                            <?= $this->renderSection('header_buttons') ?>
                        </div>
                    </div>
                </header>

                <div id="contents">
                    <?= $this->renderSection('content') ?>
                </div>
            </span>
        </div>

        <footer id="footer">
            <p class="tel"></p>
        </footer>
    </div>

    <script type="text/javascript" src="<?= base_url('adm_assets/_common/js/jquery.easing.min.js') ?>"
        charset="utf-8"></script>
    <script type="text/javascript" src="<?= base_url('adm_assets/_common/js/common.js') ?>" charset="utf-8"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function alert_(msg) {
            alert(msg);
        }

        $(document).ready(function () {
            $('#sidebarToggle, #sidebarOverlay').on('click', function () {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });
        });
    </script>
</body>

</html>
