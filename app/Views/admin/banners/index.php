<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success" style="padding: 10px; background-color: #dff0d8; color: #3c763d; margin-bottom: 10px;">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php foreach($categories as $cat): ?>
        <div class="listTop" style="margin-top: 30px;">
            <div class="left">
                <p class="schTxt">Category: <?= $cat['subject'] ?> (<?= $cat['onum'] == 0 ? 'Korean' : 'English' ?>)</p>
            </div>
        </div>

        <div class="listBottom">
            <table cellpadding="0" cellspacing="0" class="listTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Preview (PC)</th>
                        <th>Subject / Link</th>
                        <th>Status</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $banners = $bbsModel->where('category', $cat['tbc_idx'])->findAll();
                    foreach($banners as $row): 
                    ?>
                    <tr>
                        <td><?= $row['bbs_idx'] ?></td>
                        <td>
                            <?php if($row['ufile6']): ?>
                                <img src="<?= base_url('data/bbs/'.$row['ufile6']) ?>" style="max-height: 50px;">
                            <?php endif; ?>
                        </td>
                        <td class="tal">
                            <strong><?= esc($row['subject']) ?></strong><br>
                            <small><?= esc($row['url']) ?></small>
                        </td>
                        <td><?= $row['notice_yn'] == 'Y' ? 'Active' : 'Hidden' ?></td>
                        <td>
                            <a href="<?= base_url('admin/banners/edit/'.$row['bbs_idx']) ?>" class="btn btn-default">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
