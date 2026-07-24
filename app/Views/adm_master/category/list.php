<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <?php if (!empty($s_parent_code_no)): ?>
            <a href="<?= base_url('AdmMaster/category') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> 대분류로
            </a>
        <?php endif; ?>
        <button type="button" onclick="change_it()" class="btn btn-success">
            <i class="bi bi-sort-numeric-down"></i> 순위변경
        </button>
        <a href="<?= base_url('AdmMaster/category/form?s_parent_code_no='.$s_parent_code_no) ?>" class="btn btn-primary ms-2">
            <i class="bi bi-plus-circle"></i> 신규등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body py-3 d-flex align-items-center justify-content-between bg-light">
        <div>
            <span class="text-muted small">■ 총 <strong><?= count($list) ?></strong>개의 목록이 있습니다.</span>
            <?php if (!empty($s_parent_code_no)): ?>
                <span class="ms-2 badge bg-primary">Parent Code: <?= esc($s_parent_code_no) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <form name="frm" id="frm">
            <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">번호</th>
                        <th style="width: 120px;">코드번호</th>
                        <th class="text-start">코드명 (국문 / 영문)</th>
                        <th style="width: 100px;">DEPTH</th>
                        <th style="width: 100px;">현황</th>
                        <th style="width: 120px;">우선순위</th>
                        <th style="width: 100px;">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr>
                            <td colspan="7" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php $num = count($list); ?>
                        <?php foreach ($list as $row): ?>
                            <tr>
                                <td><?= $num-- ?></td>
                                <td class=""><?= esc($row['code_no']) ?></td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="<?= base_url('AdmMaster/category/form/'.$row['code_idx'].'?s_parent_code_no='.$s_parent_code_no) ?>" class="text-decoration-none text-dark fw-bold">
                                            <?= esc($row['code_name']) ?> <span class="text-muted fw-normal">/ <?= esc($row['code_name_en']) ?></span>
                                        </a>
                                        <?php if ($row['cnt'] > 0): ?>
                                            <a href="<?= base_url('AdmMaster/category?s_parent_code_no='.$row['code_no']) ?>" class="btn btn-outline-info btn-sm ms-2" style="font-size: 0.75rem;">
                                                <i class="bi bi-folder2-open"></i> 하위 카테고리 (<?= $row['cnt'] ?>)
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary"><?= esc($row['depth']) ?></span></td>
                                <td>
                                    <?php
                                        if ($row['status'] == 'Y') echo '<span class="badge bg-success">사용</span>';
                                        elseif ($row['status'] == 'N') echo '<span class="badge bg-danger">삭제</span>';
                                        elseif ($row['status'] == 'C') echo '<span class="badge bg-warning text-dark">마감</span>';
                                    ?>
                                </td>
                                <td>
                                    <input type="number" name="onum[]" value="<?= $row['onum'] ?>" class="form-control form-control-sm text-center mx-auto" style="width:70px" />
                                    <input type="hidden" name="code_idx[]" value="<?= $row['code_idx'] ?>" />
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('AdmMaster/category/form/'.$row['code_idx'].'?s_parent_code_no='.$s_parent_code_no) ?>" class="btn btn-secondary" title="수정">
                                            <i class="bi bi-gear"></i>
                                        </a>
                                        <button type="button" onclick="del_it('<?= $row['code_idx'] ?>')" class="btn btn-danger" title="삭제">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script>
function change_it() {
    $.ajax({
        url: "<?= base_url('AdmMaster/category/updateOrder') ?>",
        type: "POST",
        data: $("#frm").serialize(),
        success: function(res) {
            if (res.status == 'OK') {
                alert("정상적으로 변경되었습니다.");
                location.reload();
            } else {
                alert("오류가 발생하였습니다!!");
            }
        },
        error: function(request, status, error) {
            alert("오류가 발생하였습니다. 다시 시도해주세요.");
        }
    });
}

function del_it(idx) {
    if (confirm("카테고리를 삭제하시겠습니까?\n하위 카테고리가 있는 경우 함께 관리하셔야 합니다.\n\n삭제 후에는 복구가 불가능합니다.")) {
        location.href = "<?= base_url('AdmMaster/category/delete') ?>/" + idx;
    }
}
</script>

<?= $this->endSection() ?>

