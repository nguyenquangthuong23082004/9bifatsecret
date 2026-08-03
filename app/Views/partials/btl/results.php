<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 12. Results (결과로 증명) – Figma 516:446.
   CASE 01 has 4 tabs (전신/체형/부분/일상); 전신 and 부분 each stack TWO blocks.
   CASE 02/03 are single composited cards.

   Every photo in this section is scaled/offset individually in Figma (never a
   plain "cover"), so each one carries its exact w/h/left/top from the node —
   $shot(file, w%, h%, left%, top%, alt). */
$tabs = ['전신관리', '체형관리', '부분관리', '일상'];
$shot = function (string $file, $w, $h, $l, $t, string $alt = '', string $cls = '') use ($img) {
    printf(
        '<img%s src="%s" alt="%s"%s style="width:%s%%;height:%s%%;left:%s%%;top:%s%%" loading="lazy">',
        $cls !== '' ? ' class="' . $cls . '"' : '',
        $img($file),
        esc($alt, 'attr'),
        $alt === '' ? ' aria-hidden="true"' : '',
        $w, $h, $l, $t
    );
};
?>
<!-- 12. Results (결과로 증명하는 비티엘 다이어트) -->
<section class="sec results" id="results">
    <div class="sec-head results__head">
        <p class="sec-head__label">눈바디+인바디 2중검증<br>확실한 비포 애프터</p>
        <h2 class="sec-head__title"><span class="line1">결과로 증명하는</span><br><span class="line2">비티엘 다이어트</span></h2>
    </div>

    <!-- ============ CASE 01 ============ -->
    <div class="case">
        <p class="case__who">
            <span class="case__avatar" aria-hidden="true"></span>
            <span class="case__badge">CASE 01</span>
            <span class="case__name">강 ** 회원님의 실제 사례</span>
        </p>

        <div class="results__tabs" role="tablist">
            <?php foreach ($tabs as $i => $t): ?>
            <button type="button" class="rtab<?= $i === 0 ? ' is-active' : '' ?>" role="tab"
                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" data-panel="rp<?= $i ?>"><?= esc($t) ?></button>
            <?php endforeach; ?>
        </div>

        <!-- 전신관리 (516:399) — 전면 block + 측면 block are BOTH shown, stacked -->
        <div class="rpanel is-active" id="rp0">
            <div class="rblock">
                <p class="subhead"><span class="is-on">전면 비포 애프터</span><i>·</i><span>측면 비포 애프터</span></p>
                <div class="ba-row">
                    <div class="ba-shot"><?php $shot('res-c1-front-before.png', 149.4, 120.67, -25, -20.67, '전신관리 전면 비포') ?></div>
                    <div class="ba-shot"><?php $shot('res-c1-front-after.png', 142.19, 125.89, -19.57, -25.89, '전신관리 전면 애프터') ?></div>
                    <p class="ba-badge"><span>체지방</span><strong>-13.5kg</strong></p>
                </div>
                <p class="ba-caption">체중 -15.5kg / 체지방 -13.5kg / 내장지방 4레벨</p>
            </div>

            <!-- 02_측면비포애프터 (503:225) — 3 shots span 518px → horizontal swipe -->
            <div class="rblock">
                <p class="subhead"><span>전면 비포 애프터</span><i>·</i><span class="is-on">측면 비포 애프터</span></p>
                <div class="ba-row ba-row--scroll">
                    <div class="ba-shot"><?php $shot('res-c1-side-1.png', 41.87, 97.12, 29.17, 0, '전신관리 측면 1') ?><span class="ba-shot__ring" aria-hidden="true"></span></div>
                    <div class="ba-shot"><?php $shot('res-c1-side-2.png', 51.88, 103.37, 19.55, -3.37, '전신관리 측면 2') ?><span class="ba-shot__ring" aria-hidden="true"></span></div>
                    <div class="ba-shot"><?php $shot('res-c1-side-3.png', 43.77, 106.93, 24.08, -6.93, '전신관리 측면 3') ?><span class="ba-shot__ring" aria-hidden="true"></span></div>
                </div>
                <p class="ba-caption">체중 -15.5kg / 체지방 -13.5kg / 내장지방 4레벨</p>
            </div>
        </div>

        <!-- 체형관리 (516:398) — only the 전면 block exists in the design -->
        <div class="rpanel" id="rp1">
            <div class="rblock rblock--nohead">
                <!-- <p class="subhead"><span class="is-on">전면 비포 애프터</span><i>·</i><span>측면 비포 애프터</span></p> -->
                <div class="ba-row">
                    <div class="ba-shot"><?php $shot('res-c1-shape-before.png', 126.79, 102.4, -13.1, -2.16, '체형관리 비포') ?></div>
                    <div class="ba-shot"><?php $shot('res-c1-shape-after.png', 119.64, 105.92, -9.82, -5.68, '체형관리 애프터') ?></div>
                    <p class="ba-badge"><strong>-10kg</strong><span>이후</span></p>
                </div>
                <p class="ba-caption">-10kg부터는 없던 허리라인이 생겼어요.</p>
            </div>
        </div>

        <!-- 부분관리 (516:397) — 복부 block + 허벅지 block, both shown -->
        <div class="rpanel" id="rp2">
            <div class="rblock rblock--gap40">
                <p class="subhead"><span class="is-on">복부 비포 애프터</span><i>·</i><span>허벅지 비포 애프터</span></p>
                <div class="ba-row ba-row--dark ba-row--h90">
                    <div class="ba-shot">
                        <?php $shot('res-c1-belly-before.png', 100, 99.03, 0, 0.49, '복부 비포') ?>
                        <span class="ba-zoom">
                            <span class="ba-zoom__pic"><?php $shot('res-c1-belly-before.png', 341.12, 180.97, -125.5, -52.06) ?></span>
                            <em>83</em>
                        </span>
                    </div>
                    <div class="ba-shot">
                        <?php $shot('res-c1-belly-after.png', 100, 100, 0, 0, '복부 애프터', 'is-contain') ?>
                        <span class="ba-zoom">
                            <span class="ba-zoom__pic"><?php $shot('res-c1-belly-after-zoom.png', 375, 227.83, -142.19, -79.96) ?></span>
                            <em>77.5</em>
                        </span>
                    </div>
                </div>
                <p class="ba-caption">-6.5인치 감소<br>(3개월차)</p>
            </div>

            <div class="rblock rblock--gap40">
                <p class="subhead"><span>복부 비포 애프터</span><i>·</i><span class="is-on">허벅지 비포 애프터</span></p>
                <div class="ba-row ba-row--dark ba-row--h161">
                    <div class="ba-shot">
                        <?php $shot('res-c1-thigh-before.png', 100, 100, 0, 0, '허벅지 비포', 'is-contain') ?>
                        <span class="ba-zoom ba-zoom--low">
                            <span class="ba-zoom__pic"><?php $shot('res-c1-thigh-before.png', 148.5, 189.77, -48.5, 0) ?></span>
                            <em>71.8</em>
                        </span>
                    </div>
                    <div class="ba-shot">
                        <?php $shot('res-c1-thigh-after.png', 70.15, 100, 19.07, 0, '허벅지 애프터') ?>
                        <span class="ba-zoom ba-zoom--low">
                            <span class="ba-zoom__pic"><?php $shot('res-c1-thigh-after.png', 133.08, 181.81, -33.2, 3.35) ?></span>
                            <em>70.2</em>
                        </span>
                    </div>
                </div>
                <p class="ba-caption ba-caption--gap30">-1.6인치 감소<br>(단 1주차)</p>
            </div>
        </div>

        <!-- 일상 (516:388) — no sub-heading; both shots are heavily zoomed in Figma -->
        <div class="rpanel" id="rp3">
            <div class="rblock rblock--nohead">
                <div class="ba-row">
                    <div class="ba-shot ba-shot--gray">
                        <?php $shot('res-c1-daily-before.png', 237.1, 143.63, -68.45, -10.58, '일상 비포') ?>
                        <span class="ba-bar" aria-hidden="true"></span>
                    </div>
                    <div class="ba-shot ba-shot--rose">
                        <?php $shot('res-c1-daily-after.png', 112.19, 186.7, -6.24, 4.39, '일상 애프터') ?>
                        <span class="ba-bar" aria-hidden="true"></span>
                    </div>
                    <p class="ba-badge"><strong>-10kg</strong><span>이후</span></p>
                </div>
                <p class="ba-caption">비티엘 다이어트가 유독 드라마틱한 이유</p>
                <p class="ba-desc">전신체중감량은 물론, 다이어트 후에도 빠지지 않는<br>부위의 선택적 인치감량도 가능</p>
            </div>
        </div>
    </div>

    <!-- ============ CASE 02 (516:426) — faded "before" behind the main shot ============ -->
    <div class="case case--simple">
        <p class="case__who">
            <span class="case__avatar" aria-hidden="true"></span>
            <span class="case__badge">CASE 02</span>
            <span class="case__name">예비신부 김 ** 님의 실제 사례</span>
        </p>
        <div class="case__shot case__shot--c2">
            <?php $shot('res-c2-before.png', 76.09, 111.34, -3.69, 4.11, '', 'is-ghost-20') ?>
            <?php $shot('res-c2-after.png', 87.9, 205.57, 10.5, -19.09, '예비신부 상체관리 비포 애프터') ?>
        </div>
        <p class="ba-caption">본식 2개월전 승모근 + 팔뚝살 집중관리</p>
        <p class="ba-desc">효과는 확실한데 다운타임은 적어 본식 전&nbsp;<br class="only-mo">긴급 상체관리에 너무 좋네요.</p>
    </div>

    <!-- ============ CASE 03 (516:427) ============ -->
    <div class="case case--simple">
        <p class="case__who">
            <span class="case__avatar" aria-hidden="true"></span>
            <span class="case__badge">CASE 03</span>
            <span class="case__name">갱년기 임 ** 님의 실제사례</span>
        </p>
        <div class="case__shot case__shot--c3">
            <?php $shot('res-c3-before.png', 43.51, 191.36, 12.19, 0, '', 'is-ghost-60') ?>
            <?php $shot('res-c3-after.png', 42.71, 195.91, 45.63, 3.18, '갱년기 복부집중관리 비포 애프터') ?>
        </div>
        <p class="ba-caption">전신감량 + 갱년기 복부집중관리 3개월차</p>
        <p class="ba-desc">관절염으로 운동을 전혀 못하는데 누워서 관리 받으니 참좋네요. 50대 이후로 뱃살이 빠진건 처음입니다.</p>
    </div>
</section>
