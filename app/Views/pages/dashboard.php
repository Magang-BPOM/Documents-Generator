<?= $this->extend('layout/main'); ?>

<?= $this->section('title'); ?>
Dashboard
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Application Layout
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Dashboard
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="m-4">

    <h1 class="text-3xl font-bold">Selamat datang di Dashboard</h1>
    <p>Ini isi konten dashboard</p>
</div>

<?= $this->endSection(); ?>