<?php
/* 16. Site footer – Figma 529:183 (logo) + 529:184 (business info).
   Multiple spaces between the address/phone/reg-no groups are intentional
   (the Figma text uses them as separators) — kept via white-space:pre-wrap. */
?>
<!-- 16. Footer -->
<footer class="site-footer">
    <?php if (!empty($_settings['logos_footer'])): ?>
        <img class="site-footer__logo" src="<?= base_url('uploads/setting/' . $_settings['logos_footer']) ?>" alt="비티엘 플러스">
    <?php else: ?>
        <img class="site-footer__logo" src="<?= base_url('assets/images/btl/logo-footer.png') ?>" alt="비티엘 플러스" width="50" height="21">
    <?php endif; ?>
    <p class="site-footer__info"><?= esc($_settings['og_site'] ?? '') ?><?php if (!empty($_settings['email'])): ?>   이메일 : <?= esc($_settings['email']) ?><?php endif; ?><br><?php if (!empty($_settings['addr1'])): ?>주소 : <?= esc($_settings['addr1']) ?><?php endif; ?><?php if (!empty($_settings['custom_phone'])): ?>      전화 : <a href="tel:<?= preg_replace('/[^0-9]/', '', $_settings['custom_phone']) ?>"><?= esc($_settings['custom_phone']) ?></a><?php endif; ?><?php if (!empty($_settings['comnum'])): ?>      사업자등록번호 : <?= esc($_settings['comnum']) ?><?php endif; ?><br><?= esc($_settings['copyright'] ?? '') ?></p>
</footer>
