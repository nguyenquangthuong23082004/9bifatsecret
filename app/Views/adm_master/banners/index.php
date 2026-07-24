<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="javascript:CheckAll(document.getElementsByName('bbs_idx[]'), true)" class="btn btn-success">전체선택</a>
        <a href="javascript:CheckAll(document.getElementsByName('bbs_idx[]'), false)" class="btn btn-success">선택해체</a>
        <a href="javascript:SELECT_DELETE()" class="btn btn-danger">선택삭제</a>
        <a href="<?= base_url('AdmMaster/bbs/banner/new') ?>" class="btn btn-primary ms-2">
            <i class="bi bi-pencil-square"></i> 배너 등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="frmSearch" method="get" action="<?= base_url('AdmMaster/banners') ?>" class="row g-3 align-items-center">
            <div class="col-auto">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="search_mode" id="search_mode_all" value="" <?= empty($search_mode) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="search_mode_all">전체</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="search_mode" id="search_mode_subject" value="subject" <?= $search_mode == 'subject' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="search_mode_subject">제목</label>
                </div>
            </div>
            <div class="col-auto flex-grow-1" style="max-width: 400px;">
                <div class="input-group">
                    <input type="text" name="search_word" value="<?= esc($search_word ?? '') ?>" class="form-control" placeholder="검색어 입력">
                    <button class="btn btn-primary" type="submit">
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
        <form name="lfrm" id="lfrm">
            <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 1%; white-space: nowrap;">선택</th>
                        <th style="width: 1%; white-space: nowrap;">번호</th>
                        <th style="width: 1%; white-space: nowrap;">구분</th>
                        <th style="width: 1%; white-space: nowrap;">미리보기</th>
                        <th class="text-start">제목 / 링크</th>
                        <th style="width: 1%; white-space: nowrap;">노출여부</th>
                        <th style="width: 1%; white-space: nowrap;">등록일</th>
                        <th style="width: 1%; white-space: nowrap;">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($list)): ?>
                        <tr>
                            <td colspan="8" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $num = $totalCount - (($pg - 1) * 20);
                        foreach($list as $row): 
                        ?>
                        <tr>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input type="checkbox" name="bbs_idx[]" value="<?= $row['bbs_idx'] ?>" class="form-check-input bbs_idx" />
                                </div>
                            </td>
                            <td><?= $num-- ?></td>
                            <td><span class="badge bg-info text-dark"><?= esc($row['category_name'] ?? '') ?></span></td>
                            <td>
                                <?php if($row['ufile6']): ?>
                                    <img src="<?= base_url('data/bbs/'.$row['ufile6']) ?>" class="img-thumbnail" style="max-height: 40px;">
                                <?php elseif($row['ufile1']): ?>
                                    <img src="<?= base_url('data/bbs/'.$row['ufile1']) ?>" class="img-thumbnail" style="max-height: 40px;">
                                <?php endif; ?>
                            </td>
                            <td class="text-start">
                                <a href="<?= base_url('AdmMaster/bbs/banner/edit/'.$row['bbs_idx']) ?>" class="text-decoration-none fw-bold text-dark">
                                    <?= esc($row['subject']) ?>
                                </a>
                                <div class="small text-muted text-truncate" style="max-width: 300px;"><?= esc($row['url']) ?></div>
                            </td>
                            <td>
                                <?= $row['notice_yn'] == 'Y' ? '<span class="badge bg-primary">노출</span>' : '<span class="badge bg-danger">숨김</span>' ?>
                            </td>
                            <td class="small text-muted"><?= date('Y.m.d', strtotime($row['r_date'])) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('AdmMaster/bbs/banner/edit/'.$row['bbs_idx']) ?>" class="btn btn-secondary" title="수정">
                                        <i class="bi bi-gear"></i>
                                    </a>
                                    <button type="button" onclick="del_chk('<?= $row['bbs_idx'] ?>')" class="btn btn-danger" title="삭제">
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
                    <a class="page-link" href="?pg=<?= $i ?>&search_mode=<?= $search_mode ?>&search_word=<?= $search_word ?>"><?= $i ?></a>
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
    if ($(".bbs_idx:checked").length == 0) {
        alert("삭제할 항목을 선택하셔야 합니다.");
        return;
    }
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        var ids = [];
        $(".bbs_idx:checked").each(function() {
            ids.push($(this).val());
        });
        
        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/banner/bulkDelete') ?>",
            type: "POST",
            data: { ids: ids },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            }
        });
    }
}

function del_chk(bbs_idx) {
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/banner/bulkDelete') ?>",
            type: "POST",
            data: { ids: [bbs_idx] },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            }
        });
    }
}
</script>

<?= $this->endSection() ?>
