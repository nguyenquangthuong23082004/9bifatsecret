<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 09. Key features (주요특장점) -->
<section class="sec features" id="features">

    <!-- 01 -->
    <div class="feature feature--01">
        <div class="sec-head">
            <p class="sec-head__label">주요특장점 01</p>
            <h3 class="sec-head__title">요요 케이스별 <span class="accent">전문기기 완비</span></h3>
            <p class="feature__desc">30여종 전문기기 중 특정부위군살, 셀루라이트, 기초대사저하 등 본인 요요케이스에 맞춰 직접선택가능</p>
        </div>
        <div class="feature__media">
            <img class="feature__bg" src="<?= $img('feature1-bg.webp') ?>" alt="30여종 전문기기" loading="lazy">
            <img class="feature__ticket" src="<?= $img('feature1-ticket.png') ?>" alt="요요방지 애프터케어 1년권" loading="lazy">
        </div>
    </div>

    <!-- 02 & 03 Row -->
    <div class="features__row">
        <!-- 02 -->
        <div class="feature feature--02">
            <div class="thermal">
                <div class="thermal__col thermal__col--before">
                    <img src="<?= $img('thermal-before.png') ?>" alt="관리 전 체온 33.5도" loading="lazy">
                </div>
                <div class="thermal__col thermal__col--after">
                    <img src="<?= $img('thermal-after.png') ?>" alt="관리 후 체온 36.8도" loading="lazy">
                </div>
                <span class="thermal__tag thermal__tag--before">관리전 33.5℃</span>
                <span class="thermal__tag thermal__tag--after">관리후 36.8℃</span>
                <span class="thermal__badge"><small>평균체온</small><b>+3.3℃</b></span>
            </div>
            <div class="sec-head sec-head--left">
                <p class="sec-head__label">주요특장점 02</p>
                <h3 class="sec-head__title">기초대사량 증진기술</h3>
                <p class="feature__desc">체온온도를 36.5℃에 맞춰 체내해독 및 면역시스템 활성화<br>기초대사량을 높여 지방연소에 최적화된 살이 찌지 않는 체질로 개선</p>
            </div>
        </div>

        <!-- 03 -->
        <div class="feature feature--03">
            <div class="feature__media">
                <img src="<?= $img('consult-pt.webp') ?>" alt="1대1 초개인화 생활습관 PT 상담" loading="lazy">
            </div>
            <div class="sec-head sec-head--left">
                <p class="sec-head__label">주요특장점 03</p>
                <h3 class="sec-head__title">1대1 초개인화 생활습관 PT</h3>
                <p class="feature__desc">식습관/생활패턴개선 + 스트레스 및 심리관리</p>
            </div>
        </div>
    </div>
</section>
