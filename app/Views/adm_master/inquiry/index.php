<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="javascript:CheckAll(document.getElementsByName('idx[]'), true)" class="btn btn-success">전체선택</a>
        <a href="javascript:CheckAll(document.getElementsByName('idx[]'), false)" class="btn btn-success">선택해체</a>
        <a href="javascript:SELECT_DELETE()" class="btn btn-danger">선택삭제</a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="search" id="search" method="get" class="row g-3 align-items-center">
            <input type="hidden" name="type" value="<?= $type ?>">
            <div class="col-auto">
                <select name="search_category" class="form-select">
                    <?php if ($type == 3): ?>
                        <option value="user_name" <?= ($search_category ?? '') == 'user_name' ? 'selected' : '' ?>>신청인</option>
                        <option value="subject" <?= ($search_category ?? '') == 'subject' ? 'selected' : '' ?>>제목</option>
                    <?php elseif ($type == 4): ?>
                        <option value="user_name" <?= ($search_category ?? '') == 'user_name' ? 'selected' : '' ?>>작성자</option>
                    <?php else: ?>
                        <option value="manager" <?= ($search_category ?? '') == 'manager' ? 'selected' : '' ?>>이름</option>
                        <option value="company" <?= ($search_category ?? '') == 'company' ? 'selected' : '' ?>>직업/주업</option>
                    <?php endif; ?>
                    <option value="tel" <?= ($search_category ?? '') == 'tel' ? 'selected' : '' ?>>연락처</option>
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
                    <?php if ($type == 3): ?>
                        <tr>
                            <th style="width: 50px;">선택</th>
                            <th style="width: 60px;">번호</th>
                            <th style="width: 150px;">분류</th>
                            <th class="text-start">제목</th>
                            <th style="width: 120px;">작성자</th>
                            <th style="width: 130px;">연락처</th>
                            <th style="width: 120px;">방문일자</th>
                            <th style="width: 150px;">방문매장</th>
                            <th style="width: 100px;">관리</th>
                        </tr>
                    <?php elseif ($type == 4): ?>
                        <tr>
                            <th style="width: 50px;">선택</th>
                            <th style="width: 60px;">번호</th>
                            <th class="text-start">작성자</th>
                            <th>연락처</th>
                            <th style="width: 150px;">신청일</th>	
                            <th style="width: 100px;">관리</th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th style="width: 50px;">선택</th>
                            <th style="width: 60px;">번호</th>
                            <th style="width: 150px;">이름</th>
                            <th style="width: 150px;">휴대전화</th>
                            <th style="width: 150px;">직업/주업</th>
                            <th style="width: 150px;">판매 경험 여부</th>
                            <th style="width: 150px;">제휴 희망 형태</th>
                            <th style="width: 150px;">신청일</th>
                            <th style="width: 100px;">관리</th>
                        </tr>
                    <?php endif; ?>
                </thead>
                <tbody>
                    <?php if(empty($list)): ?>
                        <tr>
                            <td colspan="10" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $num = $totalCount - (($pg - 1) * 20);
                        foreach($list as $row): 
                        ?>
                        <tr>
                             <td>
                                 <div class="form-check d-flex justify-content-center">
                                     <input type="checkbox" name="idx[]" class="form-check-input idx" value="<?= $row['idx'] ?>" />
                                 </div>
                             </td>
                            <td><?= $num-- ?></td>
                            <?php if ($type == 3): ?>
                                <td>
                                    <?php 
                                    $gubuns = ['01'=>'칭찬합니다', '02'=>'불만있습니다', '03'=>'창업희망', '04'=>'기타'];
                                    $gText = $gubuns[$row['gubun'] ?? '04'];
                                    $gClass = ($row['gubun'] == '02') ? 'bg-danger' : (($row['gubun'] == '01') ? 'bg-success' : 'bg-secondary');
                                    ?>
                                    <span class="badge <?= $gClass ?>"><?= $gText ?></span>
                                </td>
                                <td class="text-start fw-bold">
                                    <a href="<?= base_url('AdmMaster/inquiry/'.$type.'/view/'.$row['idx']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($row['subject']) ?>
                                    </a>
                                </td>
                                <td><?= esc($row['user_name'] ?? '') ?></td>
                                <td><?= esc($row['user_phone'] ?? '') ?></td>
                                <td class="small text-muted"><?= esc($row['visit_date'] ?? '') ?></td>
                                <td><?= esc($row['visit_store'] ?? '') ?></td>
                            <?php elseif ($type == 4): ?>
                                <td class="text-start fw-bold"><?= esc($row['user_name'] ?? '') ?></td>
                                <td><?= esc($row['phone'] ?? '') ?></td>
                                <td class="small text-muted"><?= esc($row['r_date'] ?? '') ?></td>
                            <?php else: ?>
                                <td class="fw-bold">
                                    <a href="<?= base_url('AdmMaster/inquiry/'.$type.'/view/'.$row['idx']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($row['manager'] ?? '') ?>
                                    </a>
                                </td>
                                <td><?= esc($row['tel'] ?? '') ?></td>
                                <td><?= esc($row['company'] ?? '') ?></td>
                                <td><?= ($row['location'] ?? '') == 'yes' ? '경험 있음' : '경험 없음' ?></td>
                                <td><?= ($row['content'] ?? '') == 'corporate' ? 'B2B/법인 제휴' : '개인 제휴' ?></td>
                                <td class="small text-muted"><?= !empty($row['regdate']) ? date('Y-m-d H:i', strtotime($row['regdate'])) : '' ?></td>
                            <?php endif; ?>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if ($type != 4): ?>
                                        <a href="<?= base_url('AdmMaster/inquiry/'.$type.'/view/'.$row['idx']) ?>" class="btn btn-secondary" title="상세보기">
                                            <i class="bi bi-search"></i>
                                        </a>
                                    <?php endif; ?>
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
                    <a class="page-link" href="?pg=<?= $i ?>&search_category=<?= $search_category ?>&search_name=<?= $search_name ?>&type=<?= $type ?>"><?= $i ?></a>
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
            url: "<?= base_url('AdmMaster/inquiry/'.$type.'/bulkDelete') ?>",
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
            url: "<?= base_url('AdmMaster/inquiry/'.$type.'/bulkDelete') ?>",
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
