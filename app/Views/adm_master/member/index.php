<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/member/downloadExcelSample') ?>" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel"></i> 엑셀 샘플 다운로드
        </a>
        <input type="file" id="excelFile" accept=".xlsx, .xls" style="display:none;" onchange="uploadExcel()">
        <button type="button" class="btn btn-warning btn-sm text-white" onclick="$('#excelFile').click()">
            <i class="bi bi-upload"></i> 엑셀 업로드
        </button>
        <a href="<?= base_url('AdmMaster/member/form') ?>" class="btn btn-primary btn-sm ms-2">
            <i class="bi bi-plus-lg"></i> 등록
        </a>
        <button type="button" class="btn btn-danger btn-sm" onclick="chk_delete()">
            <i class="bi bi-trash"></i> 선택삭제
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form name="sfrm" action="<?= base_url('AdmMaster/member') ?>" method="get" class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="search_category" class="form-select">
                    <option value="user_name" <?= $search_category == 'user_name' ? 'selected' : '' ?>>이름</option>
                    <option value="user_id" <?= $search_category == 'user_id' ? 'selected' : '' ?>>사번</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="search_name" value="<?= esc($search_name) ?>" class="form-control" placeholder="검색어 입력">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark">검색</button>
                <a href="<?= base_url('AdmMaster/member') ?>" class="btn btn-outline-secondary">초기화</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <form name="frm" id="frm">
                <?= csrf_field() ?>
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th width="5%"><input type="checkbox" class="form-check-input" id="checkAll" onclick="check_all()"></th>
                            <th width="5%">No</th>
                            <th width="10%">권한(레벨)</th>
                            <th width="15%">사번(아이디)</th>
                            <th width="15%">이름</th>
                            <th width="15%">연락처</th>
                            <th width="15%">등록일</th>
                            <th width="10%">상태</th>
                            <th width="10%">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)): ?>
                            <tr>
                                <td colspan="9" class="py-5 text-muted">등록된 회원이 없습니다.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($list as $i => $row): ?>
                                <tr>
                                    <td><input type="checkbox" class="form-check-input chk" name="ids[]" value="<?= $row['m_idx'] ?>"></td>
                                    <td><?= $totalCount - (($pg - 1) * 20) - $i ?></td>
                                    <td><?= $row['user_level'] == 100 ? '<span class="badge bg-danger">관리자</span>' : '<span class="badge bg-secondary">레벨('.$row['user_level'].')</span>' ?></td>
                                    <td><?= esc($row['user_id']) ?></td>
                                    <td class="">
                                        <a href="<?= base_url('AdmMaster/member/form/' . $row['m_idx']) ?>" class="text-decoration-none">
                                            <?= esc($row['user_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($row['mobile']) ?></td>
                                    <td><?= substr($row['m_date'] ?? '', 0, 10) ?></td>
                                    <td><?= $row['status'] == 'Y' ? '<span class="badge bg-success">사용</span>' : '<span class="badge bg-danger">중지</span>' ?></td>
                                    <td>
                                        <a href="<?= base_url('AdmMaster/member/form/' . $row['m_idx']) ?>" class="btn btn-sm btn-outline-secondary">수정</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php
            // Calculate start and end pages for pagination (show max 10 pages)
            $pageRange = 10;
            $startPage = floor(($pg - 1) / $pageRange) * $pageRange + 1;
            $endPage = min($startPage + $pageRange - 1, $totalPages);
            
            // First / Prev
            if ($startPage > 1) {
                echo '<li class="page-item"><a class="page-link" href="?pg=1&search_category='.$search_category.'&search_name='.$search_name.'"><i class="bi bi-chevron-double-left"></i></a></li>';
                $prevRange = $startPage - 1;
                echo '<li class="page-item"><a class="page-link" href="?pg='.$prevRange.'&search_category='.$search_category.'&search_name='.$search_name.'"><i class="bi bi-chevron-left"></i></a></li>';
            }
            
            // Page numbers
            for ($i = $startPage; $i <= $endPage; $i++): 
            ?>
                <li class="page-item <?= $i == $pg ? 'active' : '' ?>">
                    <a class="page-link" href="?pg=<?= $i ?>&search_category=<?= $search_category ?>&search_name=<?= $search_name ?>"><?= $i ?></a>
                </li>
            <?php 
            endfor; 
            
            // Next / Last
            if ($endPage < $totalPages) {
                $nextRange = $endPage + 1;
                echo '<li class="page-item"><a class="page-link" href="?pg='.$nextRange.'&search_category='.$search_category.'&search_name='.$search_name.'"><i class="bi bi-chevron-right"></i></a></li>';
                echo '<li class="page-item"><a class="page-link" href="?pg='.$totalPages.'&search_category='.$search_category.'&search_name='.$search_name.'"><i class="bi bi-chevron-double-right"></i></a></li>';
            }
            ?>
        </ul>
    </nav>
</div>

<style>
#loadingOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
</style>

<div id="loadingOverlay">
    <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-3 fw-bold text-white fs-5">엑셀 데이터를 처리 중입니다. 잠시만 기다려주세요...</div>
</div>

<script>
function check_all() {
    var isChecked = $("#checkAll").prop("checked");
    $(".chk").prop("checked", isChecked);
}

function chk_delete() {
    if ($(".chk:checked").length == 0) {
        alert("삭제할 항목을 선택해주세요.");
        return;
    }
    
    if (confirm("정말 삭제하시겠습니까?")) {
        var ids = [];
        $(".chk:checked").each(function() {
            ids.push($(this).val());
        });

        $.ajax({
            url: "<?= base_url('AdmMaster/member/bulkDelete') ?>",
            type: "POST",
            data: {
                ids: ids,
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            },
            success: function(res) {
                if(res.status == 'OK') {
                    location.reload();
                }
            }
        });
    }
}

function uploadExcel() {
    var fileInput = document.getElementById('excelFile');
    if (fileInput.files.length === 0) return;

    var formData = new FormData();
    formData.append('excelFile', fileInput.files[0]);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    // Show loading overlay
    $("#loadingOverlay").css("display", "flex");

    $.ajax({
        url: "<?= base_url('AdmMaster/member/importExcel') ?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            $("#loadingOverlay").hide(); // Hide overlay
            if(res.status == 'OK') {
                alert(res.message || '엑셀 업로드가 완료되었습니다.');
                location.reload();
            } else {
                alert(res.message || '업로드 중 오류가 발생했습니다.');
            }
        },
        error: function() {
            $("#loadingOverlay").hide(); // Hide overlay
            alert("서버 통신 오류가 발생했습니다.");
        }
    });
    fileInput.value = ''; // Reset file input
}
</script>

<?= $this->endSection() ?>

