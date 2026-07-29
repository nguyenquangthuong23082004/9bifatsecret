<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 02. Philosophy (이념) -->
<section class="sec philo" id="philosophy">
    <div class="sec-head philo__head">
        <h2 class="sec-head__title sec-head__title--rose">
            <span class="line1">부산 프리미엄 체험관리</span><br>다이어트 전문 센터
        </h2>
        <p class="sec-head__label">세계적으로 검증된<br>BTL전문가와 1:1 맞춤 체형관리</p>
    </div>

    <div class="philo__logo">
        <p class="philo__watermark" aria-hidden="true">PREMIUM<br>BODY CARE</p>
        <!-- PC dùng ảnh riêng (philosophy-triangle-pc.png), mobile giữ bản gốc -->
        <picture class="philo__logo-mark">
            <source media="(min-width:992px)" srcset="<?= $img('philosophy-triangle-pc.png') ?>">
            <img src="<?= $img('philosophy-triangle.png') ?>" alt="BITIEL premium body care" loading="lazy">
        </picture>
        <img class="philo__logo-text" src="<?= $img('philosophy-logo-gray.png') ?>" alt="bitiel" loading="lazy">
    </div>

    <div class="philo__list">
        <div class="philo__item">
            <span class="philo__bullet" aria-hidden="true"></span>
            <div>
                <h3><span class="ko">아름답게</span> Beautiful</h3>
                <p>개인의 체형을 고려한 맞춤 관리로<br>균형 잡힌 아름다운 바디라인</p>
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
