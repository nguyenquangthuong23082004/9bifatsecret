<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="javascript:CheckAll(document.getElementsByName('a_idx[]'), true)" class="btn btn-success">전체선택</a>
        <a href="javascript:CheckAll(document.getElementsByName('a_idx[]'), false)" class="btn btn-success">선택해체</a>
        <a href="javascript:SELECT_DELETE()" class="btn btn-danger">선택삭제</a>
        <a href="<?= base_url('AdmMaster/agency/form') ?>" class="btn btn-primary ms-2">
            <i class="bi bi-pencil-square"></i> 대리점 등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="search" id="search" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="search_category" class="form-select">
                    <option value="agency_name" <?= ($search_category ?? '') == 'agency_name' ? 'selected' : '' ?>>대리점명</option>
                    <option value="addr1" <?= ($search_category ?? '') == 'addr1' ? 'selected' : '' ?>>주소</option>
                    <option value="phone" <?= ($search_category ?? '') == 'phone' ? 'selected' : '' ?>>연락처</option>
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
                        <th style="width: 50px;">선택</th>
                        <th style="width: 60px;">번호</th>
                        <th style="width: 250px;">대리점명</th>
                        <th style="width: 150px;">연락처</th>	
                        <th class="text-start">주소</th>	
                        <th style="width: 200px;">오픈시간</th>	
                        <th style="width: 100px;">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr>
                            <td colspan="7" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $num = $totalCount - (($pg - 1) * 10);
                        foreach ($list as $row): 
                        ?>
                        <tr>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="checkbox" name="a_idx[]" class="form-check-input a_idx" value="<?= $row['a_idx'] ?>" />
                                </div>
                            </td>
                            <td><?= $num-- ?></td>
                            <td class="fw-bold"><?= esc($row['agency_name']) ?></td>
                            <td><?= esc($row['phone']) ?></td>
                            <td class="text-start small"><?= esc($row['addr1']) ?></td>
                            <td class="small"><?= esc($row['open_time']) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('AdmMaster/agency/form/'.$row['a_idx']) ?>" class="btn btn-secondary" title="수정">
                                        <i class="bi bi-gear"></i>
                                    </a>
                                    <button type="button" onclick="del_it('<?= $row['a_idx'] ?>');" class="btn btn-danger" title="삭제">
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
                    <a class="page-link" href="?pg=<?= $i ?>&search_category=<?= $search_category ?>&search_name=<?= $search_name ?>"><?= $i ?></a>
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
    if ($(".a_idx:checked").length == 0) {
        alert("삭제할 내용을 선택하셔야 합니다.");
        return;
    }
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        var ids = [];
        $(".a_idx:checked").each(function() {
            ids.push($(this).val());
        });
        
        $.ajax({
            url: "<?= base_url('AdmMaster/agency/bulkDelete') ?>",
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
            url: "<?= base_url('AdmMaster/agency/bulkDelete') ?>",
            type: "POST",
            data: { ids: [idx] },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            }
        });
    }
}
</script>

<?= $this->endSection() ?>
