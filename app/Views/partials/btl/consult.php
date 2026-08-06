<?php
/** 07. Real-time consult status (상담현황) */
$rows = [
    ['상담대기', '구*명(50대,여)', '27분전', 'wait'],
    ['상담중',   '박*진(35세,여)', '31분전', 'active'],
    // ['상담완료', '구*명(50대,여)', '30분전', 'done'],
    ['상담완료', '최*연(28세,여)', '42분전', 'wait'],
    ['상담완료', '김*미(42세,여)', '50분전', 'muted'],
    ['상담완료', '김*은(33세,여)', '63분전', 'muted'],
    ['상담완료', '오*주(37세,여)', '78분전', 'muted'],
];
?>
<section class="sec consult" id="consult">
    <div class="sec-head">
        <p class="sec-head__label">오늘의 실시간 상담 현황</p>
        <h2 class="sec-head__title">지금도 많은 분들이<br class="">맞춤 상담을 진행하고<br class="only-mo">있습니다.</h2>
    </div>

    <ul class="consult__list">
        <?php foreach ($rows as [$state, $name, $time, $mod]): ?>
        <li class="consult__row consult__row--<?= $mod ?>">
            <span class="info">
                <span class="state"><?= esc($state) ?></span>
                <span class="name"><?= esc($name) ?></span>
            </span>
            <span class="time"><?= esc($time) ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
