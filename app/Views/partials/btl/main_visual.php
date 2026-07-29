<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 01. Main visual -->
<section class="sec hero" id="main">
    <!-- Lớp nền (hoạ tiết vòng tròn + bóng "before"). Dùng <img> thay cho
         background-image để backend sau này render đường dẫn ảnh động;
         CSS .hero__bg > img (object-fit:cover) biến nó thành ảnh nền. -->
    <div class="hero__bg" aria-hidden="true">
        <img src="<?= $img('bg-main-vs-pc.png') ?>" alt="" aria-hidden="true">
    </div>

    <div class="hero__inner">
        <div class="hero__copy">
            <p class="hero__label"><b>19년의 노하우</b>가 이뤄낸 성공</p>
            <h1 class="hero__title">누적 1만 명의<br>놀라운 변화</h1>
            <p class="hero__sub">-20만kg <span class="em">감/량/신/화</span></p>
        </div>

        <div class="hero__visual">
            <!-- PC dùng ảnh riêng (main-visual.png), mobile giữ bản .webp -->
            <picture>
                <source media="(min-width:992px)" srcset="<?= $img('main-visual.png') ?>">
                <img src="<?= $img('main-visual.webp') ?>" alt="" aria-hidden="true">
            </picture>
        </div>
    </div>
</section>

<!-- 19 YEARS band -->
<div class="band"><p>19 YEARS OF EXPERIENCE</p></div>
