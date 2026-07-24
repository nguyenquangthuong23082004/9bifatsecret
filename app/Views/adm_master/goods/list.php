<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
    <div class="d-flex gap-2">
        <a href="<?= base_url('AdmMaster/goods/form') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> 상품 등록
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body bg-light">
        <form name="search" id="search" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="search_category" class="form-select">
                    <option value="goods_name_ko" <?= ($search_category ?? '') == 'goods_name_ko' ? 'selected' : '' ?>>상품명</option>
                    <option value="oneinfo_ko" <?= ($search_category ?? '') == 'oneinfo_ko' ? 'selected' : '' ?>>상세내용</option>
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
        <table class="table table-bordered align-middle mb-0" style="font-size: 0.9rem;">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 60px;">번호</th>
                    <th style="width: 200px;">분류</th>
                    <th style="width: 150px;">이미지</th>
                    <th>상품명 / 한줄설명</th>
                    <th style="width: 100px;">사용유무</th>
                    <th style="width: 120px;">등록일</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($list)): ?>
                    <tr>
                        <td colspan="6" class="py-5 text-muted text-center">검색된 결과가 없습니다.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $num = $totalCount - (($pg - 1) * 20);
                    foreach($list as $row): 
                    ?>
                    <tr>
                        <td class="text-center"><?= $num-- ?></td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark">
                                <?= esc($row['product_code_name_1'] ?? '') ?>
                            </span>
                            <div class="small mt-1 text-muted"><?= esc($row['product_code_name_2'] ?? '') ?></div>
                        </td>
                        <td class="text-center p-1">
                            <?php if ($row['ufile1']): ?>
                                <img src="<?= base_url('data/goods/'.$row['ufile1']) ?>" class="img-thumbnail" style="max-width:100px; max-height:60px;">
                            <?php else: ?>
                                <div class="bg-light text-muted small py-3">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="mb-1">
                                <a href="<?= base_url('AdmMaster/goods/form/'.$row['idx']) ?>" class="text-decoration-none fw-bold text-dark fs-6">
                                    <?= esc($row['goods_name_ko']) ?>
                                    <span class="text-muted fw-normal small">/ <?= esc($row['goods_name_en'] ?? '') ?></span>
                                </a>
                            </div>
                            <div class="p-2 border-start border-4 border-light bg-light small text-muted">
                                <i class="bi bi-info-circle me-1"></i> <?= esc($row['oneinfo_ko'] ?? '') ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <?= $row['useYN'] == 'Y' ? '<span class="badge bg-primary">사용</span>' : '<span class="badge bg-danger">중단</span>' ?>
                        </td>
                        <td class="text-center small text-muted"><?= date('Y.m.d', strtotime($row['regdate'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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

<?= $this->endSection() ?>
