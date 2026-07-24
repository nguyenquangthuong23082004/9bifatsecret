<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/category?s_parent_code_no='.$s_parent_code_no) ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <button type="button" onclick="send_it()" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> <?= !empty($item['code_idx']) ? '수정' : '등록' ?>
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-pencil-square me-1"></i> 카테고리 <?= !empty($item['code_idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/category/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="code_idx" value="<?= $item['code_idx'] ?? '' ?>">
            <input type="hidden" name="code_no" value="<?= $code_no ?>">
            <input type="hidden" name="depth" value="<?= $depth ?>">
            <input type="hidden" name="parent_code_no" value="<?= $parent_code_no ?>">
            <input type="hidden" name="s_parent_code_no" value="<?= $s_parent_code_no ?>">

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">코드번호</label>
                    <input type="text" class="form-control bg-light" value="<?= $code_no ?>" readonly>
                </div>

                <?php if ($parent_code_no == "0" && empty($item['code_idx'])): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">코드구분 (영문만)</label>
                    <input type="text" id="code_gubun" name="code_gubun" value="<?= esc($code_gubun) ?>" class="form-control" style="ime-mode:disabled" />
                </div>
                <?php else: ?>
                    <input type="hidden" id="code_gubun" name="code_gubun" value="<?= esc($code_gubun) ?>" />
                <?php endif; ?>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">코드명 (국문)</label>
                    <input type="text" id="code_name" name="code_name" value="<?= esc($item['code_name'] ?? '') ?>" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">코드명 (영문)</label>
                    <input type="text" id="code_name_en" name="code_name_en" value="<?= esc($item['code_name_en'] ?? '') ?>" class="form-control" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">현황</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusY" value="Y" <?= ($item['status'] ?? 'Y') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusY">사용</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusC" value="C" <?= ($item['status'] ?? '') == 'C' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusC">마감</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusN" value="N" <?= ($item['status'] ?? '') == 'N' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusN">삭제</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">우선순위</label>
                    <div class="input-group">
                        <input type="number" id="onum" name="onum" value="<?= esc($item['onum'] ?? '0') ?>" class="form-control" />
                        <span class="input-group-text small text-muted">숫자가 높을수록 상위에 노출됩니다.</span>
                    </div>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-2 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/category?s_parent_code_no='.$s_parent_code_no) ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="button" onclick="send_it()" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= !empty($item['code_idx']) ? '수정하기' : '등록하기' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function send_it() {
    var frm = document.frm;
    if (frm.code_name.value == "") {
        alert("코드명(국문)을 입력하셔야 합니다.");
        frm.code_name.focus();
        return;
    }
    if (frm.code_name_en.value == "") {
        alert("코드명(영문)을 입력하셔야 합니다.");
        frm.code_name_en.focus();
        return;
    }
    frm.submit();
}
</script>

<?= $this->endSection() ?>

