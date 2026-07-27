<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 13. Your turn (당신 차례) – Figma 529:80.
   Before/After stacked cards (SNS-post style) + result badge + testimonial. */
?>
<!-- 13. Your turn (이제 당신 차례입니다) -->
<section class="sec turn" id="turn">
    <div class="sec-head turn__head">
        <p class="sec-head__label">
            1회 관리시 즉시효과 가능<br>
            2주 관리시 평균 -5kg 감소<br>
            비티엘 집중감량모델 평균 -15kg 감소
        </p>
        <h2 class="sec-head__title"><span class="line1">고민은 시간만 늦출 뿐</span><br><span class="line2">이제 당신 차례입니다!</span></h2>
    </div>

    <ul class="turn__dots" aria-hidden="true"><li class="is-on"></li><li></li><li></li><li></li></ul>

    <div class="turn__cards">
        <figure class="ba-card ba-card--before">
            <figcaption class="ba-card__label">BEFORE</figcaption>
            <div class="ba-card__photo"><img src="<?= $img('turn-before.png') ?>" alt="관리 전 전신 사진" loading="lazy"></div>
            <img class="ba-card__react" src="<?= $img('sns-react.png') ?>" alt="" aria-hidden="true" loading="lazy">
        </figure>

        <p class="turn__badge"><strong>-15kg</strong><span>3개월</span></p>

        <figure class="ba-card ba-card--after">
            <figcaption class="ba-card__label">AFTER</figcaption>
            <div class="ba-card__photo"><img src="<?= $img('turn-after.png') ?>" alt="관리 후 전신 사진" loading="lazy"></div>
            <img class="ba-card__react" src="<?= $img('sns-react.png') ?>" alt="" aria-hidden="true" loading="lazy">
        </figure>
    </div>

    <blockquote class="turn__quote">
        <p>저는 다이어트보다 항상 요요가 고민이었는데<br>
           이번에 3개월 -15kg감량 후에는 처음으로<br>
           요요가 안왔어요. 유지까지 책임져주는 곳이라<br>
           너무 신뢰가 갑니다.</p>
    </blockquote>
</section>
