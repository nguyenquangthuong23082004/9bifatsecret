<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
$tabs = ['집중관리', '체형관리', '부분관리', '페이스 다이어트', '갱년기 다이어트'];
?>
<!-- 04. Program (프로그램) -->
<section class="sec program" id="program">
    <div class="sec-head">
        <p class="sec-head__label">체중+체형+페이스+이너순환 동시케어</p>
        <h2 class="sec-head__title"><span class="accent">비티엘 프로그램</span><br>기본구성</h2>
    </div>

    <div class="tabs" role="tablist" aria-label="프로그램 종류">
        <?php foreach ($tabs as $i => $t): ?>
        <button type="button" class="tab<?= $i === 0 ? ' is-active' : '' ?>" role="tab"
                aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= esc($t) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="program__panel">
        <img src="<?= $img('program-focus.webp') ?>" alt="집중관리 프로그램 – 비만 유형별 단계 관리" loading="lazy">
        <p class="program__desc">
            개인별 비만유형과, 제형 그리고 건강상태를 고려하여<br>
            디톡스부터 체지방 감소 셀룰라이트,탄력+근력관리까지<br>
            단계별로 진행합니다.
        </p>
    </div>
</section>
