<?php
/** 08. Yoyo ZERO comparison table (좌우로 스와이프해 비교) */
$star = '<svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M7.52447 1.46352C7.67415 1.00287 8.32585 1.00287 8.47553 1.46353L9.68386 5.18237C9.75079 5.38838 9.94277 5.52786 10.1594 5.52786H14.0696C14.554 5.52786 14.7554 6.14767 14.3635 6.43237L11.2001 8.73075C11.0248 8.85807 10.9515 9.08375 11.0184 9.28976L12.2268 13.0086C12.3764 13.4693 11.8492 13.8523 11.4573 13.5676L8.29389 11.2693C8.11865 11.1419 7.88135 11.1419 7.70611 11.2693L4.54267 13.5676C4.15081 13.8523 3.62357 13.4693 3.77325 13.0086L4.98157 9.28976C5.04851 9.08375 4.97518 8.85807 4.79994 8.73075L1.6365 6.43237C1.24464 6.14767 1.44603 5.52786 1.93039 5.52786H5.84062C6.05723 5.52786 6.24921 5.38838 6.31614 5.18237L7.52447 1.46352Z" fill="#FFC000"/></svg>';
$stars = fn(int $n) => '<span class="stars">' . str_repeat($star, $n) . '</span>';
?>
<!-- Yoyo ZERO title -->
<div class="sec-head" style="padding:74px 16px 0">
    <p class="sec-head__label">감량도 잘하지만 유지는 더 잘하는</p>
    <h2 class="sec-head__title"><span class="line1">비티엘만의 특별한</span><br class=""><span class="line2">요요 ZERO 다이어트</span></h2>
</div>

<section class="sec yoyo" id="yoyo" aria-label="다이어트 방식 비교">
    <div class="compare">
        <table>
            <colgroup>
                <col style="width:96px"><col style="width:91px"><col style="width:101px"><col style="width:131px"><col style="width:121px">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col" class="dim-hover">위**, 마***</th>
                    <th scope="col" class="dim-hover">다이어트약</th>
                    <th scope="col" class="dim-hover">지방흡입수술</th>
                    <th scope="col">비티엘 다이어트</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">단기간<br>감량효과</th>
                    <td><?= $stars(3) ?></td><td><?= $stars(3) ?></td><td><?= $stars(5) ?></td><td class="bitiel"><?= $stars(4) ?></td>
                </tr>
                <tr>
                    <th scope="row">요요<br>방지효과</th>
                    <td><?= $stars(1) ?></td><td><?= $stars(2) ?></td><td><?= $stars(3) ?></td><td class="bitiel"><?= $stars(5) ?></td>
                </tr>
                <tr>
                    <th scope="row">주요<br>부작용</th>
                    <td>극심한 요요<br>호르몬 교란</td>
                    <td>불면증, 우울증<br>손떨림등</td>
                    <td>피부탄력저하, 유착<br>피부괴사 등</td>
                    <td class="bitiel">없음</td>
                </tr>
                <tr>
                    <th scope="row">체질변화</th>
                    <td colspan="3" style="text-align:left">피부탄력저하, 유착 피부괴사 등</td>
                    <td class="bitiel">살 안찌는<br>체질로 변화</td>
                </tr>
                <tr>
                    <th scope="row">유지관리</th>
                    <td colspan="3" style="text-align:left">단기 감량에만 초점, 사후 케어 프로그램 부재</td>
                    <td class="bitiel">1년간 에프터케어<br>서비스 제공</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="swipe-hint">
        <span class="swipe-hint__icons" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <svg viewBox="0 0 24 24" fill="none"><path d="M8 11V6.5a1.5 1.5 0 013 0V11m0-1V5.5a1.5 1.5 0 013 0V11m0-.5V6.5a1.5 1.5 0 013 0V13c0 3.3-2.2 6-5.5 6S11 16.5 11 14l-2.2-2.2a1.4 1.4 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <svg viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <p>좌우로 스와이프해 비교해보세요</p>
    </div>
</section>
