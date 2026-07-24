<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/agency') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <button type="button" onclick="send_it()" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> <?= !empty($item['a_idx']) ? '수정' : '등록' ?>
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-shop me-1"></i> 대리점 <?= !empty($item['a_idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/agency/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="a_idx" value="<?= $item['a_idx'] ?? '' ?>">
            <input type="hidden" name="sido" id="sido" value="<?= esc($item['sido'] ?? '') ?>">
            <input type="hidden" name="gugun" id="gugun" value="<?= esc($item['gugun'] ?? '') ?>">
            <input type="hidden" name="dong" id="dong" value="<?= esc($item['dong'] ?? '') ?>">
            <input type="hidden" name="zip" id="zip" value="<?= esc($item['zip'] ?? '') ?>">
            <input type="hidden" name="lat" id="lat" value="<?= esc($item['lat'] ?? '') ?>">
            <input type="hidden" name="lng" id="lng" value="<?= esc($item['lng'] ?? '') ?>">

            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-bold text-danger">대리점명</label>
                    <input type="text" name="agency_name" value="<?= esc($item['agency_name'] ?? '') ?>" class="form-control form-control-lg" required placeholder="대리점명을 입력하세요">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">연락처</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone" value="<?= esc($item['phone'] ?? '') ?>" class="form-control" placeholder="00-000-0000">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">팩스</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-printer"></i></span>
                        <input type="text" name="fax" value="<?= esc($item['fax'] ?? '') ?>" class="form-control" placeholder="00-000-0000">
                    </div>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">영업시간</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                        <input type="text" name="open_time" value="<?= esc($item['open_time'] ?? '') ?>" class="form-control" placeholder="예: 평일 09:00 ~ 22:00">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">우선순위</label>
                    <input type="number" name="onum" value="<?= esc($item['onum'] ?? '0') ?>" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">실평수</label>
                    <div class="input-group">
                        <input type="text" name="py_size" value="<?= esc($item['py_size'] ?? '') ?>" class="form-control" placeholder="00">
                        <span class="input-group-text">평</span>
                    </div>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">제공 서비스/타입</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="opt_1" id="opt_1" value="Y" <?= ($item['opt_1'] ?? '') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="opt_1">스테이크 타입</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="opt_2" id="opt_2" value="Y" <?= ($item['opt_2'] ?? '') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="opt_2">샐러드 타입</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="opt_3" id="opt_3" value="Y" <?= ($item['opt_3'] ?? '') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="opt_3">테이크아웃 타입</label>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">대리점 소개/내용</label>
                    <textarea name="contents" id="_contents" class="summernote"><?= esc($item['contents'] ?? '') ?></textarea>
                </div>

                <div class="col-12 border-top pt-4">
                    <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-geo-alt me-1"></i> 위치 정보</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <button type="button" onclick="execDaumPostcode()" class="btn btn-outline-primary btn-sm mb-2">
                                <i class="bi bi-search"></i> 주소 검색
                            </button>
                            <input type="text" name="addr1" id="addr1" value="<?= esc($item['addr1'] ?? '') ?>" class="form-control mb-2" placeholder="기본 주소" readonly>
                            <input type="text" name="addr2" id="addr2" value="<?= esc($item['addr2'] ?? '') ?>" class="form-control" placeholder="상세 주소를 입력하세요">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">네이버/다음 지도 스크립트 (IFRAME/JS)</label>
                            <textarea name="map" class="form-control" style="height:120px" placeholder="지도 공유 코드를 붙여넣으세요"><?= esc($item['map'] ?? '') ?></textarea>
                        </div>
                        <?php if (!empty($item['map'])): ?>
                        <div class="col-12">
                            <label class="form-label fw-bold small">지도 미리보기</label>
                            <div class="p-2 border rounded bg-white shadow-sm overflow-hidden" style="max-width: 100%;">
                                <?= $item['map'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-2 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/agency') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="button" onclick="send_it()" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= !empty($item['a_idx']) ? '수정하기' : '등록하기' ?>
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
        height: 300,
        lang: 'ko-KR',
        placeholder: '대리점 소개 내용을 입력해주세요.',
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
});

function execDaumPostcode() {
    new daum.Postcode({
        oncomplete: function(data) {
            var fullRoadAddr = data.roadAddress;
            var extraRoadAddr = '';
            if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){ extraRoadAddr += data.bname; }
            if(data.buildingName !== '' && data.apartment === 'Y'){ extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName); }
            if(extraRoadAddr !== ''){ extraRoadAddr = ' (' + extraRoadAddr + ')'; }
            if(fullRoadAddr !== ''){ fullRoadAddr += extraRoadAddr; }

            document.getElementById("zip").value = data.zonecode;
            document.getElementById("addr1").value = fullRoadAddr;
            document.getElementById("addr2").focus();
            document.getElementById("sido").value = data.sido;
            document.getElementById("gugun").value = data.sigungu;
            document.getElementById("dong").value = data.bname;
        }
    }).open();
}

function send_it() {
    var frm = document.frm;
    if (frm.agency_name.value == "") { alert("매장명을 입력하셔야 합니다."); frm.agency_name.focus(); return; }
    if (frm.addr1.value == "") { alert("주소를 입력 선택하셔야 합니다."); return; }
    
    if ($('#_contents').summernote('isEmpty')) {
        // Optional: alert("내용을 입력해주세요.");
    }
    
    frm.submit();
}

function del_it() {
    if(confirm("삭제 후 복구하실 수 없습니다. \n\n삭제하시겠습니까?")) {
        location.href = "<?= base_url('AdmMaster/agency/delete/'.$item['a_idx']) ?>";
    }
}
</script>

<?= $this->endSection() ?>

