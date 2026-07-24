<?php $img = fn($f) => base_url('assets/images/btl/' . $f); ?>
<!-- 06. Experience ticket (체험권) -->
<section class="sec ticket" id="ticket">
    <div class="ticket__head">
        <p class="ticket__label">19th birthday기념</p>
        <h2 class="ticket__title">놓치면 후회,체험 후<br>결정해보세요!<br>첫 방문 체험가 혜택</h2>
    </div>
    <div class="ticket__visual">
        <img class="ticket-img" src="<?= $img('ticket.png') ?>" alt="전신집중 다이어트 체험권 – 정가 51만원 15만원" loading="lazy">
        <img class="ticket-circle" src="<?= $img('ticket-circle.png') ?>" alt="" aria-hidden="true" loading="lazy">
    </div>
</section>
