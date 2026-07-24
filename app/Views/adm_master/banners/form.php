<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/banners') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <button type="submit" form="bannerFrm" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> 저장
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-pencil-square me-1"></i> 배너 <?= !empty($banner['bbs_idx']) ? '수정' : '등록' ?>
        </h5>
    </div>
    <div class="card-body">
        <form id="bannerFrm" action="<?= base_url('AdmMaster/banners/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="bbs_idx" value="<?= $banner['bbs_idx'] ?? '' ?>">
            
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-bold text-danger">배너 제목</label>
                    <input type="text" name="subject" value="<?= esc($banner['subject'] ?? '') ?>" class="form-control form-control-lg" required placeholder="배너 제목을 입력하세요">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">링크 URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                        <input type="text" name="url" value="<?= esc($banner['url'] ?? '') ?>" class="form-control" placeholder="http://...">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">PC용 이미지</label>
                    <input type="file" name="ufile6" class="form-control" accept="image/*">
                    <?php if(!empty($banner['ufile6'])): ?>
                        <div class="mt-2 p-2 border rounded bg-light">
                            <img src="<?= base_url('data/bbs/'.$banner['ufile6']) ?>" class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px;">
                            <div class="small text-muted text-truncate"><?= esc($banner['rfile6']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">모바일용 이미지</label>
                    <input type="file" name="ufile5" class="form-control" accept="image/*">
                    <?php if(!empty($banner['ufile5'])): ?>
                        <div class="mt-2 p-2 border rounded bg-light">
                            <img src="<?= base_url('data/bbs/'.$banner['ufile5']) ?>" class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px;">
                            <div class="small text-muted text-truncate"><?= esc($banner['rfile5']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold d-block">노출 상태</label>
                    <div class="p-2 border rounded bg-light">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="notice_yn" id="activeY" value="Y" <?= ($banner['notice_yn'] ?? 'Y') == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="activeY">노출함</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="notice_yn" id="activeN" value="N" <?= ($banner['notice_yn'] ?? '') == 'N' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="activeN">숨김</label>
                        </div>
                    </div>
                </div>

                <!-- 하단 버튼 -->
                <div class="col-12 border-top pt-4 mt-2 d-flex justify-content-center gap-3">
                    <a href="<?= base_url('AdmMaster/banners') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> 취소
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= !empty($banner['bbs_idx']) ? '수정하기' : '등록하기' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

