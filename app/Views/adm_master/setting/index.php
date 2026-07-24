<?php
$setting = $setting ?? [];
?>
<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
<div class="d-flex gap-2">
    <button type="button" onclick="send_its()" class="btn btn-primary btn-sm">
        <i class="bi bi-save"></i> 설정 저장
    </button>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-gear me-1"></i> 사이트 설정
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/setting/update') ?>" method="post"
            enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- ========== 관리자 계정 ========== -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="bi bi-person-circle me-1"></i> 관리자
                        계정</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">관리자 사번(아이디)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control bg-light" value="<?= esc($admin['user_id']) ?>" readonly>
                    </div>
                    <div class="form-text text-danger small">* 사번은 변경이 불가능합니다.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">관리자명</label>
                    <input type="text" name="user_name" value="<?= esc($admin['user_name']) ?>" class="form-control"
                        placeholder="성명을 입력하세요">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">현재 비밀번호</label>
                    <input type="password" name="user_pw_org" value="" class="form-control"
                        placeholder="비밀번호 변경 시에만 입력하세요">
                    <div class="form-text text-muted small">* 비밀번호를 변경하시려면 현재 사용 중인 비밀번호를 입력해 주십시오.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">새 비밀번호</label>
                    <input type="password" name="user_pw" value="" class="form-control" placeholder="6~20자리 영문/숫자">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">새 비밀번호 확인</label>
                    <input type="password" name="user_pw2" value="" class="form-control" placeholder="비밀번호 재입력">
                </div>
            </div>

            <!-- ========== 사이트 기본 정보 ========== -->
            <div class="row g-4 border-top pt-5 mb-5">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-building me-1"></i> 사이트 기본
                        정보</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">사이트명 (SMS 발송용 등)</label>
                    <input type="text" name="site_name" value="<?= esc($setting['site_name'] ?? '') ?>"
                        class="form-control" placeholder="사이트명을 입력하세요">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">상호명</label>
                    <input type="text" name="home_name" value="<?= esc($setting['home_name'] ?? '') ?>"
                        class="form-control" placeholder="상호명을 입력하세요">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">고객문의 이메일</label>
                    <input type="text" name="qna_email" value="<?= esc($setting['qna_email'] ?? '') ?>"
                        class="form-control" placeholder="문의 이메일 주소">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">본사 주소</label>
                    <div class="input-group mb-2" style="max-width: 350px;">
                        <input type="text" name="zip" id="zip" value="<?= esc($setting['zip'] ?? '') ?>"
                            class="form-control" placeholder="우편번호" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="execDaumPostcode()">주소
                            검색</button>
                    </div>
                    <input type="text" name="addr1" id="addr1" value="<?= esc($setting['addr1'] ?? '') ?>"
                        class="form-control mb-2" placeholder="기본 주소" readonly style="max-width: 600px;">
                    <input type="text" name="addr2" id="addr2" value="<?= esc($setting['addr2'] ?? '') ?>"
                        class="form-control" placeholder="상세 주소" style="max-width: 600px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">대표자명</label>
                    <input type="text" name="com_owner" value="<?= esc($setting['com_owner'] ?? '') ?>"
                        class="form-control" placeholder="대표자명">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">개인정보 담당자</label>
                    <input type="text" name="info_owner" value="<?= esc($setting['info_owner'] ?? '') ?>"
                        class="form-control" placeholder="개인정보 담당자명">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">대표번호</label>
                    <input type="text" name="custom_phone" value="<?= esc($setting['custom_phone'] ?? '') ?>"
                        class="form-control" placeholder="예: 02-000-0000">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">팩스번호</label>
                    <input type="text" name="fax" value="<?= esc($setting['fax'] ?? '') ?>" class="form-control"
                        placeholder="예: 02-000-0001">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">사업자등록번호</label>
                    <input type="text" name="comnum" value="<?= esc($setting['comnum'] ?? '') ?>" class="form-control"
                        placeholder="000-00-00000">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">통신판매번호</label>
                    <input type="text" name="mallOrder" value="<?= esc($setting['mallOrder'] ?? '') ?>"
                        class="form-control" placeholder="제0000-서울-00000호">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Copyright</label>
                    <input type="text" name="copyright" value="<?= esc($setting['copyright'] ?? '') ?>"
                        class="form-control" placeholder="Copyright (c) 2025 HANA EVENT. ALL RIGHTS RESERVED">
                </div>
            </div>

            <!-- ========== 이미지 / 로고 ========== -->
            <div class="row g-4 border-top pt-5 mb-5">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-image me-1"></i> 이미지 / 로고
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">로고 이미지</label>
                    <input type="file" name="logos" class="form-control mb-2" accept="image/*">
                    <?php if (!empty($setting['logos'])): ?>
                        <div class="d-inline-block border rounded p-1 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['logos']) ?>" alt="로고"
                                style="max-height: 100px; max-width: 100%;">
                        </div>
                        <div class="small text-muted mt-1"><i class="bi bi-check-circle-fill text-success me-1"></i> 현재 등록된
                            로고</div>
                    <?php else: ?>
                        <div class="small text-muted">등록된 이미지가 없습니다.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">파비콘 (Favicon)</label>
                    <input type="file" name="favico" class="form-control mb-2" accept="image/*">
                    <?php if (!empty($setting['favico'])): ?>
                        <div class="d-inline-block border rounded p-1 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['favico']) ?>" alt="파비콘"
                                style="max-height: 64px;">
                        </div>
                        <div class="small text-muted mt-1"><i class="bi bi-check-circle-fill text-success me-1"></i> 현재 등록된
                            파비콘</div>
                    <?php else: ?>
                        <div class="small text-muted">등록된 이미지가 없습니다.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">관리자 로고 이미지</label>
                    <input type="file" name="logos_footer" class="form-control mb-2" accept="image/*">
                    <?php if (!empty($setting['logos_footer'])): ?>
                        <div class="d-inline-block border rounded p-1 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['logos_footer']) ?>" alt="관리자 로고"
                                style="max-height: 100px; max-width: 100%;">
                        </div>
                        <div class="small text-muted mt-1"><i class="bi bi-check-circle-fill text-success me-1"></i> 현재 등록된
                            관리자 로고</div>
                    <?php else: ?>
                        <div class="small text-muted">등록된 이미지가 없습니다.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ========== SEO ========== -->
            <div class="row g-4 border-top pt-5">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-search me-1"></i> 검색 엔진 최적화
                        (SEO)</h5>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">브라우저 타이틀 (Browser Title)</label>
                    <input type="text" name="browser_title" value="<?= esc($setting['browser_title'] ?? '') ?>"
                        class="form-control" placeholder="<title> 태그에 들어갈 내용">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">메타 태그 (Meta Description)</label>
                    <input type="text" name="meta_tag" value="<?= esc($setting['meta_tag'] ?? '') ?>"
                        class="form-control" placeholder="meta description 내용">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">메타 키워드 (Meta Keywords)</label>
                    <input type="text" name="meta_keyword" value="<?= esc($setting['meta_keyword'] ?? '') ?>"
                        class="form-control" placeholder="쉼표(,)로 구분">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">og:제목 (Open Graph Title)</label>
                    <input type="text" name="og_title" value="<?= esc($setting['og_title'] ?? '') ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">og:사이트이름 (Open Graph Site Name)</label>
                    <input type="text" name="og_site" value="<?= esc($setting['og_site'] ?? '') ?>"
                        class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:부가설명 (Open Graph Description)</label>
                    <input type="text" name="og_des" value="<?= esc($setting['og_des'] ?? '') ?>" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:URL (Open Graph URL)</label>
                    <input type="text" name="og_url" value="<?= esc($setting['og_url'] ?? '') ?>" class="form-control"
                        placeholder="https://example.com">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Schema (JSON-LD)</label>
                    <textarea name="schema_jsonld" class="form-control font-monospace" rows="6"
                        placeholder='{ "@context": "https://schema.org", ... }'><?= esc($setting['schema_jsonld'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:이미지 (Open Graph Image)</label>
                    <input type="file" name="og_img" class="form-control" accept="image/*">
                    <div class="form-text text-muted">권장 사이즈: 1200x630 px</div>
                    <?php if (!empty($setting['og_img'])): ?>
                        <div class="mt-2 d-inline-block border rounded p-1 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['og_img']) ?>" alt="og:image"
                                style="max-height: 120px; max-width: 100%;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ========== SMS 연동 & 시스템 ========== -->
            <div class="row g-4 pt-5 mb-5">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-chat-dots me-1"></i> SMS 연동
                        & 시스템</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">알림톡 apikey</label>
                    <input type="text" name="allim_apikey" value="<?= esc($setting['allim_apikey'] ?? '') ?>"
                        class="form-control" placeholder="_ALLIM_APIKEY">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">알림톡 userid</label>
                    <input type="text" name="allim_userid" value="<?= esc($setting['allim_userid'] ?? '') ?>"
                        class="form-control" placeholder="_ALLIM_USERID">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">알림톡 senderkey</label>
                    <input type="text" name="allim_senderkey" value="<?= esc($setting['allim_senderkey'] ?? '') ?>"
                        class="form-control" placeholder="_ALLIM_SENDERKEY">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">문자 발신번호</label>
                    <input type="text" name="sms_phone" value="<?= esc($setting['sms_phone'] ?? '') ?>"
                        class="form-control" placeholder="예: 02-000-0000">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">이메일</label>
                    <input type="text" name="email" value="<?= esc($setting['email'] ?? '') ?>" class="form-control"
                        placeholder="시스템 알림용 이메일">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold d-block">SSL 사용</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="ssl_chk" id="ssl_Y" value="Y"
                            <?= ($setting['ssl_chk'] ?? 'Y') === 'Y' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ssl_Y">사용</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ssl_chk" id="ssl_N" value="N"
                            <?= ($setting['ssl_chk'] ?? '') === 'N' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ssl_N">사용안함</label>
                    </div>
                    <div class="form-text text-muted d-inline-block ms-2">(전체 사이트 SSL 적용 여부를 선택합니다.)</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 border-top pt-4 mt-4 d-flex justify-content-center">
                    <button type="button" onclick="send_its()" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="bi bi-check-circle me-1"></i> 설정 저장하기
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daum Postcode -->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function (data) {
                var addr = data.userSelectedType === 'R' ? data.roadAddress : data.jibunAddress;
                document.getElementById('zip').value = data.zonecode;
                document.getElementById('addr1').value = addr;
                document.getElementById('addr2').focus();
            }
        }).open();
    }

    function send_its() {
        var frm = document.frm;

        if (frm.user_name.value.trim() == "") {
            alert("관리자명을 입력하셔야 합니다.");
            frm.user_name.focus();
            return;
        }

        if (frm.user_pw_org.value != "") {
            if (frm.user_pw.value.length > 0 && (frm.user_pw.value.length < 6 || frm.user_pw.value.length > 20)) {
                alert("새 비밀번호는 6 ~ 20 자리로 입력해주세요.");
                frm.user_pw.focus();
                return;
            }
            if (frm.user_pw.value != frm.user_pw2.value) {
                alert("비밀번호 확인이 일치하지 않습니다.");
                frm.user_pw2.focus();
                return;
            }
        } else if (frm.user_pw.value != "") {
            alert("비밀번호를 변경하시려면 현재 비밀번호를 입력하셔야 합니다.");
            frm.user_pw_org.focus();
            return;
        }

        var formData = new FormData($("#frm")[0]);

        $.ajax({
            url: "<?= base_url('AdmMaster/setting/update') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status == "OK") {
                    alert(response.message || "정상적으로 저장되었습니다.");
                    location.reload();
                } else {
                    alert(response.message || "오류가 발생하였습니다!!");
                    if (response.status == "NP") frm.user_pw_org.focus();
                }
            },
            error: function () {
                alert("통신 오류가 발생하였습니다.");
            }
        });
    }
</script>

<?= $this->endSection() ?>
