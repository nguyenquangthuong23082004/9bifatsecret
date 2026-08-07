<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
// Ảnh rỗng → không in src, thêm hidden để không render ra
$imgAttrs = fn($f) => $f !== '' && $f !== null
    ? ' src="' . $img($f) . '"'
    : ' hidden style="display:none !important"';
/* 10. Menopause diet accordion (갱년기).
   Mỗi item accordion khi click sẽ cập nhật:
   - .meno__care h4  → careTitle
   - .meno__care p   → careDesc (có thể dùng \n để xuống dòng)
   - .meno__care-img → careImg  (tên file trong assets/images/btl/)
   - .meno__woman-pc → womanImg (tên file trong assets/images/btl/)
*/
$items = [
    /* [title, desc, careTitle, careDesc, careImg, womanImg] */
    [
        '격한 운동 불가',
        '열감, 가슴 두근거림, 관절약화',
        '편하게 누워서 관리',
        "운동부족 걱정 X\n무리없이 편안하게",
        'meno-care-treatment.jpg',      // ← 케어 이미지 (meno__care-img)
        'meno-symptom-joint-pain.png',      // ← 인물 이미지 (meno__woman-pc)
    ],
    [
        '기초 대사량 저하',
        '살찌기 쉬운 체질로 변형',
        '기초대사, 면역증진',
        "대사관리를 통해 체지방연소 가속화\n‘살 안찌는 체질’로 변화",
        'meno-symptom-belly-fat.webp',      // ← 케어 이미지
        'meno-symptom-stomach-pain.png',    // ← 인물 이미지
    ],
    [
        '여성호르몬 감소',
        '복부~허리 지방집중 거북등 심화',
        '림프순환 집중케어',
        "여성호르몬분비 증가를 위해 림프순환을 높여 갱년기 하체지방 커팅 및 
        올곧은 등라인 유지",
        'menopause-care.webp',      // ← 케어 이미지
        '',      // ← 인물 이미지
    ],
    [
        '혈관 노폐물 증가',
        '림프부종, 당뇨, 고지혈증 위험상승',
        '중년건강케어',
        "혈관노폐물, 림프독소\n배출을 통해 만성부종해결\n및 대사건강회복",
        'menopause-bg.webp',      // ← 케어 이미지 (full-bleed: ảnh phủ kín cả nền)
        'menopause-woman.png',      // ← 인물 이미지
    ],
];
$open = 0; // open by default

// Build JS-accessible data array (base_url resolved server-side)
$jsData = array_map(fn($item) => [
    'careTitle' => $item[2],
    'careDesc'  => nl2br(esc($item[3])),
    'careImg'   => $item[4] !== '' ? $img($item[4]) : '',
    'womanImg'  => $item[5] !== '' ? $img($item[5]) : '',
], $items);
?>
<!-- 10. Menopause diet (갱년기) -->
<section class="sec meno" id="menopause">
    <div class="sec-head">
        <p class="sec-head__label">다시 가벼워질 나를 만나는 시간</p>
        <h2 class="sec-head__title"><span class="line1">비티엘 맞춤</span><br class="only-mo"><span class="line2">갱년기 다이어트</span></h2>
    </div>

    <div class="meno__box">
        <ul class="accordion-btl">
            <?php foreach ($items as $i => [$title, $desc]): ?>
            <li class="acc__item<?= $i === $open ? ' is-open' : '' ?>" data-meno-idx="<?= $i ?>">
                <button type="button" class="acc__head" aria-expanded="<?= $i === $open ? 'true' : 'false' ?>">
                    <h3><?= esc($title) ?></h3>
                    <p><?= esc($desc) ?></p>
                </button>
                <div class="acc__panel"></div>
            </li>
            <?php endforeach; ?>
        </ul>
        <img id="menoWomanImg" class="meno__woman-pc"<?= $imgAttrs($items[$open][5]) ?>
             alt="" aria-hidden="true" loading="lazy">

        <!-- Khối chi tiết – cập nhật động khi chọn item -->
        <div class="meno__care" data-meno-care="<?= $open ?>">
            <h4 id="menoCareTitle"><?= esc($items[$open][2]) ?></h4>
            <p id="menoCareDesc"><?= nl2br(esc($items[$open][3])) ?></p>
            <img id="menoCareImg" class="meno__care-img"<?= $imgAttrs($items[$open][4]) ?>
                 alt="<?= esc($items[$open][2]) ?>" loading="lazy">
        </div>
    </div>

    <!-- Dữ liệu cho JS (server-side resolved) -->
    <script>
    window.MENO_CARE_DATA = <?= json_encode(array_values($jsData), JSON_UNESCAPED_UNICODE) ?>;
    </script>
</section>

