<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success" style="padding: 10px; background-color: #dff0d8; color: #3c763d; margin-bottom: 10px;">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="listTop">
        <div class="left">
            <p class="schTxt">Total: <?= $totalCount ?> posts found.</p>
        </div>
        <div class="right">
            <a href="<?= base_url('admin/bbs/'.$code.'/new') ?>" class="btn btn-primary">Create New Post</a>
        </div>
    </div>

    <div class="listBottom">
        <table cellpadding="0" cellspacing="0" class="listTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Subject</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Hit</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($list as $row): ?>
                <tr>
                    <td><?= $row['bbs_idx'] ?></td>
                    <td class="tal">
                        <?php if($row['notice_yn'] == 'Y'): ?>
                            <span style="color: red; font-weight: bold;">[Notice]</span>
                        <?php endif; ?>
                        <?= esc($row['subject']) ?>
                    </td>
                    <td><?= esc($row['user_name']) ?></td>
                    <td><?= date('Y.m.d', strtotime($row['r_date'])) ?></td>
                    <td><?= $row['hit'] ?></td>
                    <td>
                        <a href="<?= base_url('admin/bbs/'.$code.'/edit/'.$row['bbs_idx']) ?>" class="btn btn-default">Edit</a>
                        <a href="<?= base_url('admin/bbs/'.$code.'/delete/'.$row['bbs_idx']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($list)): ?>
                    <tr><td colspan="6">No posts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="paging">
        <ul>
            <?php for($i=1; $i<=$totalPages; $i++): ?>
                <li class="<?= $pg == $i ? 'active' : '' ?>"><a href="?pg=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
