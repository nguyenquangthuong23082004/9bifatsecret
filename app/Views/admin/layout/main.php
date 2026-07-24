<!DOCTYPE HTML>
<html lang="ko">
<head>
    <title><?= $title ?? 'Admin Panel' ?> - (주)일월세미컴</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('admin/_images/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= base_url('admin/_common/css/import.css') ?>" type="text/css" />
    <script type="text/javascript" src="<?= base_url('admin/_common/js/jquery-1.11.1.min.js') ?>"></script>
    <!--[if lte IE 9]>
    <script src="<?= base_url('admin/_common/js/html5.js') ?>"></script>
    <script src="<?= base_url('admin/_common/js/respond.min.js') ?>"></script>
    <![endif]-->
    <style>
        /* Add some padding for the main content if needed */
        #contents { padding: 20px; }
    </style>
</head>
<body>
    <div id="wrap">
        <header id="header">
            <div class="headerSet">
                <h1>ILWOL <span class="col_yellow">CMS</span></h1>
                <p class="pic"><img src="<?= base_url('admin/_images/logo.png') ?>" alt="" style="height: 40px;" /></p>
                <div class="settings">
                    <p class="config"><?= session()->get('member')['name'] ?>님 접속중 
                        <a href="<?= base_url('admin/logout') ?>" style="margin-left: 10px; color: #fff;">[로그아웃]</a>
                    </p>
                </div>
            </div>

            <div class="gnbWrap">
                <div class="gnbLeft">
                    <div class="top">
                        <ul>
                            <li><a href="<?= base_url('/') ?>" target="_blank">홈페이지 <span class="col">:</span></a></li>
                        </ul>
                    </div>
                </div>

                <div id="gnb">
                    <ul>
                        <li><a href="<?= base_url('admin/dashboard') ?>"><img src="<?= base_url('admin/_images/common/ico_gnb_01.png') ?>" alt="" /> <span class="tit">대시보드</span></a></li>
                        <li><a href="#"><img src="<?= base_url('admin/_images/common/ico_gnb_08.png') ?>" alt="" /> <span class="tit">프론트관리</span></a>
                            <ul class="smenu_8">
                                <li class="fir"><a href="<?= base_url('admin/banners') ?>">메인배너관리</a></li>
                            </ul>
                        </li>
                        <li><a href="#"><img src="<?= base_url('admin/_images/common/ico_gnb_07.png') ?>" alt="" /> <span class="tit">게시판관리</span></a>
                            <ul class="smenu_7">
                                <li class="fir"><a href="<?= base_url('admin/bbs/notice') ?>">공지사항</a></li>
                                <li><a href="<?= base_url('admin/bbs/news') ?>">일월뉴스</a></li>
                                <li class="end"><a href="<?= base_url('admin/inquiry') ?>">온라인문의</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div id="container">
            <header id="headerContainer">
                <div class="inner">
                    <h2><?= $title ?? 'Dashboard' ?></h2>
                </div>
            </header>

            <div id="contents">
                <?= $this->renderSection('content') ?>
            </div>
        </div>

        <footer id="footer">
            <p class="tel">COPYRIGHT ⓒ ILWOLSEMICOM ALL RIGHT RESERVED.</p>
            <p class="btnTop"><a href="#" class="scrollTop"><img src="<?= base_url('admin/_images/common/btn_scrolltop.png') ?>" alt="top" /></a></p>
        </footer>
    </div>

    <script type="text/javascript" src="<?= base_url('admin/_common/js/jquery.easing.min.js') ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?= base_url('admin/_common/js/common.js') ?>" charset="utf-8"></script>
</body>
</html>
