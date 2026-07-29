<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 10. Menopause diet accordion (갱년기).
   Each concern expands to its care detail. Detail illustration currently shared
   (only one detail asset exists in Frame 5); per-item images can be added later. */
$items = [
    ['격한 운동 불가',   '열감, 가슴 두근거림, 관절약화',        '격한 운동 없이 편안한 관리',  "편하게 누워서\n무리없이 관리"],
    ['기초 대사량 저하', '살찌기 쉬운 체질로 변형',            '기초대사·면역 증진',        "대사관리로\n살 안찌는 체질로 변화"],
    ['림프 순환 저하',   '복부, 허리 지방집중, 거북등 심화',    '림프순환 집중케어',         "림프순환을 높여 갱년기\n하체지방 커팅 및\n올곧은 등 유지"],
    ['혈관 노폐물 증가', '림프부종, 당뇨, 고지혈증 위험상승',   '중년건강케어',              "혈관노폐물, 림프독소\n배출을 통해 만성부종해결\n및 대사건강회복"],
];
$open = 2; // 림프 순환 저하 open by default (matches Figma)
?>
<!-- 10. Menopause diet (갱년기) -->
<section class="sec meno" id="menopause">
    <div class="sec-head">
        <p class="sec-head__label">다시 가벼워질 나를 만나는 시간</p>
        <h2 class="sec-head__title"><span class="line1">비티엘 맞춤</span><br class="only-mo"><span class="line2">갱년기 다이어트</span></h2>
    </div>

    <div class="meno__box">
        <ul class="accordion-btl">
            <?php foreach ($items as $i => [$title, $desc, $careTitle, $careDesc]): ?>
            <li class="acc__item<?= $i === $open ? ' is-open' : '' ?>">
                <button type="button" class="acc__head" aria-expanded="<?= $i === $open ? 'true' : 'false' ?>">
                    <h3><?= esc($title) ?></h3>
                    <p><?= esc($desc) ?></p>
                </button>
                <!-- <div class="acc__panel">
                    <div class="acc__detail">
                        <h4><?= esc($careTitle) ?></h4>
                        <p><?= nl2br(esc($careDesc)) ?></p>
                        <img class="only-mo" src="<?= $img('menopause-woman.png') ?>" alt="" aria-hidden="true" loading="lazy">
                    </div>
                </div> -->
            </li>
            <?php endforeach; ?>
        </ul>
        <!-- <img class="meno__woman-pc only-pc" src="<?= $img('menopause-woman.png') ?>" alt="" aria-hidden="true" loading="lazy"> -->

        <!-- Khối chi tiết TĨNH thay cho accordion cũ (Figma 529:580) -->
        <div class="meno__care">
            <h4>림프순환 집중케어</h4>
            <p>림프순환을 높여 갱년기 하체지방 커팅 및<br>올곧은 등 유지</p>
        </div>
    </div>
</section>
