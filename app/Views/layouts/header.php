<?php
/* GNB – Figma 457:2 (closed) / 529:185 (open drawer).
   Open state = full-screen white overlay under the 50px bar, 11 centred
   links (18px, gap 30). 실시간상담 is struck through in the design. */
$menu = [
    ['ABOUT',          '#philosophy'],
    ['프로그램',        '#program'],
    ['듀얼솔루션',      '#dual'],
    ['체험권',          '#ticket'],
    // ['실시간상담',      '#consult', true],   // struck through (529:208)
    ['요요 ZERO',       '#yoyo'],
    ['갱년기 프로그램', '#menopause'],
    ['체온다이어트',    '#thermo'],
    ['비포&애프터',     '#results'],
    ['FAQ',            '#faq'],
    ['1대1문의',        '#contact'],
];
?>
<header class="gnb">
  <div class="gnb__container">
    <div class="gnb__inner">
        <a class="gnb__logo" href="<?= base_url() ?>" aria-label="비티엘 홈">
            <img src="<?= base_url('assets/images/btl/logo-bitiel.png') ?>" alt="BITIEL">
        </a>
        <button class="gnb__toggle" type="button" aria-label="메뉴 열기" aria-expanded="false" aria-controls="gnbMenu">
            <!-- 457:37 — 18x14 bars, 2px thick, 4px gaps -->
            <svg class="gnb__icon gnb__icon--open" width="30" height="30" viewBox="0 0 30 30" fill="none" aria-hidden="true">
                <path d="M24 22H6V20H24V22ZM24 16H6V14H24V16ZM24 10H6V8H24V10Z" fill="#252525"/>
            </svg>
            <!-- 529:202 — 16.41x16.26 close cross -->
            <svg class="gnb__icon gnb__icon--close" width="30" height="30" viewBox="0 0 30 30" fill="none" aria-hidden="true">
                <path d="M23.207 8.28223L16.4141 15.0752L23.0566 21.7178L21.6426 23.1318L15 16.4893L8.3584 23.1318L6.94336 21.7178L13.5859 15.0752L6.79297 8.28223L8.20703 6.86816L15 13.6611L21.793 6.86816L23.207 8.28223Z" fill="#252525"/>
            </svg>
        </button>
    </div>

    <nav id="gnbMenu" class="gnb__menu" aria-label="주요 메뉴" hidden>
        <ul class="gnb__list">
            <?php foreach ($menu as $item): ?>
            <li><a href="<?= $item[1] ?>"<?= !empty($item[2]) ? ' class="is-off"' : '' ?>><?= esc($item[0]) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
  </div><!-- /.gnb__container -->
</header>
