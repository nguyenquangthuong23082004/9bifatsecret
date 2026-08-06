<?php
/* 15. Contact form (문의) – Figma 529:182.
   POSTs to /consult (no controller yet — wire up when the endpoint exists). */
$fields = [
    ['name',   '성함',     'text', '이름을 입력해주세요.'],
    ['age',    '나이',     'text', '출생연도 2자리를 입력해주세요.(예 : 82)'],
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

    <form class="contact__form" action="<?= base_url('consult') ?>" method="post" onsubmit="return validateConsultForm(this)" novalidate>
        <?= csrf_field() ?>
        
        <!-- Honeypot anti-spam field -->
        <div style="display:none !important; position:absolute !important; left:-9999px !important;">
            <label for="c-email_address">이메일 주소</label>
            <input type="text" name="email_address" id="c-email_address" autocomplete="off" tabindex="-1">
        </div>

        <div class="contact__fields">
            <?php foreach ($fields as [$name, $label, $type, $ph]): ?>
            <div class="field">
                <label class="field__label" for="c-<?= $name ?>"><?= esc($label) ?></label>
                <input class="field__input" id="c-<?= $name ?>" type="<?= $type ?>" name="<?= $name ?>"
                       placeholder="<?= esc($ph) ?>"
                       <?php if ($name === 'age'): ?>
                           inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       <?php elseif ($name === 'phone'): ?>
                           oninput="this.value = this.value.replace(/[^0-9\-]/g, '')"
                       <?php endif; ?>
                >
            </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="contact__btn">빠른 상담 신청하기</button>

        <label class="contact__agree">
            <input type="checkbox" name="agree" value="1">
            <span class="contact__agree-box" aria-hidden="true"></span>
            <span class="contact__agree-text">개인정보취급 방침 <a href="javascript:void(0)" onclick="openPrivacyModal()" class="contact__agree-link" style="text-decoration: underline; margin-left: 5px; color: var(--c-rose);">[보기]</a></span>
        </label>
    </form>
</section>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="privacy-modal">
    <div class="privacy-modal__content">
        <div class="privacy-modal__header">
            <h3 class="privacy-modal__title">개인정보처리방침</h3>
            <button type="button" onclick="closePrivacyModal()" class="privacy-modal__close">&times;</button>
        </div>
        <div class="privacy-modal__body" id="privacyModalBody">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="privacy-modal__footer">
            <button type="button" onclick="closePrivacyModal()" class="privacy-modal__btn">닫기</button>
        </div>
    </div>
</div>

<style>
.privacy-modal {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
    padding: 20px;
    box-sizing: border-box;
}
.privacy-modal__content {
    background-color: #ffffff;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    animation: modalFadeIn 0.3s ease-out;
}
@keyframes modalFadeIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.privacy-modal__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    background-color: #fdfafb;
}
.privacy-modal__title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--c-ink);
}
.privacy-modal__close {
    background: none;
    border: none;
    font-size: 24px;
    font-weight: 300;
    cursor: pointer;
    color: var(--c-muted);
    padding: 0;
    line-height: 1;
}
.privacy-modal__close:hover {
    color: var(--c-ink);
}
.privacy-modal__body {
    padding: 20px;
    overflow-y: auto;
    font-size: 14px;
    line-height: 1.6;
    color: var(--c-ink-2);
}
.privacy-modal__footer {
    padding: 12px 20px;
    border-top: 1px solid #f3f4f6;
    display: flex;
    justify-content: flex-end;
    background-color: #fdfafb;
}
.privacy-modal__btn {
    background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-rose) 100%);
    border: none;
    color: #ffffff;
    padding: 8px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(200, 54, 90, 0.15);
    transition: opacity 0.2s;
}
.privacy-modal__btn:hover {
    opacity: 0.9;
}
.privacy-modal-spinner {
    width: 2rem;
    height: 2rem;
    border: 0.22em solid var(--c-rose);
    border-right-color: transparent;
    border-radius: 50%;
    display: inline-block;
    animation: spinner-spin .75s linear infinite;
}
@keyframes spinner-spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
function openPrivacyModal() {
    var modal = document.getElementById('privacyModal');
    var body = document.getElementById('privacyModalBody');
    modal.style.display = 'flex';
    body.innerHTML = `
        <div style="text-align: center; padding: 30px;">
            <div class="privacy-modal-spinner" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    fetch('<?= base_url('privacy-policy-content') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK') {
                body.innerHTML = data.content;
            } else {
                body.innerHTML = '<p style="color: red; text-align: center;">내용을 불러오지 못했습니다. 잠시 후 다시 시도해 주세요.</p>';
            }
        })
        .catch(error => {
            body.innerHTML = '<p style="color: red; text-align: center;">오류가 발생하여 내용을 불러오지 못했습니다.</p>';
        });
}

function closePrivacyModal() {
    var modal = document.getElementById('privacyModal');
    modal.style.display = 'none';
}

function validateConsultForm(form) {
    if (!form.name.value.trim()) {
        alert('성함을 입력해주세요.');
        form.name.focus();
        return false;
    }
    if (!form.age.value.trim()) {
        alert('나이를 입력해주세요.');
        form.age.focus();
        return false;
    }
    if (!form.region.value.trim()) {
        alert('거주지역을 입력해주세요.');
        form.region.focus();
        return false;
    }
    if (!form.phone.value.trim()) {
        alert('연락처를 입력해주세요.');
        form.phone.focus();
        return false;
    }
    if (!form.agree.checked) {
        alert('개인정보취급 방침에 동의하셔야 신청이 가능합니다.');
        form.agree.focus();
        return false;
    }
    return true;
}

window.addEventListener('click', function(event) {
    var modal = document.getElementById('privacyModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
