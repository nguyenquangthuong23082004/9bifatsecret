<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/goods') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <button type="button" onclick="send_it()" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> <?= !empty($item['idx']) ? '수정' : '등록' ?>
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-box-seam me-1"></i> 상품 <?= !empty($item['idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/goods/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="idx" value="<?= $item['idx'] ?? '' ?>">

            <div class="row g-4">
                <!-- 카테고리 -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">카테고리 분류</label>
                    <div class="input-group">
                        <select id="product_code_1" name="product_code_1" class="form-select border-primary" onchange="get_code(this.value, 2)">
                            <option value="">1차분류 선택</option>
                            <?php foreach ($categories1 as $cat): ?>
                                <option value="<?= $cat['code_no'] ?>" <?= ($item['product_code_1'] ?? '') == $cat['code_no'] ? 'selected' : '' ?>><?= esc($cat['code_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="product_code_2" name="product_code_2" class="form-select border-primary">
                            <option value="">2차분류 선택</option>
                            <?php foreach ($categories2 as $cat): ?>
                                <option value="<?= $cat['code_no'] ?>" <?= ($item['product_code_2'] ?? '') == $cat['code_no'] ? 'selected' : '' ?>><?= esc($cat['code_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 사용여부 -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">사용 유무</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="useYN" id="useY" value="Y" <?= ($item['useYN'] ?? 'Y') == 'Y' ? 'checked' : '' ?> />
                            <label class="form-check-label" for="useY">사용함 (Open)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="useYN" id="useN" value="N" <?= ($item['useYN'] ?? '') == 'N' ? 'checked' : '' ?> />
                            <label class="form-check-label" for="useN">사용안함 (Hidden)</label>
                        </div>
                    </div>
                </div>

                <!-- 제품명 -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">제품명 (국문)</label>
                    <input type="text" name="goods_name_ko" value="<?= esc($item['goods_name_ko'] ?? '') ?>" class="form-control" required placeholder="한글 제품명을 입력하세요">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">제품명 (영문)</label>
                    <input type="text" name="goods_name_en" value="<?= esc($item['goods_name_en'] ?? '') ?>" class="form-control" placeholder="English product name">
                </div>

                <!-- 한줄설명 -->
                <div class="col-md-6">
                    <label class="form-label fw-bold small">한줄설명 (국문)</label>
                    <input type="text" name="oneinfo_ko" value="<?= esc($item['oneinfo_ko'] ?? '') ?>" class="form-control" placeholder="국문 간략 설명을 입력하세요">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">한줄설명 (영문)</label>
                    <input type="text" name="oneinfo_en" value="<?= esc($item['oneinfo_en'] ?? '') ?>" class="form-control" placeholder="English brief info">
                </div>

                <!-- 이미지 업로드 -->
                <div class="col-12 border-top pt-4">
                    <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-images me-1"></i> 상품 이미지 (475 * 315 권장)</h6>
                    <div class="row g-3">
                        <?php for($i=1; $i<=6; $i++): ?>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">이미지 <?= $i ?></label>
                                <input type="file" name="ufile<?= $i ?>" class="form-control form-control-sm" accept="image/*">
                                <?php if (!empty($item['ufile'.$i])): ?>
                                    <div class="mt-2 p-2 border rounded bg-light position-relative">
                                        <img src="<?= base_url('data/goods/'.$item['ufile'.$i]) ?>" class="img-fluid rounded shadow-sm mb-1" style="max-height: 100px;">
                                        <div class="form-check position-absolute top-0 end-0 m-2">
                                            <input class="form-check-input" type="checkbox" name="del_<?= $i ?>" id="del_<?= $i ?>" value="Y">
                                            <label class="form-check-label text-danger small bg-white px-1 rounded" for="del_<?= $i ?>">삭제</label>
                                        </div>
                                        <div class="small text-truncate" title="<?= esc($item['rfile'.$i]) ?>"><?= esc($item['rfile'.$i]) ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- 상세 정보 에디터 섹션 -->
                <div class="col-12 border-top pt-4 mt-5">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-ko-tab" data-bs-toggle="pill" data-bs-target="#pills-ko" type="button" role="tab">국문 (KO)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-en-tab" data-bs-toggle="pill" data-bs-target="#pills-en" type="button" role="tab">영문 (EN)</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- 국문 탭 -->
                        <div class="tab-pane fade show active" id="pills-ko" role="tabpanel">
                            <?php 
                            $ko_fields = [
                                ['name' => 'info1_ko', 'title' => '기본정보', 'tag' => 'inputTag1'],
                                ['name' => 'info2_ko', 'title' => '상세정보', 'tag' => 'inputTag3'],
                                ['name' => 'info3_ko', 'title' => '특성/용도', 'tag' => 'inputTag5'],
                            ];
                            foreach ($ko_fields as $field): 
                            ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0"><?= $field['title'] ?></label>
                                    <button type="button" onclick="applyTemplate('<?= $field['name'] ?>', '<?= $field['tag'] ?>')" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-layout-text-window-reverse"></i> 템플릿 적용
                                    </button>
                                </div>
                                <textarea name="<?= $field['name'] ?>" id="<?= $field['name'] ?>" class="summernote"><?= esc($item[$field['name']] ?? '') ?></textarea>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- 영문 탭 -->
                        <div class="tab-pane fade" id="pills-en" role="tabpanel">
                            <?php 
                            $en_fields = [
                                ['name' => 'info1_en', 'title' => 'Basic Info', 'tag' => 'inputTag2'],
                                ['name' => 'info2_en', 'title' => 'Detail Info', 'tag' => 'inputTag4'],
                                ['name' => 'info3_en', 'title' => 'Spec/Usage', 'tag' => 'inputTag6'],
                            ];
                            foreach ($en_fields as $field): 
                            ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0"><?= $field['title'] ?></label>
                                    <button type="button" onclick="applyTemplate('<?= $field['name'] ?>', '<?= $field['tag'] ?>')" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-layout-text-window-reverse"></i> Apply Template
                                    </button>
                                </div>
                                <textarea name="<?= $field['name'] ?>" id="<?= $field['name'] ?>" class="summernote"><?= esc($item[$field['name']] ?? '') ?></textarea>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-2 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/goods') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="button" onclick="send_it()" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= !empty($item['idx']) ? '수정하기' : '등록하기' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function() {
    $('.summernote').summernote({
        height: 250,
        lang: 'ko-KR',
        placeholder: '내용을 입력해주세요.',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});

function get_code(strs, depth) {
    $.ajax({
        type: "GET",
        url: "<?= base_url('AdmMaster/goods/get_code') ?>",
        data: { parent_code_no: strs, depth: depth },
        success: function(list) {
            $("#product_code_2").empty().append("<option value=''>2차분류 선택</option>");
            $.each(list, function(i, item) {
                var statusTxt = item.status == 'C' ? '[마감]' : (item.status == 'N' ? '[삭제]' : '');
                $("#product_code_2").append("<option value='" + item.code_no + "'>" + item.code_name + " " + statusTxt + "</option>");
            });
        }
    });
}

function applyTemplate(editorId, tagId) {
    var html = $("#" + tagId).html();
    $("#" + editorId).summernote('pasteHTML', html);
}

function send_it() {
    var frm = document.frm;
    if (frm.product_code_1.value == "") { alert("1차분류를 선택하셔야 합니다."); frm.product_code_1.focus(); return; }
    if (frm.product_code_2.value == "") { alert("2차분류를 선택하셔야 합니다."); frm.product_code_2.focus(); return; }
    if (frm.goods_name_ko.value == "") { alert("제품명(국문)을 입력하셔야 합니다."); frm.goods_name_ko.focus(); return; }
    
    frm.submit();
}

function del_it() {
    if(confirm("삭제 후 복구하실 수 없습니다. \n\n삭제하시겠습니까?")) {
        location.href = "<?= base_url('AdmMaster/goods/delete/'.($item['idx'] ?? '')) ?>";
    }
}
</script>

<?= $this->endSection() ?>

