<?php
/* 14. FAQ (자주 묻는 질문) – Figma 529:143.
   Mapped dynamically to /AdmMaster/bbs/faq database table */
$faqsList = $faqs ?? [];
$open = 0; // The first item (비용은 얼마인가요?) is open by default
?>
<!-- 14. FAQ (자주 묻는 질문) -->
<section class="sec faq" id="faq">
    <div class="sec-head faq__head">
        <p class="sec-head__label">예약 전에 궁금하신 점을 확인해보세요.</p>
        <h2 class="sec-head__title">자주 묻는 질문</h2>
    </div>

    <ul class="faq__list">
        <?php foreach ($faqsList as $i => $item): 
            $q = $item['subject'] ?? '';
            $a = $item['contents'] ?? '';
        ?>
        <li class="faq__item<?= $i === $open ? ' is-open' : '' ?>">
            <button type="button" class="faq__q" aria-expanded="<?= $i === $open ? 'true' : 'false' ?>">
                <span class="faq__mark" aria-hidden="true">Q</span>
                <span class="faq__label"><?= esc($q) ?></span>
                <span class="faq__toggle" aria-hidden="true"></span>
            </button>
            <?php if (!empty($a)): ?>
            <div class="faq__panel">
                <div><?= $a ?></div>
            </div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
