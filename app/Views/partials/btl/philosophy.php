<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 02. Philosophy (이념) -->
<section class="sec philo" id="philosophy">
    <div class="sec-head philo__head">
        <h2 class="sec-head__title sec-head__title--rose">
            <span class="line1">부산 프리미엄 체험관리</span><br>다이어트 전문 센터
        </h2>
        <p class="sec-head__label philo__lead">
            <span class="philo__lead-1">세계적으로 검증된 BTL전문가와 1:1 맞춤 체형관리</span>
            <span class="philo__lead-2"><b><em>Plus</em> <em>B</em>eauty <em>T</em>o <em>L</em>ife</b> : 삶에 아름다움을 더하다</span>
        </p>
    </div>

    <div class="philo__logo">
        <p class="philo__watermark" aria-hidden="true">PREMIUM<br>BODY CARE</p>
        <!-- PC dùng ảnh riêng (philosophy-triangle-pc.png), mobile giữ bản gốc -->
        <picture class="philo__logo-mark">
            <source media="(min-width:992px)" srcset="<?= $img('philosophy-triangle-pc.png') ?>">
            <img src="<?= $img('philosophy-triangle.png') ?>" alt="BITIEL premium body care" loading="lazy">
        </picture>
        <img class="philo__logo-text" src="<?= $img('philosophy-logo-gray.png') ?>" alt="bitiel" loading="lazy">

        <!-- Nhãn chạy dọc 3 cạnh tam giác (chỉ mobile — Figma 565:9 / 565:13 / 565:20) -->
        <span class="philo__edge philo__edge--left" aria-hidden="true"><b>아름답게</b> Beautiful</span>
        <span class="philo__edge philo__edge--right" aria-hidden="true"><b>건강하게</b> Healthy</span>
        <span class="philo__edge philo__edge--bottom" aria-hidden="true"><b>더욱 젊게</b> Younger</span>
    </div>

    <div class="philo__list">
        <div class="philo__item">
            <span class="philo__bullet" aria-hidden="true"></span>
            <div>
                <h3><span class="ko">아름답게</span> Beautiful</h3>
                <p>개인의 체형을 고려한 맞춤 관리로<br class="only-pc">균형 잡힌 아름다운 바디라인</p>
            </div>
        </div>
        <div class="philo__item">
            <span class="philo__bullet" aria-hidden="true"></span>
            <div>
                <h3><span class="ko">건강하게</span> Healthy</h3>
                <p>몸의 밸런스를 바로잡아<br>건강한 라이프스타일 완성</p>
            </div>
        </div>
        <div class="philo__item">
            <span class="philo__bullet" aria-hidden="true"></span>
            <div>
                <h3><span class="ko">더욱 젊게</span> Younger</h3>
                <p>꾸준한 관리로 오래 유지되는<br>건강한 아름다움</p>
            </div>
        </div>
    </div>
</section>
