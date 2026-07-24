<!DOCTYPE html>
<html lang="ko">
<?= $this->include('layouts/head') ?>
<body>
<div class="btl">
    <?= $this->include('layouts/header') ?>

    <main class="btl-page">
        <?= $this->renderSection('content') ?>
    </main><!-- /.btl-page -->
</div><!-- /.btl -->

<?= $this->include('layouts/footer') ?>
</body>
</html>
