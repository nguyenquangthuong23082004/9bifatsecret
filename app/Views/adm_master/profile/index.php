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
            <i class="bi bi-person-gear me-1"></i> 관리자 정보 설정
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/profile/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Admin Account Section -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="bi bi-person-circle me-1"></i> 계정 정보</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">관리자 아이디</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control bg-light" value="<?= esc($admin['user_id']) ?>" readonly>
                    </div>
                    <div class="form-text text-danger small">* 아이디는 변경이 불가능합니다.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">관리자명</label>
                    <input type="text" name="user_name" value="<?= esc($admin['user_name']) ?>" class="form-control" placeholder="성명을 입력하세요">
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

            <!-- SEO Management Section -->
            <div class="row g-4 border-top pt-5">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-search me-1"></i> 검색 엔진 최적화</h5>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">브라우저 타이틀 (Browser Title)</label>
                    <input type="text" name="browser_title" value="<?= esc($setting['browser_title'] ?? '') ?>" class="form-control" placeholder="<title> 태그에 들어갈 내용">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">메타 태그 (Meta Description)</label>
                    <input type="text" name="meta_tag" value="<?= esc($setting['meta_tag'] ?? '') ?>" class="form-control" placeholder="meta description 내용">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">메타 키워드 (Meta Keywords)</label>
                    <input type="text" name="meta_keyword" value="<?= esc($setting['meta_keyword'] ?? '') ?>" class="form-control" placeholder="쉼표(,)로 구분">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">og:제목 (Open Graph Title)</label>
                    <input type="text" name="og_title" value="<?= esc($setting['og_title'] ?? '') ?>" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">og:사이트이름 (Open Graph Site Name)</label>
                    <input type="text" name="og_site" value="<?= esc($setting['og_site'] ?? '') ?>" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:부가설명 (Open Graph Description)</label>
                    <input type="text" name="og_des" value="<?= esc($setting['og_des'] ?? '') ?>" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:URL (Open Graph URL)</label>
                    <input type="text" name="og_url" value="<?= esc($setting['og_url'] ?? '') ?>" class="form-control" placeholder="https://example.com">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Schema (JSON-LD)</label>
                    <textarea name="schema_jsonld" class="form-control font-monospace" rows="6" placeholder='{ "@context": "https://schema.org", ... }'><?= esc($setting['schema_jsonld'] ?? '') ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">og:이미지 (Open Graph Image)</label>
                    <div class="mb-2">
                        <input type="file" name="og_img" class="form-control">
                        <div class="form-text text-muted">권장 사이즈: 1200x630 px</div>
                    </div>
                    <?php if (!empty($setting['og_img'])): ?>
                        <div class="mt-3">
                            <div class="d-inline-block border rounded p-1 bg-light shadow-sm">
                                <img src="<?= base_url('uploads/setting/' . $setting['og_img']) ?>" alt="og:image" style="max-height: 120px; max-width: 100%; border-radius: 4px;">
                            </div>
                            <div class="small text-muted mt-1"><i class="bi bi-image me-1"></i> 현재 등록된 이미지</div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Company Info Settings Section -->
                <div class="col-12 border-top pt-5 mt-5">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-building me-1"></i> 회사 정보 설정</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">사업자등록번호</label>
                    <input type="text" name="comnum" value="<?= esc($setting['comnum'] ?? '') ?>" class="form-control" placeholder="예: 144-86-01453">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">대표 이메일</label>
                    <input type="email" name="email" value="<?= esc($setting['email'] ?? '') ?>" class="form-control" placeholder="예: admin01@auto-style.kr">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">회사 주소</label>
                    <input type="text" name="addr1" value="<?= esc($setting['addr1'] ?? '') ?>" class="form-control" placeholder="예: 서울특별시 강서구 마곡중앙6로 42...">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Copyright 문구</label>
                    <input type="text" name="copyright" value="<?= esc($setting['copyright'] ?? '') ?>" class="form-control" placeholder="예: 2024 AUTOSTYLE. All rights reserved.">
                </div>

                <!-- 이메일 연동 (SMTP) 설정 Section -->
                <div class="col-12 border-top pt-5 mt-5">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-envelope me-1"></i> 이메일 연동 (SMTP) 설정</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">SMTP 호스트 (HOST)</label>
                    <input type="text" name="smtp_host" value="<?= esc($setting['smtp_host'] ?? '') ?>" class="form-control" placeholder="예: spam.cafe24.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">SMTP 아이디 (ID)</label>
                    <input type="text" name="smtp_id" value="<?= esc($setting['smtp_id'] ?? '') ?>" class="form-control" placeholder="예: uwal@uwal.co.kr">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">SMTP 비밀번호 (PASS)</label>
                    <input type="text" name="smtp_pass" value="<?= esc($setting['smtp_pass'] ?? '') ?>" class="form-control" placeholder="비밀번호 입력">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">이메일 발송 대상 (관리자 수신 이메일 리스트)</label>
                    <input type="text" name="admin_email_list" value="<?= esc($setting['admin_email_list'] ?? '') ?>" class="form-control" placeholder="쉼표(,)로 구분하여 여러 개 입력 가능">
                    <div class="form-text text-danger small">* 문의 접수 시 알림 메일을 받을 이메일 주소 목록입니다.</div>
                </div>

                <!-- Logos & Favicon Settings Section -->
                <div class="col-12 border-top pt-5 mt-5">
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-images me-1"></i> 로고 및 파비콘 설정</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">헤더 로고 (Header Logo)</label>
                    <input type="file" name="logos" class="form-control mb-2">
                    <?php if (!empty($setting['logos'])): ?>
                        <div class="d-inline-block border rounded p-2 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['logos']) ?>" alt="Header Logo" style="max-height: 50px; background: #000; padding: 5px; border-radius: 2px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">푸터 로고 (Footer Logo)</label>
                    <input type="file" name="logos_footer" class="form-control mb-2">
                    <?php if (!empty($setting['logos_footer'])): ?>
                        <div class="d-inline-block border rounded p-2 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['logos_footer']) ?>" alt="Footer Logo" style="max-height: 50px; background: #000; padding: 5px; border-radius: 2px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">파비콘 (Favicon)</label>
                    <input type="file" name="favico" class="form-control mb-2">
                    <?php if (!empty($setting['favico'])): ?>
                        <div class="d-inline-block border rounded p-2 bg-light shadow-sm">
                            <img src="<?= base_url('uploads/setting/' . $setting['favico']) ?>" alt="Favicon" style="max-height: 32px; max-width: 32px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12 border-top pt-4 mt-5 d-flex justify-content-center">
                    <button type="button" onclick="send_its()" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="bi bi-check-circle me-1"></i> 설정 저장하기
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function send_its() {
        var frm = document.frm;

        // Validate Admin Name
        if (frm.user_name.value.trim() == "") {
            alert("성명을 입력하셔야 합니다.");
            frm.user_name.focus();
            return;
        }

        // Validate Password if changing
        if (frm.user_pw.value != "") {
            if (frm.user_pw.value.length < 6 || frm.user_pw.value.length > 20) {
                alert("새 비밀번호는 6 ~ 20 자리로 입력해주세요.");
                frm.user_pw.focus();
                return;
            }
            if (frm.user_pw.value != frm.user_pw2.value) {
                alert("비밀번호 확인이 일치하지 않습니다.");
                frm.user_pw2.focus();
                return;
            }
        }

        // Use FormData to support file upload via AJAX
        var formData = new FormData($("#frm")[0]);

        $.ajax({
            url: "<?= base_url('AdmMaster/profile/update') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == "OK") {
                    alert(response.message || "정상적으로 수정되었습니다.");
                    location.reload();
                } else {
                    alert(response.message || "오류가 발생하였습니다!!");
                    if (response.status == "NP") frm.user_pw_org.focus();
                }
            },
            error: function() {
                alert("통신 오류가 발생하였습니다.");
            }
        });
    }
</script>

<?= $this->endSection() ?>