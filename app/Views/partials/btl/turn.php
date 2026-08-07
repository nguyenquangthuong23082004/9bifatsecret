<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 13. Your turn (당신 차례) – Figma 529:80.
   Horizontal swipe carousel of 4 cases (529:26 / 529:27 / 529:40 / 529:53),
   each = BEFORE card + result badge + AFTER card + testimonial.
   Photo fills are scaled/offset per node, so each carries its own crop
   (w/h/left/top in %); slide 4 has no photos yet in the design. */
$slides = [
    [
        'badge' => ['-15kg', '3개월'],
        'react' => 'sns-react.png',
        'before' => ['turn-before.png', 96.53, 133.08, 3.47, -4.42],
        'after'  => ['turn-after.png',  82.29, 117.04, 9.24, 0],
        'quote'  => "저는 다이어트보다 항상 요요가 고민이었는데 이번에 3개월 -15kg감량 후에는 처음으로 요요가 안왔어요. 유지까지 책임져주는 곳이라 너무 신뢰가 갑니다.",
    ],
    [
        'badge' => ['-7kg', '3주'],
        'react' => 'turn-s2-react.png',
        'before' => ['turn-s2-before.png', 48.25, 128.08, 27.57, -0.04],
        'after'  => ['turn-s2-after.png',  48.16, 127.63, 26.08, 0],
        'quote'  => "단 3주만에 허리라인이 생겼어요. 저는 살도 살이지만 다리순환이 안되는게 문제였는데 비티엘에서는 순환자체를 도와주니까 감량과 더불어 부종까지 빠지니 효과가 빨리 더 잘드러나는 것 같아요",
    ],
    [
        'badge' => ['-12kg', '3개월'],
        'react' => 'turn-s3-react.png',
        'before' => ['turn-s3-before.png', 'contain'],
        'after'  => ['turn-s3-after.png',  'contain'],
        'quote'  => "모태 하체비만이라 아무리 살을 빼도 하체가 비대해보이는건 포기했었는데, 비티엘에서는 선택부위만 집중적으로 감량이 가능하니 너무 신기하네요. 3개월만에 다리라인이 매끈해진게 확실히 느껴지고 이제서야 상체랑 비율이 맞아서 옷입는게 재미있어요.",
    ],
    [
        'badge' => ['-15kg', '13주차'],
        'react' => 'turn-s4-react.png',
        // thanh đen che mắt (Figma 608:5 / 608:6) đã vẽ sẵn vào ảnh -censored
        // before: crop zoom riêng cho PC (xem .turn-slide--4 trong pc.css)
        'before' => ['turn-s4-before-censored.jpg', 'contain'],
        'after'  => ['turn-s4-after-censored.png', 'cover'],
        'quote'  => "출산 후 초기,중기,후기에 나뉘 체계적으로 관리받으니 정체기도 가뿐이 넘기고 15kg 감량 성공했습니다!! 비티엘 다이어트는 감량효과도 확실한데 산모에게 맞는 림프순환, 망가진 체형 교정까지 건강을 같이 지킬 수 있어서 훨씬 만족스러워요!",
    ],
];

/** Render the photo box of one card (or an empty placeholder). */
$photo = function (?array $fill) use ($img) {
    echo '<div class="ba-card__photo">';
    if ($fill !== null) {
        // ['file', 'contain'|'cover'] -> object-fit; ngược lại là crop w/h/left/top theo %
        if (in_array($fill[1] ?? null, ['contain', 'cover'], true)) {
            printf('<img class="is-%s" src="%s" alt="" aria-hidden="true" loading="lazy">', $fill[1], $img($fill[0]));
        } else {
            printf(
                '<img src="%s" alt="" aria-hidden="true" loading="lazy" style="width:%s%%;height:%s%%;left:%s%%;top:%s%%">',
                $img($fill[0]), $fill[1], $fill[2], $fill[3], $fill[4]
            );
        }
    }
    echo '</div>';
};
?>
<!-- 13. Your turn (이제 당신 차례입니다) -->
<section class="sec turn" id="turn">
    <div class="sec-head turn__head">
        <p class="sec-head__label">
            1회 관리시 즉시감량 가능<br>
            2주 관리시 평균 -5kg 감소<br>
            비티엘 집중감량모델 평균 -15kg 감소
        </p>
        <h2 class="sec-head__title"><span class="line1">고민은 시간만 늦출 뿐</span><br><span class="line2">이제 당신 차례입니다!</span></h2>
    </div>

    <!-- pagination sits ABOVE the cards (Figma 516:458) -->
    <div class="turn__dots" aria-hidden="true"></div>

    <div class="turn__swiper swiper">
        <button type="button" class="turn__nav turn__nav--prev only-pc" aria-label="이전"></button>
        <button type="button" class="turn__nav turn__nav--next only-pc" aria-label="다음"></button>
        <div class="swiper-wrapper">
            <?php foreach ($slides as $n => $s): ?>
            <div class="swiper-slide turn-slide--<?= $n + 1 ?>">
                <div class="turn__cards">
                    <figure class="ba-card ba-card--before">
                        <figcaption class="ba-card__label">BEFORE</figcaption>
                        <?php $photo($s['before']) ?>
                        <img class="ba-card__react" src="<?= $img($s['react']) ?>" alt="" aria-hidden="true" loading="lazy">
                    </figure>

                    <p class="turn__badge"><strong><?= esc($s['badge'][0]) ?></strong><span><?= esc($s['badge'][1]) ?></span></p>

                    <figure class="ba-card ba-card--after">
                        <figcaption class="ba-card__label">AFTER</figcaption>
                        <?php $photo($s['after']) ?>
                        <img class="ba-card__react" src="<?= $img($s['react']) ?>" alt="" aria-hidden="true" loading="lazy">
                    </figure>
                </div>

                <blockquote class="turn__quote">
                    <p><?= str_replace(['<br />', '<br>', '<br/>'], '<br class="only-mo">', nl2br(esc($s['quote']))) ?></p>
                </blockquote>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
