<?php
$img  = fn($f) => base_url('assets/images/btl/' . $f);
$tabs = ['집중관리', '체형관리', '부분관리', '페이스 다이어트', '갱년기 다이어트'];
?>
<!-- 04. Program (프로그램) -->
<section class="sec program" id="program">
    <div class="sec-head">
        <p class="sec-head__label">체중+체형+페이스+이너순환 동시케어</p>
        <h2 class="sec-head__title"><span class="accent">비티엘 프로그램</span><br>기본구성</h2>
    </div>

    <div class="tabs" role="tablist" aria-label="프로그램 종류">
        <?php foreach ([array_slice($tabs, 0, 3), array_slice($tabs, 3)] as $r => $row): ?>
        <div class="tabs__row tabs__row--<?= $r + 1 ?>" role="presentation">
            <?php foreach ($row as $j => $label): $i = $r * 3 + $j; ?>
            <button type="button" class="tab<?= $i === 0 ? ' is-active' : '' ?>" role="tab"
                    id="program-tab-<?= $i ?>" aria-controls="program-panel-<?= $i ?>"
                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= esc($label) ?></button>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- 01_집중관리 (463:208) -->
    <div class="program__panel program__panel--focus" id="program-panel-0" role="tabpanel" aria-labelledby="program-tab-0">
        <div class="program__card">
            <div class="program__media">
                <img src="<?= $img('program-focus.png') ?>" alt="집중관리 프로그램 – 비만 유형별 단계 관리" loading="lazy">
            </div>
        </div>
        <p class="program__desc">개인별 비만유형과, 제형 그리고 건강상태를 고려하여<br>디톡스부터 체지방 감소 셀룰라이트,탄력+근력관리까지<br>단계별로 진행합니다.</p>
    </div>

    <!-- 02_체형관리 (463:224) -->
    <div class="program__panel program__panel--shape" id="program-panel-1" role="tabpanel" aria-labelledby="program-tab-1" hidden>
        <div class="program__card">
            <div class="program__media">
                <img src="<?= $img('program-shape.png') ?>" alt="체형관리 프로그램 – 상하체 균형 다이어트" loading="lazy">
            </div>
        </div>
        <p class="program__desc">상세비만, 하체비만 등 상하의 균형을 이루기 위한 다이어트</p>
    </div>

    <!-- 03_부분관리 (464:269) -->
    <div class="program__panel program__panel--part" id="program-panel-2" role="tabpanel" aria-labelledby="program-tab-2" hidden>
        <div class="program__card">
            <div class="program__media">
                <img src="<?= $img('program-part.png') ?>" alt="부분관리 프로그램 – 특정부위 인치감소" loading="lazy">
            </div>
        </div>
        <p class="program__desc">다이어트 후에도 빠지지 않는 특정부위 군살의<br>확실한 인치감소효과를 기대할 수 있는 프로그램</p>
    </div>

    <!-- 04_페이스다이어트 (463:230) -->
    <div class="program__panel program__panel--face" id="program-panel-3" role="tabpanel" aria-labelledby="program-tab-3" hidden>
        <div class="program__card">
            <div class="face">
                <div class="face__item face__item--before">
                    <img class="face__photo" src="<?= $img('program-face-before.png') ?>" alt="페이스 다이어트 비포" loading="lazy">
                    <div class="face__badge">
                        <span class="face__avatar"><img src="<?= $img('program-face-before-avatar.png') ?>" alt="" loading="lazy"></span>
                        <span class="program__tag">Before</span>
                    </div>
                </div>
                <div class="face__item face__item--after">
                    <img class="face__photo" src="<?= $img('program-face-after.png') ?>" alt="페이스 다이어트 애프터" loading="lazy">
                    <div class="face__badge">
                        <span class="face__avatar"><img src="<?= $img('program-face-after-avatar.png') ?>" alt="" loading="lazy"></span>
                        <span class="program__tag">After</span>
                    </div>
                </div>
            </div>
        </div>
        <p class="program__desc">감량후 처진 얼굴에 탄력을 보충하는 페이스리프팅 프로그램</p>
    </div>

    <!-- 05_갱년기다이어트 (464:275) -->
    <div class="program__panel program__panel--meno" id="program-panel-4" role="tabpanel" aria-labelledby="program-tab-4" hidden>
        <div class="program__card">
            <img class="meno__img meno__img--before" src="<?= $img('program-meno-before.png') ?>" alt="갱년기 다이어트 비포" loading="lazy">
            <img class="meno__img meno__img--after" src="<?= $img('program-meno-after.png') ?>" alt="갱년기 다이어트 애프터" loading="lazy">
            <span class="program__tag meno__tag meno__tag--before">Before</span>
            <span class="program__tag meno__tag meno__tag--after">After</span>
        </div>
        <p class="program__desc">호르몬 밸런스 조절 + 림프 / 혈액 순환 활성화를 통해 진행하는 다이어트로<br>중년에 맞는 건강한 감량 프로그램</p>
    </div>
</section>
