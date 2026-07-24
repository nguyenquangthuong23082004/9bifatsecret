<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/bbs/'.$code) ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
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
                <i class="bi bi-pencil"></i> 글 등록
            </button>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>



<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-pencil-square me-1"></i> <?= $config['board_name'] ?> <?= !empty($item['bbs_idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form name="frm" id="frm" action="<?= base_url('AdmMaster/bbs/'.$code.'/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="bbs_idx" value="<?= $item['bbs_idx'] ?? '' ?>">
            <input type="hidden" name="code" value="<?= $code ?>">
            
            <?php 
            $skin = $config['skin'] ?? 'basic';
            $isCategory = ($code == 'faq') ? 'N' : ($config['is_category'] ?? 'N');
            $isNotice = ($code == 'faq') ? 'N' : ($config['is_notice'] ?? 'N');
            $isSecure = ($code == 'faq') ? 'N' : ($config['is_secure'] ?? 'N');
            $hide_fields = in_array($skin, ['faq', 'gallery', 'media', 'event']) || $code == 'faq';
            ?>

            <div class="row g-3">
                <!-- 작성자 -->
                <div class="col-md-6" <?= $hide_fields ? 'style="display:none"' : '' ?>>
                    <label class="form-label fw-bold">작성자</label>
                    <input type="text" name="writer" value="<?= esc($item['writer'] ?? '관리자') ?>" class="form-control" />
                </div>

                <!-- 이메일 -->
                <div class="col-md-6" <?= $hide_fields ? 'style="display:none"' : '' ?>>
                    <label class="form-label fw-bold">이메일</label>
                    <input type="email" name="email" value="<?= esc($item['email'] ?? '') ?>" class="form-control" />
                </div>

                <!-- 기간 (Event Skin) -->
                <?php if ($skin == 'event'): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">기간</label>
                    <div class="input-group">
                        <input type="text" name="s_date" value="<?= esc($item['s_date'] ?? '') ?>" class="datepicker form-control" placeholder="시작일" />
                        <span class="input-group-text">~</span>
                        <input type="text" name="e_date" value="<?= esc($item['e_date'] ?? '') ?>" class="datepicker form-control" placeholder="종료일" />
                    </div>
                </div>
                <?php endif; ?>

                <!-- 구분/카테고리 -->
                <?php if ($isCategory == 'Y'): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">구분</label>
                    <select name="category" class="form-select">
                        <option value="">선택</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($code == 'app'): ?>
                                <option value="<?= $cat['code_idx'] ?>" <?= ($item['category'] ?? '') == $cat['code_idx'] ? 'selected' : '' ?>><?= esc($cat['code_name']) ?></option>
                            <?php else: ?>
                                <option value="<?= $cat['tbc_idx'] ?>" <?= ($item['category'] ?? '') == $cat['tbc_idx'] ? 'selected' : '' ?>><?= esc($cat['subject']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($code == 'app'): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">2차분류</label>
                    <input type="text" name="simple" value="<?= esc($item['simple'] ?? '') ?>" class="form-control" />
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- 공지/비밀글 옵션 -->
                <?php if ($isNotice == 'Y' || $isSecure == 'Y'): ?>
                <div class="col-md-6 d-flex align-items-end mb-3" <?= $hide_fields ? 'style="display:none"' : '' ?>>
                    <div class="p-2 border rounded bg-light w-100">
                        <?php if ($isNotice == 'Y'): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="notice_yn" id="notice_yn" value="Y" <?= ($item['notice_yn'] ?? '') == 'Y' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="notice_yn">공지글</label>
                            </div>
                        <?php endif; ?>
                        <?php if ($isSecure == 'Y'): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="secure_yn" id="secure_yn" value="Y" <?= ($item['secure_yn'] ?? '') == 'Y' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="secure_yn">비밀글</label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 등록일/조회수 -->
                <div class="col-md-3" <?= in_array($skin, ['faq', 'media', 'event']) ? 'style="display:none"' : '' ?>>
                    <label class="form-label fw-bold">등록일</label>
                    <input type="text" name="r_date" value="<?= esc($item['r_date'] ?? date('Y-m-d H:i:s')) ?>" class="form-control bg-light" readonly />
                </div>

                <?php if ($code != 'banner'): ?>
                <div class="col-md-3" <?= ($skin == 'faq') ? 'style="display:none"' : '' ?>>
                    <label class="form-label fw-bold">조회수</label>
                    <input type="number" name="hit" value="<?= esc($item['hit'] ?? '0') ?>" class="form-control" />
                </div>
                <?php else: ?>
                    <input type="hidden" name="hit" value="<?= esc($item['hit'] ?? '0') ?>">
                <?php endif; ?>

                <!-- 제목 -->
                <div class="<?= ($code == 'app' || $code == 'faq') ? 'col-md-10' : 'col-12' ?>">
                    <label class="form-label fw-bold text-danger">제목</label>
                    <input type="text" name="subject" value="<?= esc($item['subject'] ?? '') ?>" class="form-control" style="height: 38px !important;" required />
                </div>

                <!-- 순위 (onum) -->
                <?php if ($code == 'app' || $code == 'faq'): ?>
                <div class="col-md-2">
                    <label class="form-label fw-bold">순위 (높은숫자 우선)</label>
                    <input type="number" name="onum" value="<?= esc($item['onum'] ?? '0') ?>" class="form-control text-center" style="height: 38px !important;" />
                </div>
                <?php endif; ?>

                <!-- 배너 링크 -->
                <?php if ($code == 'banner'): ?>
                <div class="col-12">
                    <label class="form-label fw-bold">링크 URL</label>
                    <input type="text" name="url" value="<?= esc($item['url'] ?? '') ?>" class="form-control" placeholder="http://" />
                </div>
                <?php endif; ?>

                <!-- 내용 (Summernote) -->
                <?php if ($code != 'banner'): ?>
                <div class="col-12">
                    <label class="form-label fw-bold">내용</label>
                    <textarea name="contents" id="contents_" class="summernote"><?= esc($item['contents'] ?? '') ?></textarea>
                </div>
                <?php endif; ?>

                <!-- 파일 업로드 섹션 -->
                <div class="col-12 border-top pt-4 mt-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-paperclip me-1"></i> 첨부파일 및 이미지</h6>
                    
                    <div class="row g-4">
                        <?php if (in_array($skin, ['gallery', 'media', 'event'])): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">이미지 첨부</label>
                            <input type="file" name="ufile6" class="form-control" accept="image/*" />
                            <?php if (!empty($item['ufile6'])): ?>
                                <div class="mt-2 p-2 border rounded bg-light">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="del_6" id="del_6" value="Y">
                                        <label class="form-check-label text-danger small" for="del_6">삭제</label>
                                    </div>
                                    <a href="<?= base_url('data/bbs/'.$item['ufile6']) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-image"></i> <?= esc($item['rfile6']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="form-text text-muted smaller mt-1">
                                <?php if ($skin == 'gallery'): ?>* 권장 사이즈: 310px * 211px
                                <?php elseif ($skin == 'media'): ?>* 권장 사이즈: 150px * 103px
                                <?php elseif ($skin == 'event'): ?>* 권장 사이즈: 413px * 207px
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($code == 'banner'): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">모바일 이미지 첨부</label>
                            <input type="file" name="ufile5" class="form-control" />
                            <?php if (!empty($item['ufile5'])): ?>
                                <div class="mt-2 p-2 border rounded bg-light">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="del_5" id="del_5" value="Y">
                                        <label class="form-check-label text-danger small" for="del_5">삭제</label>
                                    </div>
                                    <a href="<?= base_url('data/bbs/'.$item['ufile5']) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-image"></i> <?= esc($item['rfile5']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!in_array($skin, ['faq', 'gallery', 'media', 'event'])): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">파일 첨부</label>
                            <input type="file" name="ufile1" class="form-control" />
                            <?php if (!empty($item['ufile1'])): ?>
                                <div class="mt-2 p-2 border rounded bg-light">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="del_1" id="del_1" value="Y">
                                        <label class="form-check-label text-danger small" for="del_1">삭제</label>
                                    </div>
                                    <a href="<?= base_url('data/bbs/'.$item['ufile1']) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-file-earmark-arrow-down"></i> <?= esc($item['rfile1']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($code == 'news' || $code == 'news_en'): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">동영상 파일 첨부</label>
                            <input type="file" name="ufile1" class="form-control" accept="video/*" />
                            <?php if (!empty($item['ufile1'])): ?>
                                <div class="mt-2 p-2 border rounded bg-light">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="del_1" id="del_v" value="Y">
                                        <label class="form-check-label text-danger small" for="del_v">삭제</label>
                                    </div>
                                    <a href="<?= base_url('data/bbs/'.$item['ufile1']) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-play-circle"></i> <?= esc($item['rfile1']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Youtube 영상 링크</label>
                            <div class="input-group">
                                <span class="input-group-text text-danger"><i class="bi bi-youtube"></i></span>
                                <input type="text" name="url" value="<?= esc($item['url'] ?? '') ?>" class="form-control" placeholder="https://www.youtube.com/watch?v=..." />
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 하단 버튼 섹션 -->
                <div class="col-12 border-top pt-4 mt-4 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/bbs/'.$code) ?>" class="btn btn-secondary px-4 py-2">
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
    // Summernote Init
    $('.summernote').summernote({
        height: 400,
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
        ],
        callbacks: {
            onImageUpload: function(files) {
                // Summernote default is Base64. 
                // To implement server-side upload, we'd add a callback here.
            }
        }
    });

    $("#frm").ajaxForm({
        success: function(response) {
            if (response.trim() == "OK") {
                <?php if (empty($item['bbs_idx'])): ?>
                    alert_("정상적으로 등록되었습니다.");
                    setTimeout(function() {
                        location.href = "<?= base_url('AdmMaster/bbs/'.$code) ?>";
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
    if (frm.subject.value == "") { alert("제목을 입력해주세요."); frm.subject.focus(); return; }
    if (frm.writer && frm.writer.value == "") { alert("작성자를 입력해주세요."); frm.writer.focus(); return; }
    
    <?php if ($code != 'banner'): ?>
    if ($('#contents_').summernote('isEmpty')) { alert("내용을 입력하셔야 합니다."); return; }
    <?php endif; ?>

    $("#frm").submit();
}

function del_chk(idx) {
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/'.$code.'/delete/') ?>" + idx,
            success: function() {
                alert_("정상적으로 삭제되었습니다.");
                setTimeout(function() {
                    location.href = "<?= base_url('AdmMaster/bbs/'.$code) ?>";
                }, 1000);
            }
        });
    }
}
</script>

<?= $this->endSection() ?>

