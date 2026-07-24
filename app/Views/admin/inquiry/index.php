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
            <p class="schTxt">Total: <?= $totalCount ?> inquiries found.</p>
        </div>
    </div>

    <div class="listBottom">
        <table cellpadding="0" cellspacing="0" class="listTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Company</th>
                    <th>Manager</th>
                    <th>Email / Tel</th>
                    <th>Date</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($list as $row): ?>
                <tr>
                    <td><?= $row['idx'] ?></td>
                    <td class="tal"><?= esc($row['company']) ?></td>
                    <td><?= esc($row['manager']) ?></td>
                    <td class="tal">
                        <?= esc($row['email1']) ?>@<?= esc($row['email2']) ?><br>
                        <?= esc($row['tel1']) ?>-<?= esc($row['tel2']) ?>-<?= esc($row['tel3']) ?>
                    </td>
                    <td><?= date('Y.m.d', strtotime($row['r_date'])) ?></td>
                    <td>
                        <a href="<?= base_url('admin/inquiry/view/'.$row['idx']) ?>" class="btn btn-default">View</a>
                        <a href="<?= base_url('admin/inquiry/delete/'.$row['idx']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($list)): ?>
                    <tr><td colspan="6">No inquiries found.</td></tr>
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
