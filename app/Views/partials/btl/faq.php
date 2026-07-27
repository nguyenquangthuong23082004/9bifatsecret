<?php
/* 14. FAQ (자주 묻는 질문) – Figma 529:143.
   Only the "비용은 얼마인가요?" answer exists in the design; the other
   answers are placeholders — replace when the copy is provided. */
$faqs = [
    ['예약 전에 궁금하신 점을 확인해보세요.', ''],
    ['비용은 얼마인가요?', '프로그램/목표감량kg/관리기간 등에 따라 달라집니다. 1대1 맞춤별 상담을 통해 상세 안내드리며, 비티엘은 10년적 가격 그대로 거품없이 필요한 만큼만 제안 드립니다. 큰 패키지보단 필요할 때 원하는 만큼 1회부터 시작하실 수 있도록 부담없이 구성한 단기프로그램도 다양하게 마련되었으며, 비티엘은 장기등록권유나 방문없이 소진을 하는 등의 행위를 하지 않습니다.'],
    ['통증이나 러닝타임이 어느 정도인가요?', ''],
    ['식단조절이나 운동이 필요한가요?', ''],
    ['갱년기 50대 이상 중년도 다이어트가 가능할까요?', ''],
    ['요요, 감량 후 얼굴처짐 등이 걱정돼요.', ''],
];
$open = 1; // 비용은 얼마인가요? open by default (matches Figma)
?>
<!-- 14. FAQ (자주 묻는 질문) -->
<section class="sec faq" id="faq">
    <div class="sec-head faq__head">
        <p class="sec-head__label">예약 전에 궁금하신 점을 확인해보세요.</p>
        <h2 class="sec-head__title">자주 묻는 질문</h2>
    </div>

    <ul class="faq__list">
        <?php foreach ($faqs as $i => [$q, $a]): ?>
        <li class="faq__item<?= $i === $open ? ' is-open' : '' ?>">
            <button type="button" class="faq__q" aria-expanded="<?= $i === $open ? 'true' : 'false' ?>">
                <span class="faq__mark" aria-hidden="true">Q</span>
                <span class="faq__label"><?= esc($q) ?></span>
                <span class="faq__toggle" aria-hidden="true"></span>
            </button>
            <?php if ($a !== ''): ?>
            <div class="faq__panel"><p><?= esc($a) ?></p></div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
