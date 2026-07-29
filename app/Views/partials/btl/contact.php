<?php
/* 15. Contact form (문의) – Figma 529:182.
   POSTs to /consult (no controller yet — wire up when the endpoint exists). */
$fields = [
    ['name',   '성함',     'text', '이름을 입력해주세요.'],
    ['age',    '나이',     'text', '출생연도로 입력해주세요.(예 : 82년생)'],
    ['region', '거주지역', 'text', '거주지역을 입력해주세요.(예 : 부산 연제구 연산동)'],
    ['phone',  '연락처',   'tel',  '‘ - ’ 번호만 입력해주세요.'],
];
?>
<!-- 15. Contact form (1:1 무료컨설팅 예약) -->
<section class="sec contact" id="contact">
    <div class="sec-head contact__head">
        <p class="sec-head__label">다이어트는 시작이 반, 인바디 상담부터 받으세요.</p>
        <h2 class="sec-head__title"><span class="line1">1:1 무료컨설팅 예약</span><br><span class="line2">지금 바로 신청하세요!</span></h2>
    </div>

    <form class="contact__form" action="<?= base_url('consult') ?>" method="post">
        <?= csrf_field() ?>
        <div class="contact__fields">
            <?php foreach ($fields as [$name, $label, $type, $ph]): ?>
            <div class="field">
                <label class="field__label" for="c-<?= $name ?>"><?= esc($label) ?></label>
                <input class="field__input" id="c-<?= $name ?>" type="<?= $type ?>" name="<?= $name ?>"
                       placeholder="<?= esc($ph) ?>" required>
            </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="contact__btn">빠른 상담 신청하기</button>

        <label class="contact__agree">
            <input type="checkbox" name="agree" value="1" required>
            <span class="contact__agree-box" aria-hidden="true"></span>
            <span class="contact__agree-text">개인정보취급 방침</span>
        </label>
    </form>
</section>
