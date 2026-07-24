<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/inquiry/'.$type) ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-list-ul"></i> 리스트
        </a>
        <?php if (!empty($item['idx'])): ?>
            <button type="button" onclick="del_it()" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> 삭제
            </button>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .table-bordered th {
        background-color: var(--bs-light) !important;
        border: 1px solid #cbd5e1 !important;
    }
    .table-bordered td {
        border: 1px solid #cbd5e1 !important;
    }
    .table-bordered {
        border-collapse: collapse !important;
        border: 1px solid #cbd5e1 !important;
    }
</style>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0 ">
            <i class="bi bi-info-circle me-1"></i> 문의내용 상세
        </h5>
        <span class="badge bg-light text-dark border"><?= esc($item['regdate'] ?? $item['r_date'] ?? '') ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <colgroup>
                    <col style="width: 240px;">
                    <col>
                    <col style="width: 240px;">
                    <col>
                </colgroup>
                <tbody>
                    <?php if ($type == 3): ?>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">분류</th>
                            <td class="px-4 py-3">
                                <?php 
                                $gubuns = ['01'=>'칭찬합니다', '02'=>'불만있습니다', '03'=>'창업희망', '04'=>'기타'];
                                echo $gubuns[$item['gubun'] ?? '04'];
                                ?>
                            </td>
                            <th class="px-4 py-3 fw-bold text-start">작성자</th>
                            <td class="px-4 py-3"><?= esc($item['user_name'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">연락처</th>
                            <td class="px-4 py-3"><?= esc($item['user_phone'] ?? '') ?></td>
                            <th class="px-4 py-3 fw-bold text-start">이메일</th>
                            <td class="px-4 py-3"><?= esc($item['user_email'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">방문일자</th>
                            <td class="px-4 py-3"><?= esc($item['visit_date'] ?? '') ?></td>
                            <th class="px-4 py-3 fw-bold text-start">방문매장</th>
                            <td class="px-4 py-3"><?= esc($item['visit_store'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">제목</th>
                            <td colspan="3" class="px-4 py-3 fw-bold fs-5"><?= esc($item['subject'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">내용</th>
                            <td colspan="3" class="px-4 py-3">
                                <div class="p-3 border rounded bg-white min-vh-25" style="min-height: 200px; white-space: pre-wrap;"><?= esc($item['contents'] ?? '') ?></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">이름</th>
                            <td class="px-4 py-3"><?= esc($item['manager'] ?? '') ?></td>
                            <th class="px-4 py-3 fw-bold text-start">휴대전화</th>
                            <td class="px-4 py-3"><?= esc($item['tel'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">직업/주업</th>
                            <td class="px-4 py-3"><?= esc($item['company'] ?? '') ?></td>
                            <th class="px-4 py-3 fw-bold text-start">장기렌터카 판매 경험 여부</th>
                            <td class="px-4 py-3"><?= ($item['location'] ?? '') == 'yes' ? '경험 있음' : '경험 없음' ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 fw-bold text-start">제휴 희망 형태</th>
                            <td class="px-4 py-3"><?= ($item['content'] ?? '') == 'corporate' ? 'B2B/법인 제휴' : '개인 제휴' ?></td>
                            <th class="px-4 py-3 fw-bold text-start">신청일</th>
                            <td class="px-4 py-3"><?= esc($item['regdate'] ?? '') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-4 d-flex justify-content-center gap-3">
        <a href="<?= base_url('AdmMaster/inquiry/'.$type) ?>" class="btn btn-secondary px-5 py-2">
            <i class="bi bi-list-ul me-1"></i> 리스트로 돌아가기
        </a>
        <?php if (!empty($item['idx'])): ?>
            <button type="button" onclick="del_it()" class="btn btn-danger px-5 py-2">
                <i class="bi bi-trash me-1"></i> 삭제하기
            </button>
        <?php endif; ?>
    </div>
</div>

<script>
function del_it() {
    if(confirm("삭제 후에는 복구가 불가능합니다.\n정말로 삭제하시겠습니까?")) {
        location.href = "<?= base_url('AdmMaster/inquiry/'.$type.'/delete/'.$item['idx']) ?>";
    }
}
</script>

<?= $this->endSection() ?>

