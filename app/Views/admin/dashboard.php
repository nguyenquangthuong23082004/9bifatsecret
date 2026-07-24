<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="listWrap">
    <div class="listTop">
        <div class="left">
            <p class="schTxt">Welcome to the Admin Panel.</p>
        </div>
    </div>

    <div class="listBottom" style="padding: 50px; text-align: center;">
        <h3>관리자 페이지에 오신 것을 환영합니다.</h3>
        <p style="margin-top: 20px;">상단 메뉴를 이용하여 사이트 콘텐츠를 관리할 수 있습니다.</p>
    </div>
</div>

<?= $this->endSection() ?>
