<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <div class="listBottom">
        <form action="<?= base_url('admin/bbs/'.$code.'/save') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="bbs_idx" value="<?= $item['bbs_idx'] ?? '' ?>">
            
            <table class="listTable">
                <tbody>
                    <tr>
                        <th width="150">Subject</th>
                        <td class="tal"><input type="text" name="subject" value="<?= esc($item['subject'] ?? '') ?>" style="width: 90%;" required></td>
                    </tr>
                    <tr>
                        <th>Notice</th>
                        <td class="tal">
                            <input type="checkbox" name="notice_yn" value="Y" <?= ($item['notice_yn'] ?? '') == 'Y' ? 'checked' : '' ?>> Mark as Important Notice
                        </td>
                    </tr>
                    <tr>
                        <th>Content</th>
                        <td class="tal">
                            <textarea name="contents" style="width: 98%; height: 300px;"><?= esc($item['contents'] ?? '') ?></textarea>
                        </td>
                    </tr>
                    <?php for($i=1; $i<=5; $i++): ?>
                    <tr>
                        <th>Attachment <?= $i ?></th>
                        <td class="tal">
                            <?php if(!empty($item['rfile'.$i])): ?>
                                Existing: <?= esc($item['rfile'.$i]) ?><br>
                            <?php endif; ?>
                            <input type="file" name="file<?= $i ?>">
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <div style="margin-top: 20px; text-align: center;">
                <button type="submit" class="btn btn-primary">Save Post</button>
                <a href="<?= base_url('admin/bbs/'.$code) ?>" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
