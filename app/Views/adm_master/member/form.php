<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
<div class="d-flex gap-2">
    <a href="<?= base_url('AdmMaster/member') ?>" class="btn btn-secondary btn-sm">
        <i class="bi bi-list"></i> 목록
    </a>
    <button type="button" onclick="send_its()" class="btn btn-primary btn-sm">
        <i class="bi bi-save"></i> 저장
    </button>
    <?php if (!empty($item)): ?>
        <a href="<?= base_url('AdmMaster/member/delete/' . $item['m_idx']) ?>" class="btn btn-danger btn-sm"
            onclick="return confirm('정말 삭제하시겠습니까?');">
            <i class="bi bi-trash"></i> 삭제
        </a>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-person-lines-fill me-1"></i> 회원 <?= !empty($item) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/member/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="m_idx" value="<?= esc($item['m_idx'] ?? '') ?>">

            <div class="row g-4 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">아이디 <span class="text-danger">*</span></label>
                    <input type="text" name="user_id" value="<?= esc($item['user_id'] ?? '') ?>" class="form-control"
                        <?= !empty($item) ? 'readonly' : '' ?> 
                        placeholder="<?= !empty($item) ? '아이디는 수정할 수 없습니다' : '영문/숫자 입력' ?>" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">사번 <span class="text-danger">*</span></label>
                    <input type="text" name="saban" value="<?= esc($item['saban'] ?? '') ?>" class="form-control"
                        placeholder="사번을 입력하세요">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">이름 <span class="text-danger">*</span></label>
                    <input type="text" name="user_name" value="<?= esc($item['user_name'] ?? '') ?>"
                        class="form-control" placeholder="이름을 입력하세요">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">비밀번호
                        <?= empty($item) ? '<span class="text-danger">*</span>' : '' ?></label>
                    <input type="password" name="user_pw" value="" class="form-control"
                        placeholder="<?= !empty($item) ? '변경시에만 입력하세요' : '비밀번호를 입력하세요' ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">권한 (레벨)</label>
                    <?php if (!empty($item) && $item['user_level'] == 100): ?>
                        <input type="text" class="form-control bg-light" value="관리자(100)" readonly>
                        <input type="hidden" name="user_level" value="100">
                        <div class="form-text text-muted small">* 최고 관리자 권한은 변경할 수 없습니다.</div>
                    <?php else: ?>
                        <select name="user_level" class="form-select">
                            <option value="3" <?= ($item['user_level'] ?? 15) == 3 ? 'selected' : '' ?>>레벨(3)</option>
                            <option value="5" <?= ($item['user_level'] ?? 15) == 5 ? 'selected' : '' ?>>레벨(5)</option>
                            <option value="10" <?= ($item['user_level'] ?? 15) == 10 ? 'selected' : '' ?>>레벨(10)</option>
                            <option value="15" <?= ($item['user_level'] ?? 15) == 15 ? 'selected' : '' ?>>레벨(15)</option>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">연락처</label>
                    <input type="text" name="mobile" value="<?= esc($item['mobile'] ?? '') ?>" class="form-control"
                        placeholder="010-0000-0000">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">이메일</label>
                    <input type="email" name="user_email" value="<?= esc($item['user_email'] ?? '') ?>"
                        class="form-control" placeholder="example@email.com">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">주소</label>
                    <div class="input-group mb-2" style="max-width: 300px;">
                        <input type="text" name="zip" id="zip" value="<?= esc($item['zip'] ?? '') ?>"
                            class="form-control" placeholder="우편번호" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="execDaumPostcode()">우편번호
                            찾기</button>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="addr1" id="addr1" value="<?= esc($item['addr1'] ?? '') ?>"
                            class="form-control" placeholder="기본 주소" readonly>
                    </div>
                    <div>
                        <input type="text" name="addr2" id="addr2" value="<?= esc($item['addr2'] ?? '') ?>"
                            class="form-control" placeholder="상세 주소">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">상태</label>
                    <?php if (!empty($item) && $item['user_level'] == 100): ?>
                        <input type="text" class="form-control bg-light"
                            value="<?= ($item['status'] ?? 'Y') == 'Y' ? '사용 (정상로그인)' : '중지 (로그인불가)' ?>" readonly>
                        <input type="hidden" name="status" value="<?= esc($item['status'] ?? 'Y') ?>">
                        <div class="form-text text-muted small">* 최고 관리자의 상태는 변경할 수 없습니다.</div>
                    <?php else: ?>
                        <select name="status" class="form-select">
                            <option value="Y" <?= ($item['status'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>사용 (정상로그인)</option>
                            <option value="N" <?= ($item['status'] ?? 'Y') == 'N' ? 'selected' : '' ?>>중지 (로그인불가)</option>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 border-top pt-4 mt-5 d-flex justify-content-center gap-2">
                <a href="<?= base_url('AdmMaster/member') ?>" class="btn btn-secondary btn-lg px-4">취소</a>
                <button type="button" onclick="send_its()" class="btn btn-primary btn-lg px-5 shadow">
                    <i class="bi bi-check-circle me-1"></i> 저장하기
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Daum Postcode Script -->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function (data) {
                var addr = '';
                var extraAddr = '';

                if (data.userSelectedType === 'R') {
                    addr = data.roadAddress;
                } else {
                    addr = data.jibunAddress;
                }

                document.getElementById('zip').value = data.zonecode;
                document.getElementById("addr1").value = addr;
                document.getElementById("addr2").focus();
            }
        }).open();
    }

    function send_its() {
        var frm = document.frm;

        if (frm.user_id.value.trim() == "") {
            alert("아이디를 입력하셔야 합니다.");
            frm.user_id.focus();
            return;
        }

        if (frm.saban.value.trim() == "") {
            alert("사번을 입력하셔야 합니다.");
            frm.saban.focus();
            return;
        }

        if (frm.user_name.value.trim() == "") {
            alert("이름을 입력하셔야 합니다.");
            frm.user_name.focus();
            return;
        }

        <?php if (empty($item)): ?>
            if (frm.user_pw.value.trim() == "") {
                alert("비밀번호를 입력하셔야 합니다.");
                frm.user_pw.focus();
                return;
            }
        <?php endif; ?>

        frm.submit();
    }

    function formatPhoneNumber(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        let res = '';
        
        if (value.startsWith('02')) {
            if (value.length < 3) res = value;
            else if (value.length < 6) res = value.substr(0, 2) + '-' + value.substr(2);
            else if (value.length < 10) res = value.substr(0, 2) + '-' + value.substr(2, 3) + '-' + value.substr(5);
            else res = value.substr(0, 2) + '-' + value.substr(2, 4) + '-' + value.substr(6, 4);
        } else {
            if (value.length < 4) res = value;
            else if (value.length < 7) res = value.substr(0, 3) + '-' + value.substr(3);
            else if (value.length < 11) res = value.substr(0, 3) + '-' + value.substr(3, 3) + '-' + value.substr(6);
            else res = value.substr(0, 3) + '-' + value.substr(3, 4) + '-' + value.substr(7, 4);
        }
        input.value = res;
    }
</script>

<?= $this->endSection() ?>
