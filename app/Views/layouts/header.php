<header class="gnb">
    <div class="gnb__inner">
        <a class="gnb__logo" href="<?= base_url() ?>" aria-label="비티엘 홈">
            <img src="<?= base_url('assets/images/btl/logo-bitiel.png') ?>" alt="BITIEL">
        </a>
        <button class="gnb__toggle" type="button" aria-label="메뉴 열기" aria-expanded="false" aria-controls="gnbMenu">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" aria-hidden="true">
                <path d="M24 22H6V20H24V22ZM24 16H6V14H24V16ZM24 10H6V8H24V10Z" fill="#252525"/>
            </svg>
        </button>
    </div>
    <nav id="gnbMenu" class="gnb__menu" aria-label="주요 메뉴" hidden>
        <a href="#program">프로그램</a>
        <a href="#dual">듀얼 솔루션</a>
        <a href="#ticket">체험권</a>
        <a href="#features">주요특장점</a>
    </nav>
</header>
