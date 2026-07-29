<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 01. Main visual -->
<section class="sec hero" id="main">
    <!-- Lớp nền (hoạ tiết vòng tròn + bóng "before"). Gắn ảnh nền sau bằng
         .hero__bg{ background-image:url(...) }. Hiện để trống. -->
    <div class="hero__bg" aria-hidden="true"></div>

    <div class="hero__inner">
        <div class="hero__copy">
            <p class="hero__label"><b>19년의 노하우</b>가 이뤄낸 성공</p>
            <h1 class="hero__title">누적 1만 명의<br>놀라운 변화</h1>
            <p class="hero__sub">-20만kg <span class="em">감/량/신/화</span></p>
        </div>

        <div class="hero__visual">
            <img src="<?= $img('main-visual.webp') ?>" alt="" aria-hidden="true">
        </div>
    </div>
</section>

<!-- 19 YEARS band -->
<div class="band"><p>19 YEARS OF EXPERIENCE</p></div>
