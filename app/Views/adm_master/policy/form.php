<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <button type="button" onclick="send_it();" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> 전체 저장
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div id="ajax-alert" class="alert alert-success alert-dismissible fade show shadow-sm mb-4 d-none" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <span id="ajax-alert-message">성공적으로 저장되었습니다.</span>
    <button type="button" class="btn-close" onclick="$('#ajax-alert').addClass('d-none');" aria-label="Close"></button>
</div>

<form name="frm" id="frm" method="post" action="<?= base_url('AdmMaster/bbs/policy/save') ?>">
    <input type="hidden" name="terms_idx" value="<?= esc($terms['bbs_idx'] ?? '') ?>" />
    <input type="hidden" name="privacy_idx" value="<?= esc($privacy['bbs_idx'] ?? '') ?>" />

    <!-- 탭 네비게이션 -->
    <ul class="nav nav-tabs mb-4" id="policyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold px-3" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms-pane" type="button" role="tab" aria-controls="terms-pane" aria-selected="true">
                <i class="bi bi-file-earmark-text me-1"></i> 이용약관
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold px-3" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy-pane" type="button" role="tab" aria-controls="privacy-pane" aria-selected="false">
                <i class="bi bi-shield-check me-1"></i> 개인정보처리방침
            </button>
        </li>
    </ul>

    <!-- 탭 컨텐츠 -->
    <div class="tab-content card shadow-sm" id="policyTabsContent">
        <!-- 이용약관 탭 -->
        <div class="tab-pane fade show active card-body p-4" id="terms-pane" role="tabpanel" aria-labelledby="terms-tab" tabindex="0">
            <div class="mb-3">
                <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-info-circle me-1"></i> 이용약관 내용 편집</h5>
                <textarea name="terms_content" id="terms_content_" class="summernote"><?= esc($terms['contents'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- 개인정보처리방침 탭 -->
        <div class="tab-pane fade card-body p-4" id="privacy-pane" role="tabpanel" aria-labelledby="privacy-tab" tabindex="0">
            <div class="mb-3">
                <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-lock me-1"></i> 개인정보처리방침 내용 편집</h5>
                <textarea name="privacy_content" id="privacy_content_" class="summernote"><?= esc($privacy['contents'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- 하단 저장 버튼 -->
    <div class="d-flex justify-content-center my-4 pb-5">
        <button type="button" onclick="send_it();" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
            <i class="bi bi-save me-2"></i> 설정 저장하기
        </button>
    </div>
</form>

<script>
$(function() {
    // Summernote Init
    $('.summernote').summernote({
        height: 500,
        lang: 'ko-KR',
        placeholder: '내용을 입력해주세요.',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // AJAX Form Init
    $("#frm").ajaxForm({
        dataType: 'json',
        success: function(response) {
            if (response.status === 'OK') {
                $('#ajax-alert-message').text(response.message);
                $('#ajax-alert').removeClass('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(function() {
                    $('#ajax-alert').addClass('d-none');
                }, 3000);
            } else {
                alert(response.message || "오류가 발생하였습니다.");
            }
        },
        error: function() {
            alert("오류가 발생하였습니다.");
        }
    });
});

function send_it() {
    if ($('#terms_content_').summernote('isEmpty')) {
        alert("이용약관 내용을 입력해주세요.");
        // Switch to terms tab if not active
        var triggerEl = document.querySelector('#terms-tab');
        bootstrap.Tab.getInstance(triggerEl).show();
        return;
    }
    if ($('#privacy_content_').summernote('isEmpty')) {
        alert("개인정보처리방침 내용을 입력해주세요.");
        // Switch to privacy tab if not active
        var triggerEl = document.querySelector('#privacy-tab');
        bootstrap.Tab.getInstance(triggerEl).show();
        return;
    }

    $("#frm").submit();
}
</script>

<?= $this->endSection() ?>
