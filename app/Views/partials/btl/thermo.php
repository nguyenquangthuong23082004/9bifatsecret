<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 11. Thermo diet (체온 다이어트) – Figma 503:189.
   Rose full-bleed section: title, 3 hashtag chips, video block with
   speech bubbles around it, then the kcal claim. */
$chips = [
    ['emoji-sunglasses.png', '#체온 업 다이어트 원조'],
    ['emoji-heart.png',      '#모든 관리에 기본포함'],
    ['emoji-smile.png',      '#따로 지불이 필요한 타사와 비교불가'],
];
?>
<!-- 11. Thermo diet (체온 다이어트) -->
<section class="sec thermo" id="thermo">
    <div class="sec-head thermo__head">
        <p class="sec-head__label">요요를 막고, 갱년기 지방을 녹이는 가장 확실한 방법</p>
        <h2 class="sec-head__title">
            <span class="line1">대사 <em>UP</em>, 감량속도 <em>UP</em></span><br class="only-mo">
            <span class="line22">체온 다이어트</span>
        </h2>
    </div>

    <ul class="thermo__chips">
        <?php foreach ($chips as [$icon, $label]): ?>
        <li class="chip">
            <img src="<?= $img($icon) ?>" alt="" aria-hidden="true" loading="lazy">
            <span><?= esc($label) ?></span>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="thermo__stage">
        <!-- left bubbles -->
        <p class="bubble bubble--a">지방연소능력 강화</p>
        <p class="bubble bubble--b">자연 식욕 감퇴</p>
        <p class="bubble bubble--c">타입별 전문기기 관리</p>

        <div class="thermo__player" role="button" tabindex="0" aria-label="체온 다이어트 영상 재생" data-video="<?= base_url('videos/video2.mp4') ?>">
            <div class="thermo__video-wrap" style="position: absolute; inset: 0; border-radius: 20px; overflow: hidden; z-index: 1;">
                <video class="thermo__video" src="<?= base_url('videos/video2.mp4') ?>" playsinline preload="metadata" style="width: 100%; height: 100%; object-fit: cover; display: block;"></video>
            </div>
            <img class="thermo__play" src="<?= $img('thermo-play.svg') ?>" alt="" aria-hidden="true" style="position: absolute; z-index: 2;">
        </div>

        <!-- right bubbles -->
        <p class="bubble bubble--d">기초대사량 증진</p>
        <p class="bubble bubble--e">신진대사 촉진</p>
        <p class="bubble bubble--f">체내 노폐물 배출</p>
    </div>

    <div class="thermo__claim">
        <h3><span class="mark">비티엘 관리 1회시 평균 약</span><br class="only-mo"><span class="mark">700kcal소모</span></h3>
        <p>누워서 관리 1회<br class="only-mo">=빠르게 걷기 1시간<br class="only-mo">=러닝머신 1시간</p>
    </div>
</section>
