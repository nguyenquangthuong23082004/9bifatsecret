<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/sms') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list"></i> 목록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= base_url('AdmMaster/sms/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="idx" value="<?= $item['idx'] ?? '' ?>">

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">스킨명 (제목) <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="<?= esc($item['title'] ?? '') ?>" class="form-control" required placeholder="예: 회원가입 환영 문자">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">발송코드 <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="<?= esc($item['code'] ?? '') ?>" class="form-control" required placeholder="예: JOIN">
                    <div class="form-text text-muted">시스템에서 문자를 호출할 때 사용하는 고유 코드입니다.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">자동발송 사용여부</label>
                    <select name="autosend" class="form-select">
                        <option value="Y" <?= ($item['autosend'] ?? 'Y') === 'Y' ? 'selected' : '' ?>>사용 (Y)</option>
                        <option value="N" <?= ($item['autosend'] ?? '') === 'N' ? 'selected' : '' ?>>미사용 (N)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">정렬 순서</label>
                    <input type="number" name="onum" value="<?= $item['onum'] ?? 0 ?>" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">문자 발송 내용 <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="10" required placeholder="문자 내용을 입력하세요. 변수는 {{변수명}} 형식으로 사용할 수 있습니다."><?= esc($item['content'] ?? '') ?></textarea>
                <div class="form-text text-muted mt-2">
                    <strong>작성 팁:</strong> 내용에 <code class="text-primary">{{name}}</code>, <code class="text-primary">{{id}}</code> 처럼 괄호 두 개를 사용하면 시스템에서 실제 데이터로 자동 치환합니다.
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle"></i> 저장하기</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
