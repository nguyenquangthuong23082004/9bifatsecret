<?php
/* 16. Site footer – Figma mobile 529:900 (logo) + 529:901 (business info).

   Bố cục theo đúng design: 3 dòng
     1) 사이트명 · 대표
     2) 주소 · 전화 · 사업자등록번호
     3) copyright
   Khoảng cách giữa các nhóm do CSS (column-gap, đơn vị em) đảm nhiệm thay cho
   khoảng trắng cứng, nên PC (16px) và mobile (14px) đều giãn đúng tỉ lệ.
   Dòng 2 dùng flex-wrap: khi hẹp, 사업자등록번호 tự xuống dòng như bản mobile. */

$telHref = !empty($_settings['custom_phone'])
    ? preg_replace('/[^0-9]/', '', $_settings['custom_phone'])
    : '';
?>
<!-- 16. Footer -->
<footer class="site-footer">
    <?php if (!empty($_settings['logos_footer'])): ?>
        <img class="site-footer__logo" src="<?= base_url('uploads/setting/' . $_settings['logos_footer']) ?>" alt="비티엘 플러스">
    <?php else: ?>
        <img class="site-footer__logo" src="<?= base_url('assets/images/btl/logo-footer.png') ?>" alt="비티엘 플러스" width="50" height="21">
    <?php endif; ?>
    <div class="site-footer__info">
        <p class="site-footer__row site-footer__row--name">
            <?php if (!empty($_settings['og_site'])): ?>
                <span class="site-footer__item"><?= esc($_settings['og_site']) ?></span>
            <?php endif; ?>
            <?php if (!empty($_settings['com_owner'])): ?>
                <span class="site-footer__item">대표 : <?= esc($_settings['com_owner']) ?></span>
            <?php endif; ?>
        </p>

        <p class="site-footer__row site-footer__row--biz">
            <?php if (!empty($_settings['addr1'])): ?>
                <span class="site-footer__item">주소 : <?= esc($_settings['addr1']) ?></span>
            <?php endif; ?>
            <?php if (!empty($_settings['custom_phone'])): ?>
                <span class="site-footer__item">전화 : <a href="tel:<?= $telHref ?>"><?= esc($_settings['custom_phone']) ?></a></span>
            <?php endif; ?>
            <?php if (!empty($_settings['comnum'])): ?>
                <span class="site-footer__item">사업자등록번호 : <?= esc($_settings['comnum']) ?></span>
            <?php endif; ?>
        </p>

        <?php if (!empty($_settings['copyright'])): ?>
            <p class="site-footer__row site-footer__row--copy"><?= esc($_settings['copyright']) ?></p>
        <?php endif; ?>
    </div>
</footer>