<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="javascript:CheckAll(document.getElementsByName('idx[]'), true)" class="btn btn-success">전체선택</a>
        <a href="javascript:CheckAll(document.getElementsByName('idx[]'), false)" class="btn btn-success">선택해체</a>
        <a href="javascript:SELECT_DELETE()" class="btn btn-danger">선택삭제</a>
        <a href="<?= base_url('AdmMaster/popups/form') ?>" class="btn btn-primary ms-2">
            <i class="bi bi-pencil-square"></i> 팝업창 등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="alert alert-info shadow-sm mb-4">
    <i class="bi bi-info-circle-fill me-2"></i>
    강제 노출/비노출 기능은 팝업창 오픈 일정과 상관없이 강제로 팝업창을 띄우거나 제거하는 기능입니다.
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="search" id="search" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="s_status" class="form-select">
                    <option value="" <?= ($s_status ?? '') == '' ? 'selected' : '' ?>>사용여부(전체)</option>
                    <option value="A" <?= ($s_status ?? '') == 'A' ? 'selected' : '' ?>>일정별 자동노출</option>
                    <option value="B" <?= ($s_status ?? '') == 'B' ? 'selected' : '' ?>>강제 노출</option>
                    <option value="C" <?= ($s_status ?? '') == 'C' ? 'selected' : '' ?>>강제 비노출</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="search_category" class="form-select">
                    <option value="">검색필드(전체)</option>
                    <option value="P_SUBJECT" <?= ($search_category ?? '') == 'P_SUBJECT' ? 'selected' : '' ?>>제목</option>
                    <option value="P_CONTENT" <?= ($search_category ?? '') == 'P_CONTENT' ? 'selected' : '' ?>>내용</option>
                </select>
            </div>
            <div class="col-auto flex-grow-1" style="max-width: 400px;">
                <div class="input-group">
                    <input type="text" name="search_name" value="<?= esc($search_name ?? '') ?>" class="form-control" placeholder="검색어 입력">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> 검색하기
                    </button>
                </div>
            </div>
            <div class="col-auto ms-auto">
                <span class="text-muted small">■ 총 <strong><?= $totalCount ?></strong>개의 목록이 있습니다.</span>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <form name="frm" id="frm">
            <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 1%; white-space: nowrap;">선택</th>
                        <th style="width: 1%; white-space: nowrap;">번호</th>
                        <th style="width: 150px; min-width: 150px; white-space: nowrap;">사용여부</th>
                        <th class="text-start">팝업창 제목</th>
                        <th style="width: 140px; min-width: 140px; white-space: nowrap;">시작일</th>	
                        <th style="width: 140px; min-width: 140px; white-space: nowrap;">종료일</th>	
                        <th style="width: 1%; white-space: nowrap;">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($list)): ?>
                        <tr>
                            <td colspan="7" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $num = $totalCount - (($pg - 1) * 10);
                        foreach($list as $row): 
                        ?>
                        <tr>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="checkbox" name="idx[]" class="form-check-input idx" value="<?= $row['idx'] ?>" />
                                </div>
                            </td>
                            <td><?= $num-- ?></td>
                            <td>
                                <select name="status" onchange="chk_status('<?= $row['idx'] ?>', this.value);" class="form-select form-select-sm border-primary" style="min-width: 130px;">
                                    <option value="A" <?= $row['status'] == 'A' ? 'selected' : '' ?>>일정별 자동노출</option>
                                    <option value="B" <?= $row['status'] == 'B' ? 'selected' : '' ?>>강제 노출</option>
                                    <option value="C" <?= $row['status'] == 'C' ? 'selected' : '' ?>>강제 비노출</option>
                                </select>
                            </td>
                            <td class="text-start fw-bold">
                                <a href="<?= base_url('AdmMaster/popups/form/'.$row['idx']) ?>" class="text-decoration-none text-dark">
                                    <?= esc($row['P_SUBJECT']) ?>
                                </a>
                            </td>
                            <td class="small text-muted text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span><?= $row['P_STARTDAY'] ?></span>
                                    <span class="badge bg-light text-dark" style="font-size: 0.75rem; padding: 0.25em 0.5em;"><?= $row['P_START_HH'] ?>:<?= $row['P_START_MM'] ?></span>
                                </div>
                            </td>
                            <td class="small text-muted text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span><?= $row['P_ENDDAY'] ?></span>
                                    <span class="badge bg-light text-dark" style="font-size: 0.75rem; padding: 0.25em 0.5em;"><?= $row['P_END_HH'] ?>:<?= $row['P_END_MM'] ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('AdmMaster/popups/form/'.$row['idx']) ?>" class="btn btn-secondary" title="수정">
                                        <i class="bi bi-gear"></i>
                                    </a>
                                    <button type="button" onclick="del_it('<?= $row['idx'] ?>');" class="btn btn-danger" title="삭제">
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

<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm shadow-sm">
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <li class="page-item <?= $pg == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?pg=<?= $i ?>&s_status=<?= $s_status ?>&search_category=<?= $search_category ?>&search_name=<?= $search_name ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script>
function CheckAll(checkBoxes, checked) {
    if (checkBoxes.length) {
        for (var i = 0; i < checkBoxes.length; i++) {
            checkBoxes[i].checked = checked;
        }
    } else {
        checkBoxes.checked = checked;
    }
}

function SELECT_DELETE() {
    if ($(".idx:checked").length == 0) {
        alert("삭제할 내용을 선택하셔야 합니다.");
        return;
    }
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        var ids = [];
        $(".idx:checked").each(function() {
            ids.push($(this).val());
        });
        
        $.ajax({
            url: "<?= base_url('AdmMaster/popups/bulkDelete') ?>",
            type: "POST",
            data: { ids: ids },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            }
        });
    }
}

function del_it(idx) {
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        $.ajax({
            url: "<?= base_url('AdmMaster/popups/bulkDelete') ?>",
            type: "POST",
            data: { ids: [idx] },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            }
        });
    }
}

function chk_status(idx, status) {
    $.ajax({
        url: "<?= base_url('AdmMaster/popups/updateStatus') ?>",
        type: "POST",
        data: { idx: idx, status: status },
        success: function(response) {
            alert("정상적으로 현황이 변경되었습니다.");
            location.reload();
        }
    });
}
</script>

<?= $this->endSection() ?>
