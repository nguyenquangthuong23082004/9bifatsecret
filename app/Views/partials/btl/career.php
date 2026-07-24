<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
$awards = [
    ['다이어트 + 체형관리<br>전문운영', '19년'],
    ['체중감량<br>성공률', '94.5%'],
    ['헬스케어<br>브랜드 대상', '1년'],
    ['요요방지율<br>유지효과입증', '94.5%'],
];
?>
<!-- 03. Career / awards (경력) -->
<section class="sec career" id="career">
    <div class="career__bg" aria-hidden="true"></div>
    <div class="career__inner">
        <h2 class="career__title">
            <span class="l1">19년 경력이 증명한</span>
            <span class="l2">비티엘 다이어트</span>
        </h2>

        <div class="career__awards">
            <?php foreach ($awards as [$label, $num]): ?>
            <div class="award">
                <p class="award__label"><?= $label ?></p>
                <p class="award__num"><?= esc($num) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <img class="career__trophy" src="<?= $img('trophy.webp') ?>" alt="2026 BITIEL 수상 트로피" loading="lazy">

        <div class="career__tv">
            <img class="career__live" src="<?= $img('badge-live.png') ?>" alt="LIVE" loading="lazy">
            <p class="career__tv-title"><span class="hl">머니투데이 방송</span>,<br>방송 통 소개</p>

            <div class="career__player" role="button" tabindex="0" aria-label="방송 영상 재생" data-video="">
                <svg class="play" width="60" height="60" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                    <rect x="0.5" y="0.5" width="59" height="59" rx="29.5" stroke="white"/>
                    <path d="M36 28.268C37.3333 29.0378 37.3333 30.9623 36 31.7321L28.5 36.0622C27.1667 36.832 25.5 35.8697 25.5 34.3301L25.5 25.6699C25.5 24.1303 27.1667 23.168 28.5 23.9378L36 28.268Z" fill="white"/>
                </svg>
            </div>

            <div class="career__apps" aria-hidden="true">
                <img class="app-1" src="<?= $img('app-1.png') ?>" alt="" loading="lazy">
                <img class="app-2" src="<?= $img('app-2.png') ?>" alt="" loading="lazy">
            </div>
        </div>
    </div>
</section>
