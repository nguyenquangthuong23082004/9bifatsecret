<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/popups') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <button type="button" onclick="send_it();" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> <?= !empty($item['idx']) ? '수정' : '등록' ?>
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-pencil-square me-1"></i> 팝업창 <?= !empty($item['idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm1" id="frm" action="<?= base_url('AdmMaster/popups/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="idx" value="<?= $item['idx'] ?? '' ?>">

            <div class="alert alert-light border mb-4 small">
                <i class="bi bi-info-circle me-1"></i> 시작일과 종료일 밤 12시를 기준으로 자동 노출/비노출 처리가 되며, 분단위로 시간을 조정하실 수 있습니다.
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">언어 구분</label>
                    <select name="P_TYPES" id="P_TYPES" class="form-select">
                        <option value="kr" <?= ($item['P_TYPES'] ?? '') == 'kr' ? 'selected' : '' ?>>국문 (KR)</option>
                        <option value="en" <?= ($item['P_TYPES'] ?? '') == 'en' ? 'selected' : '' ?>>영문 (EN)</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold text-danger">팝업창 제목</label>
                    <input type="text" name="P_SUBJECT" value="<?= esc($item['P_SUBJECT'] ?? '') ?>" class="form-control" required placeholder="관리용 제목을 입력하세요">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">팝업 노출 기간</label>
                    <div class="p-3 border rounded bg-light">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" name="P_STARTDAY" value="<?= esc($item['P_STARTDAY'] ?? '') ?>" class="form-control datepicker" style="width:130px" placeholder="시작일">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" name="P_START_HH" value="<?= esc($item['P_START_HH'] ?? '00') ?>" class="form-control text-center" style="width:60px" maxlength="2">
                                    <span class="input-group-text">:</span>
                                    <input type="text" name="P_START_MM" value="<?= esc($item['P_START_MM'] ?? '00') ?>" class="form-control text-center" style="width:60px" maxlength="2">
                                </div>
                            </div>
                            <div class="col-auto px-2">~</div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                    <input type="text" name="P_ENDDAY" value="<?= esc($item['P_ENDDAY'] ?? '') ?>" class="form-control datepicker" style="width:130px" placeholder="종료일">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" name="P_END_HH" value="<?= esc($item['P_END_HH'] ?? '23') ?>" class="form-control text-center" style="width:60px" maxlength="2">
                                    <span class="input-group-text">:</span>
                                    <input type="text" name="P_END_MM" value="<?= esc($item['P_END_MM'] ?? '59') ?>" class="form-control text-center" style="width:60px" maxlength="2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">노출 상태</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_a" value="A" <?= ($item['status'] ?? 'A') == 'A' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_a">일정별 자동노출</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_b" value="B" <?= ($item['status'] ?? '') == 'B' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_b">강제노출</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_c" value="C" <?= ($item['status'] ?? '') == 'C' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_c">강제 비노출</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">노출 방식</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="P_CATE" id="P_CATE_l" type="radio" value="L" <?= ($item['P_CATE'] ?? 'L') == 'L' ? 'checked' : '' ?> onclick="chk_it('L')" />
                            <label class="form-check-label" for="P_CATE_l">레이어형</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="P_CATE" id="P_CATE_p" type="radio" value="P" <?= ($item['P_CATE'] ?? '') == 'P' ? 'checked' : '' ?> onclick="chk_it('P')" />
                            <label class="form-check-label" for="P_CATE_p">윈도우형</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 styleL">
                    <label class="form-label fw-bold">팝업 사이즈 (가로 X 세로)</label>
                    <div class="input-group">
                        <input type="number" name="P_WIN_WIDTH" value="<?= esc($item['P_WIN_WIDTH'] ?? '500') ?>" class="form-control text-center">
                        <span class="input-group-text">px X</span>
                        <input type="number" name="P_WIN_HEIGHT" value="<?= esc($item['P_WIN_HEIGHT'] ?? '500') ?>" class="form-control text-center">
                        <span class="input-group-text">px</span>
                    </div>
                </div>

                <div class="col-md-6 styleL">
                    <label class="form-label fw-bold">팝업창 노출 위치 (좌 X 상)</label>
                    <div class="input-group">
                        <input type="number" name="P_WIN_LEFT" value="<?= esc($item['P_WIN_LEFT'] ?? '0') ?>" class="form-control text-center">
                        <span class="input-group-text">px X</span>
                        <input type="number" name="P_WIN_TOP" value="<?= esc($item['P_WIN_TOP'] ?? '0') ?>" class="form-control text-center">
                        <span class="input-group-text">px</span>
                    </div>
                </div>

                <div class="col-12 styleL">
                    <label class="form-label fw-bold">팝업 내용</label>
                    <textarea name="P_CONTENT" id="P_CONTENT" class="summernote"><?= esc($item['P_CONTENT'] ?? '') ?></textarea>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-2 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/popups') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="button" onclick="send_it();" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= !empty($item['idx']) ? '수정하기' : '등록하기' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function() {
    // Summernote Init
    $('.summernote').summernote({
        height: 400,
        lang: 'ko-KR',
        placeholder: '팝업 내용을 입력해주세요.',
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

    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    
    chk_it("<?= $item['P_CATE'] ?? 'L' ?>");
});

function chk_it(str) {
    if (str == "T") {
        $(".styleL").hide();
    } else {
        $(".styleL").show();
    }
}

function send_it() {
    var frm = document.frm1;
    if (frm.P_SUBJECT.value == "") {
        alert("제목을 입력해주세요.");
        frm.P_SUBJECT.focus();
        return;
    }
    if (frm.P_STARTDAY.value == "") {
        alert("시작일을 선택해주세요.");
        frm.P_STARTDAY.focus();
        return;
    }
    if (frm.P_ENDDAY.value == "") {
        alert("종료일을 선택해주세요.");
        frm.P_ENDDAY.focus();
        return;
    }

    if ($('#P_CONTENT').summernote('isEmpty')) {
        alert("내용을 입력해주세요.");
        return;
    }

    frm.submit();
}

function del_chk(idx) {
    if (confirm("삭제 하시겠습니까?\n삭제후에는 복구가 불가능합니다.")) {
        location.href = "<?= base_url('AdmMaster/popups/delete/') ?>" + idx;
    }
}
</script>

<?= $this->endSection() ?>

