<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <div class="listBottom">
        <form action="<?= base_url('admin/banners/save') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="bbs_idx" value="<?= $banner['bbs_idx'] ?? '' ?>">
            
            <table class="listTable">
                <tbody>
                    <tr>
                        <th width="150">Subject</th>
                        <td class="tal"><input type="text" name="subject" value="<?= esc($banner['subject'] ?? '') ?>" style="width: 90%;"></td>
                    </tr>
                    <tr>
                        <th>Link URL</th>
                        <td class="tal"><input type="text" name="url" value="<?= esc($banner['url'] ?? '') ?>" style="width: 90%;"></td>
                    </tr>
                    <tr>
                        <th>PC Image</th>
                        <td class="tal">
                            <?php if(!empty($banner['ufile6'])): ?>
                                <img src="<?= base_url('data/bbs/'.$banner['ufile6']) ?>" style="max-height: 100px;"><br>
                            <?php endif; ?>
                            <input type="file" name="ufile6">
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile Image</th>
                        <td class="tal">
                            <?php if(!empty($banner['ufile5'])): ?>
                                <img src="<?= base_url('data/bbs/'.$banner['ufile5']) ?>" style="max-height: 100px;"><br>
                            <?php endif; ?>
                            <input type="file" name="ufile5">
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="tal">
                            <input type="radio" name="notice_yn" value="Y" <?= ($banner['notice_yn'] ?? 'Y') == 'Y' ? 'checked' : '' ?>> Active
                            <input type="radio" name="notice_yn" value="N" <?= ($banner['notice_yn'] ?? '') == 'N' ? 'checked' : '' ?>> Hidden
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px; text-align: center;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?= base_url('admin/banners') ?>" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
