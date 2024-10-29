<?= $this->extend('layout/main'); ?>

<?= $this->section('title'); ?>
Semua Dokumen
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Semua Dokumen
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6 m-6">
    <div class="flex items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Semua dokumen</h2>
    </div>
</div>

<?= $this->endSection(); ?>