<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/line_card') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 목록
        </a>
        <?php if (!empty($item['bbs_idx'])): ?>
            <button type="button" onclick="send_it();" class="btn btn-primary btn-sm">
                <i class="bi bi-check-lg"></i> 수정
            </button>
            <button type="button" onclick="del_chk('<?= $item['bbs_idx'] ?>');" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> 삭제
            </button>
        <?php else: ?>
            <button type="button" onclick="send_it();" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i> 등록
            </button>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-pencil-square me-1"></i> Line Card <?= !empty($item['bbs_idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/bbs/'.$code.'/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="bbs_idx" value="<?= $item['bbs_idx'] ?? '' ?>">
            <input type="hidden" name="code" value="<?= $code ?>">

            <div class="row g-3">
                
                <!-- 파트너명 -->
                <div class="col-12">
                    <label class="form-label fw-bold text-danger">파트너명</label>
                    <input type="text" name="subject" value="<?= esc($item['subject'] ?? '') ?>" class="form-control form-control-lg" placeholder="예: Formosa" required />
                </div>

                <!-- 설명 -->
                <div class="col-12">
                    <label class="form-label fw-bold">설명</label>
                    <input type="text" name="contents" value="<?= esc($item['contents'] ?? '') ?>" class="form-control" placeholder="예: TVS, Automotive Grade TVS..." />
                </div>

                <!-- URL -->
                <div class="col-12">
                    <label class="form-label fw-bold">웹사이트 링크 (URL)</label>
                    <input type="text" name="url" value="<?= esc($item['url'] ?? '') ?>" class="form-control" placeholder="https://..." />
                </div>

                <!-- 설정 옵션 -->
                <div class="col-md-6 d-flex align-items-end mb-3">
                    <div class="p-2 border rounded bg-light w-100">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="notice_yn" id="notice_yn" value="Y" <?= ($item['notice_yn'] ?? '') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="notice_yn">새창에서 열기 (target="_blank")</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="secure_yn" id="secure_yn" value="Y" <?= ($item['secure_yn'] ?? '') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label text-danger" for="secure_yn">숨김 (프론트엔드 비노출)</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">순서 (onum)</label>
                    <input type="number" name="onum" value="<?= esc($item['onum'] ?? '0') ?>" class="form-control" />
                    <small class="text-muted">숫자가 클수록 먼저 노출됩니다.</small>
                </div>

                <!-- 파일 첨부 -->
                <div class="col-12 border-top pt-4 mt-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-image me-1"></i> 로고 이미지 첨부</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">로고 파일</label>
                            <input type="file" name="ufile1" class="form-control" accept="image/*" />
                            <?php if (!empty($item['ufile1'])): ?>
                                <div class="mt-2 p-2 border rounded bg-light">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="del_1" id="del_1" value="Y">
                                        <label class="form-check-label text-danger small" for="del_1">삭제</label>
                                    </div>
                                    <a href="<?= base_url('data/bbs/'.$item['ufile1']) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-image"></i> <?= esc($item['rfile1']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="form-text text-muted smaller mt-1">
                                권장: 투명 배경(PNG) 형태의 로고
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-4 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/line_card') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="button" onclick="send_it();" class="btn btn-primary px-5 py-2 fw-bold">
                        <i class="bi bi-save me-1"></i> <?= !empty($item['bbs_idx']) ? '수정하기' : '등록하기' ?>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
$(function() {
    $("#frm").ajaxForm({
        success: function(response) {
            if (response.trim() == "OK") {
                <?php if (empty($item['bbs_idx'])): ?>
                    alert_("정상적으로 등록되었습니다.");
                    setTimeout(function() {
                        location.href = "<?= base_url('AdmMaster/line_card') ?>";
                    }, 1000);
                <?php else: ?>
                    alert_("정상적으로 수정되었습니다.");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                <?php endif; ?>
            } else {
                alert(response);
            }
        },
        error: function() {
            alert("오류가 발생하였습니다.");
        }
    });
});

function send_it() {
    var frm = document.frm;
    if (frm.subject.value == "") { alert("파트너명을 입력해주세요."); frm.subject.focus(); return; }
    $("#frm").submit();
}

function del_chk(idx) {
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/'.$code.'/delete/') ?>" + idx,
            success: function() {
                alert_("정상적으로 삭제되었습니다.");
                setTimeout(function() {
                    location.href = "<?= base_url('AdmMaster/line_card') ?>";
                }, 1000);
            }
        });
    }
}
</script>

<?= $this->endSection() ?>

