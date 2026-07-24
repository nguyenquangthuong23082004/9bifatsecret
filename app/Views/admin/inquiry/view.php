<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <div class="listBottom">
        <table class="listTable">
            <tbody>
                <tr>
                    <th width="150">Company</th>
                    <td class="tal"><?= esc($item['company']) ?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td class="tal"><?= esc($item['location']) ?></td>
                </tr>
                <tr>
                    <th>Subject / Content</th>
                    <td class="tal"><?= esc($item['content']) ?></td>
                </tr>
                <tr>
                    <th>Manager</th>
                    <td class="tal"><?= esc($item['manager']) ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td class="tal"><?= esc($item['tel1']) ?>-<?= esc($item['tel2']) ?>-<?= esc($item['tel3']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td class="tal"><?= esc($item['email1']) ?>@<?= esc($item['email2']) ?></td>
                </tr>
                <tr>
                    <th>Detailed Message</th>
                    <td class="tal" style="height: 200px; vertical-align: top; padding: 15px;">
                        <?= nl2br(esc($item['d12'])) ?>
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td class="tal"><?= $item['r_date'] ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            <a href="<?= base_url('admin/inquiry') ?>" class="btn btn-default">Back to List</a>
            <a href="<?= base_url('admin/inquiry/delete/'.$item['idx']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
