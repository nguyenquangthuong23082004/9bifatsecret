<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="javascript:CheckAll(document.getElementsByName('bbs_idx[]'), true)" class="btn btn-success">전체선택</a>
        <a href="javascript:CheckAll(document.getElementsByName('bbs_idx[]'), false)" class="btn btn-success">선택해제</a>
        <a href="javascript:SELECT_DELETE()" class="btn btn-danger">선택삭제</a>
        <?php if ($code == 'app' || $code == 'faq'): ?>
        <a href="javascript:UPDATE_ORDER()" class="btn btn-warning ms-2">
            <i class="bi bi-sort-numeric-down"></i> 순위변경
        </a>
        <?php endif; ?>
        <a href="<?= base_url('AdmMaster/bbs/'.$code.'/form') ?>" class="btn btn-primary ms-2">
            <i class="bi bi-pencil-square"></i> 글 등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
$skin       = $config['skin'] ?? 'basic';
$isCategory = ($code == 'faq') ? 'N' : ($config['is_category'] ?? 'N');

$search_mode = $search_mode ?? '';
$search_word = $search_word ?? '';
$scategory   = $scategory ?? '';
?>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="frmSearch" method="get" class="row g-3 align-items-center">

            <?php if ($isCategory == 'Y'): ?>
                <div class="col-auto">
                    <select name="scategory" class="form-select" onchange="this.form.submit();">
                        <option value="">전체 구분</option>

                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>

                                <?php if ($code == 'app'): ?>
                                    <option 
                                        value="<?= esc($cat['code_idx']) ?>"
                                        <?= (string)$scategory === (string)$cat['code_idx'] ? 'selected' : '' ?>
                                    >
                                        <?= esc($cat['code_name']) ?>
                                    </option>
                                <?php else: ?>
                                    <option 
                                        value="<?= esc($cat['tbc_idx']) ?>"
                                        <?= (string)$scategory === (string)$cat['tbc_idx'] ? 'selected' : '' ?>
                                    >
                                        <?= esc($cat['subject']) ?>
                                    </option>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            <?php endif; ?>
			

            <div class="col-auto">
                <div class="form-check form-check-inline">
                    <input 
                        class="form-check-input" 
                        type="radio" 
                        name="search_mode" 
                        id="mode_all" 
                        value="" 
                        <?= $search_mode == '' ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="mode_all">전체</label>
                </div>

                <div class="form-check form-check-inline">
                    <input 
                        class="form-check-input" 
                        type="radio" 
                        name="search_mode" 
                        id="mode_subject" 
                        value="subject" 
                        <?= $search_mode == 'subject' ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="mode_subject">제목</label>
                </div>

                <div class="form-check form-check-inline">
                    <input 
                        class="form-check-input" 
                        type="radio" 
                        name="search_mode" 
                        id="mode_contents" 
                        value="contents" 
                        <?= $search_mode == 'contents' ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="mode_contents">내용</label>
                </div>

                <?php if ($code != 'news'): ?>
                    <div class="form-check form-check-inline">
                        <input 
                            class="form-check-input" 
                            type="radio" 
                            name="search_mode" 
                            id="mode_writer" 
                            value="writer" 
                            <?= $search_mode == 'writer' ? 'checked' : '' ?>
                        >
                        <label class="form-check-label" for="mode_writer">작성자</label>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-auto flex-grow-1" style="max-width: 400px;">
                <div class="input-group">
                    <input 
                        type="text" 
                        name="search_word" 
                        value="<?= esc($search_word) ?>" 
                        class="form-control" 
                        placeholder="검색어 입력" 
                        aria-label="검색어 입력"
                    >

                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> 검색하기
                    </button>
                </div>
            </div>

            <div class="col-auto">
                <a href="<?= base_url('AdmMaster/bbs/'.$code) ?>" class="btn btn-outline-secondary">
                    초기화
                </a>
            </div>

            <div class="col-auto ms-auto">
                <span class="text-muted small">
                    ■ 총 <strong><?= number_format($totalCount) ?></strong>개의 목록이 있습니다.
                </span>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <form name="lfrm" id="lfrm">
            <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 1%; white-space: nowrap;">선택</th>
                        <th style="width: 1%; white-space: nowrap;">번호</th>

                        <?php if ($isCategory == 'Y'): ?>
                            <th style="width: 1%; white-space: nowrap;">구분</th>
                        <?php endif; ?>

                        <th class="text-start">제목</th>

                        <?php if ($code == 'app' || $code == 'faq'): ?>
                            <th style="width: 5%; white-space: nowrap;">순위</th>
                        <?php endif; ?>

                        <?php if ($skin != 'faq'): ?>
                            <th style="width: 1%; white-space: nowrap;">작성자</th>
                            <th style="width: 1%; white-space: nowrap;">파일</th>
                            <th style="width: 1%; white-space: nowrap;">작성일</th>
                            <th style="width: 1%; white-space: nowrap;">조회</th>
                        <?php endif; ?>

                        <th style="width: 1%; white-space: nowrap;">관리</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($list)): ?>
                        <tr>
                            <td colspan="<?= $isCategory == 'Y' ? '10' : '9' ?>" class="py-5 text-muted">
                                검색된 결과가 없습니다.
                            </td>
                        </tr>
                    <?php else: ?>

                        <?php 
                        $perPage = $perPage ?? 20;
                        $num = $totalCount - (($pg - 1) * $perPage);

                        foreach ($list as $row): 
                            $nums = ($row['notice_yn'] == 'Y') ? '<span class="badge bg-danger">공지</span>' : $num--;

                            $file_chk = 'N';
                            for ($i = 1; $i <= 6; $i++) {
                                if (!empty($row['rfile'.$i])) {
                                    $file_chk = 'Y';
                                }
                            }
                        ?>

                        <tr>
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input 
                                        type="checkbox" 
                                        name="bbs_idx[]" 
                                        value="<?= esc($row['bbs_idx']) ?>" 
                                        class="form-check-input bbs_idx" 
                                    />
                                </div>
                            </td>

                            <td><?= $nums ?></td>

                            <?php if ($isCategory == 'Y'): ?>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?= esc($row['category_name'] ?? '-') ?>
                                    </span>
                                </td>
                            <?php endif; ?>

                            <td class="text-start fw-bold">
                                <a href="<?= base_url('AdmMaster/bbs/'.$code.'/form/'.$row['bbs_idx']) ?>" class="text-decoration-none text-dark">
                                    <?= ($row['recomm_yn'] ?? '') == 'Y' ? '<span class="badge bg-warning text-dark me-1">추천</span>' : '' ?>

                                    <?= esc($row['subject']) ?>

                                    <?= ($row['secure_yn'] ?? '') == 'Y' ? ' <i class="bi bi-lock-fill text-muted"></i>' : '' ?>

                                    <?php if (!empty($row['comment_cnt']) && $row['comment_cnt'] > 0): ?>
                                        <span class="text-primary small">(<?= esc($row['comment_cnt']) ?>)</span>
                                    <?php endif; ?>
                                </a>
                            </td>

                            <?php if ($code == 'app' || $code == 'faq'): ?>
                                <td>
                                    <input type="number" name="onum[]" value="<?= esc($row['onum'] ?? 0) ?>" class="form-control form-control-sm text-center mx-auto" style="width: 70px;">
                                </td>
                            <?php endif; ?>

                            <?php if ($skin != 'faq'): ?>
                                <td><?= esc($row['writer']) ?></td>

                                <td>
                                    <?php if ($file_chk == 'Y'): ?>
                                        <i class="bi bi-paperclip text-primary fs-5"></i>
                                    <?php endif; ?>
                                </td>

                                <td class="small text-muted" style="white-space: nowrap;">
                                    <?= !empty($row['r_date']) ? date('Y-m-d H:i', strtotime($row['r_date'])) : '' ?>
                                </td>

                                <td><?= esc($row['hit'] ?? 0) ?></td>
                            <?php endif; ?>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('AdmMaster/bbs/'.$code.'/form/'.$row['bbs_idx']) ?>" class="btn btn-secondary" title="수정">
                                        <i class="bi bi-gear"></i>
                                    </a>

                                    <button type="button" onclick="del_chk('<?= esc($row['bbs_idx']) ?>')" class="btn btn-danger" title="삭제">
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

<?php
$queryParams = [
    'search_mode' => $search_mode,
    'search_word' => $search_word,
    'scategory'   => $scategory,
];
?>

<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm shadow-sm">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php
                $queryParams['pg'] = $i;
                $pageUrl = '?' . http_build_query($queryParams);
                ?>

                <li class="page-item <?= $pg == $i ? 'active' : '' ?>">
                    <a class="page-link" href="<?= esc($pageUrl) ?>">
                        <?= $i ?>
                    </a>
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
        alert("삭제할 게시물을 선택하셔야 합니다.");
        return;
    }

    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        var ids = [];

        $(".bbs_idx:checked").each(function() {
            ids.push($(this).val());
        });

        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/'.$code.'/bulkDelete') ?>",
            type: "POST",
            data: { 
                ids: ids
            },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            },
            error: function() {
                alert("삭제 중 오류가 발생했습니다.");
            }
        });
    }
}

function del_chk(idx) {
    if (confirm("삭제 하시겠습니까?\n삭제 후에는 복구가 불가능합니다.")) {
        var ids = [idx];

        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/'.$code.'/bulkDelete') ?>",
            type: "POST",
            data: { 
                ids: ids
            },
            success: function(response) {
                alert("정상적으로 삭제되었습니다.");
                location.reload();
            },
            error: function() {
                alert("삭제 중 오류가 발생했습니다.");
            }
        });
    }
}

function UPDATE_ORDER() {
    if (confirm("현재 페이지의 항목 순위를 변경하시겠습니까?")) {
        var ids = [];
        var onums = [];

        $(".bbs_idx").each(function() {
            ids.push($(this).val());
            onums.push($(this).closest('tr').find('input[name="onum[]"]').val());
        });

        $.ajax({
            url: "<?= base_url('AdmMaster/bbs/'.$code.'/updateOrder') ?>",
            type: "POST",
            data: { 
                bbs_idx: ids,
                onum: onums
            },
            success: function(response) {
                alert("순위가 변경되었습니다.");
                location.reload();
            },
            error: function() {
                alert("처리 중 오류가 발생했습니다.");
            }
        });
    }
}
</script>

<?= $this->endSection() ?>