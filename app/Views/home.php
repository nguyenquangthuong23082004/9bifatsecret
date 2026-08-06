<?php
/**
 * BITIEL(비티엘) diet landing – Frame 5.
 * Layout: layouts/main (head + header + footer) · section partials.
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('partials/btl/main_visual') ?>
<?= $this->include('partials/btl/philosophy') ?>
<?= $this->include('partials/btl/career') ?>
<?= $this->include('partials/btl/program') ?>
<?= $this->include('partials/btl/dual') ?>
<?= $this->include('partials/btl/ticket') ?>
<?= $this->include('partials/btl/consult') ?>
<?= $this->include('partials/btl/yoyo') ?>
<?= $this->include('partials/btl/features') ?>
<?= $this->include('partials/btl/menopause') ?>
<?= $this->include('partials/btl/thermo') ?>
<?= $this->include('partials/btl/results') ?>
<?= $this->include('partials/btl/turn') ?>
<?= $this->include('partials/btl/faq') ?>
<?= $this->include('partials/btl/contact') ?>
<?= $this->include('partials/btl/popups') ?>
<?= $this->endSection() ?>
