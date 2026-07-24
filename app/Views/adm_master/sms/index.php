<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
<div class="d-flex gap-2">
    <a href="<?= base_url('AdmMaster/sms/form') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> 자동문자 스킨 등록
    </a>
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
        <form action="<?= base_url('AdmMaster/sms') ?>" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <input type="text" name="search_title" value="<?= esc($search_title ?? '') ?>" class="form-control"
                    placeholder="스킨명 검색">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> 검색</button>
                <a href="<?= base_url('AdmMaster/sms') ?>" class="btn btn-secondary">초기화</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th width="150">스킨명</th>
                        <th width="100">발송코드</th>
                        <th>발송내용</th>
                        <th width="100">자동발송</th>
                        <th width="80">순서</th>
                        <th width="150">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr>
                            <td colspan="7" class="py-5 text-muted">등록된 자동문자 스킨이 없습니다.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        // Default pagination
                        $perPage = 20;
                        $pg = $pg ?? 1;
                        $no = $totalCount - (($pg - 1) * $perPage);
                        foreach ($list as $item): ?>
                            <tr>
                                <td><?= $no-- ?></td>
                                <td><a href="<?= base_url('AdmMaster/sms/form/' . $item['idx']) ?>"
                                        class="text-decoration-none fw-bold"><?= esc($item['title']) ?></a></td>
                                <td><span class="badge bg-secondary"><?= esc($item['code']) ?></span></td>
                                <td class="text-start"><?= nl2br(esc($item['content'])) ?></td>
                                <td>
                                    <?php if ($item['autosend'] === 'Y'): ?>
                                        <span class="badge bg-success">사용</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">미사용</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['onum'] ?></td>
                                <td>
                                    <a href="<?= base_url('AdmMaster/sms/form/' . $item['idx']) ?>"
                                        class="btn btn-sm btn-outline-primary"> 수정</a>
                                    <a href="<?= base_url('AdmMaster/sms/delete/' . $item['idx']) ?>"
                                        class="btn btn-sm btn-outline-danger" onclick="return confirm('정말로 삭제하시겠습니까?');"> 삭제</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'default_full') ?>
</div>

<?= $this->endSection() ?>