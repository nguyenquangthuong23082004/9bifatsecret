<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
$devices = ['device-1.webp', 'device-2.webp', 'device-3.webp'];
?>
<!-- 05. Dual solution (듀얼솔루션) -->
<section class="sec dual" id="dual">
    <div class="sec-head">
        <p class="sec-head__label">체중+체형+페이스+이너순환 동시케어</p>
        <h2 class="sec-head__title"><span class="line1">기기로 빼고, 수기로 다듬는</span><br><span class="accent">DUAL 솔루션</span></h2>
    </div>

    <div class="dual__block">
        <h3>비티엘의 최신기기</h3>
        <ul class="check-list">
            <li>관리기계 최신화가 전국 제일 빠른 곳</li>
            <li>바디/페이스 기기 각각 보유</li>
            <li>감량목표나 구체적 니즈에 맞게 최적의 조합으로 추천</li>
        </ul>
    </div>

    <!-- device carousel -->
    <div class="dual__devices swiper">
        <div class="swiper-wrapper">
            <?php foreach ($devices as $d): ?>
            <div class="swiper-slide" style="width:160px">
                <div class="device"><img src="<?= $img($d) ?>" alt="비티엘 전문 관리기기" loading="lazy"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <p class="dual__plus" aria-hidden="true">+</p>

    <div class="dual__block">
        <h3>비티엘의 수기테라피</h3>
        <ul class="check-list">
            <li class="hl">전 직원 국가기술자격증 보유</li>
            <li>림프순환/신진대사/근육이완/부종케어 등</li>
            <li>사람 손이 필요한 곳까지 수기테라피로 완벽관리</li>
        </ul>
    </div>

    <div class="dual__therapy">
        <img src="<?= $img('therapy-1.webp') ?>" alt="비티엘 수기테라피 – 얼굴 관리" loading="lazy">
        <img src="<?= $img('therapy-2.webp') ?>" alt="비티엘 수기테라피 – 바디 관리" loading="lazy">
    </div>
</section>
