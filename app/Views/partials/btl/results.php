<?php
$img = fn($f) => base_url('assets/images/btl/' . $f);
/* 12. Results (결과로 증명) – Figma 516:446.
   CASE 01 has 4 tabs (전신/체형/부분/일상); 전신 and 부분 each have two
   sub-views switched by the "A · B" heading. CASE 02/03 are single cards. */
$tabs = ['전신관리', '체형관리', '부분관리', '일상'];
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
            <span class="case__name">강**회원님의 실제 사례</span>
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
                    <div class="ba-shot"><img src="<?= $img('res-c1-front-before.png') ?>" alt="전신관리 전면 비포" loading="lazy"></div>
                    <div class="ba-shot"><img src="<?= $img('res-c1-front-after.png') ?>" alt="전신관리 전면 애프터" loading="lazy"></div>
                    <p class="ba-badge"><span>체지방</span><strong>-13.5kg</strong></p>
                </div>
                <p class="ba-caption">체중 -15.5kg / 체지방 -13.5kg / 내장지방 4레벨</p>
            </div>

            <!-- 02_측면비포애프터 (503:225) — 3 shots, wider than the 375 grid → swipe -->
            <div class="rblock">
                <p class="subhead"><span>전면 비포 애프터</span><i>·</i><span class="is-on">측면 비포 애프터</span></p>
                <div class="ba-row ba-row--scroll">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="ba-shot">
                        <img src="<?= $img('res-c1-side-' . $i . '.png') ?>" alt="전신관리 측면 <?= $i ?>" loading="lazy">
                        <span class="ba-shot__ring" aria-hidden="true"></span>
                    </div>
                    <?php endfor; ?>
                </div>
                <p class="ba-caption">체중 -15.5kg / 체지방 -13.5kg / 내장지방 4레벨</p>
            </div>
        </div>

        <!-- 체형관리 (516:398) — only the 전면 block exists in the design -->
        <div class="rpanel" id="rp1">
            <div class="rblock">
                <p class="subhead"><span class="is-on">전면 비포 애프터</span><i>·</i><span>측면 비포 애프터</span></p>
                <div class="ba-row">
                    <div class="ba-shot"><img src="<?= $img('res-c1-shape-before.png') ?>" alt="체형관리 비포" loading="lazy"></div>
                    <div class="ba-shot"><img src="<?= $img('res-c1-shape-after.png') ?>" alt="체형관리 애프터" loading="lazy"></div>
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
                        <img src="<?= $img('res-c1-belly-before.png') ?>" alt="복부 비포" loading="lazy">
                        <span class="ba-zoom"><img src="<?= $img('res-c1-belly-before.png') ?>" alt="" aria-hidden="true"><em>83</em></span>
                    </div>
                    <div class="ba-shot">
                        <img src="<?= $img('res-c1-belly-after.png') ?>" alt="복부 애프터" loading="lazy">
                        <span class="ba-zoom"><img src="<?= $img('res-c1-belly-after-zoom.png') ?>" alt="" aria-hidden="true"><em>77.5</em></span>
                    </div>
                </div>
                <p class="ba-caption">-6.5인치 감소<br>(3개월차)</p>
            </div>

            <div class="rblock rblock--gap40">
                <p class="subhead"><span>복부 비포 애프터</span><i>·</i><span class="is-on">허벅지 비포 애프터</span></p>
                <div class="ba-row ba-row--dark ba-row--h161">
                    <div class="ba-shot">
                        <img src="<?= $img('res-c1-thigh-before.png') ?>" alt="허벅지 비포" loading="lazy">
                        <span class="ba-zoom ba-zoom--low"><img src="<?= $img('res-c1-thigh-before.png') ?>" alt="" aria-hidden="true"><em>71.8</em></span>
                    </div>
                    <div class="ba-shot">
                        <img src="<?= $img('res-c1-thigh-after.png') ?>" alt="허벅지 애프터" loading="lazy">
                        <span class="ba-zoom ba-zoom--low"><img src="<?= $img('res-c1-thigh-after.png') ?>" alt="" aria-hidden="true"><em>70.2</em></span>
                    </div>
                </div>
                <p class="ba-caption ba-caption--gap30">-1.6인치 감소<br>(단 1주차)</p>
            </div>
        </div>

        <!-- 일상 (516:388) — no sub-heading in the design -->
        <div class="rpanel" id="rp3">
            <div class="rblock rblock--nohead">
                <div class="ba-row">
                    <div class="ba-shot ba-shot--gray">
                        <img src="<?= $img('res-c1-daily-before.png') ?>" alt="일상 비포" loading="lazy">
                        <span class="ba-bar" aria-hidden="true"></span>
                    </div>
                    <div class="ba-shot ba-shot--rose">
                        <img src="<?= $img('res-c1-daily-after.png') ?>" alt="일상 애프터" loading="lazy">
                        <span class="ba-bar" aria-hidden="true"></span>
                    </div>
                    <p class="ba-badge"><strong>-10kg</strong><span>이후</span></p>
                </div>
                <p class="ba-caption">비티엘 다이어트가 유독 드라마틱한 이유</p>
                <p class="ba-desc">전신체중감량은 물론, 다이어트 후에도 빠지지 않는<br>부위의 선택적 인치감량도 가능</p>
            </div>
        </div>
    </div>

    <!-- ============ CASE 02 ============ -->
    <div class="case case--simple">
        <p class="case__who">
            <span class="case__avatar" aria-hidden="true"></span>
            <span class="case__badge">CASE 02</span>
            <span class="case__name">예비신부 김** 님의 실제 사례</span>
        </p>
        <div class="case__shot"><img src="<?= $img('res-c2-after.png') ?>" alt="예비신부 상체관리 비포 애프터" loading="lazy"></div>
        <p class="ba-caption">본식 2개월전 승모근 + 팔뚝살 집중관리</p>
        <p class="ba-desc">효과는 확실한데 다운타임은 적어 본식 전<br>긴급 상체관리에 너무 좋네요.</p>
    </div>

    <!-- ============ CASE 03 ============ -->
    <div class="case case--simple">
        <p class="case__who">
            <span class="case__avatar" aria-hidden="true"></span>
            <span class="case__badge">CASE 03</span>
            <span class="case__name">갱년기 임** 님의 실제사례</span>
        </p>
        <div class="case__shot"><img src="<?= $img('res-c3-after.png') ?>" alt="갱년기 복부집중관리 비포 애프터" loading="lazy"></div>
        <p class="ba-caption">전신감량 + 갱년기 복부집중관리 3개월차</p>
        <p class="ba-desc">관절염으로 운동을 전혀 못하는데 누워서 관리 받으니 참좋네요.<br>50대 이후로 뱃살이 빠진건 처음입니다.</p>
    </div>
</section>
