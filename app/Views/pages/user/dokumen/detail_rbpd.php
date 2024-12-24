<?= $this->extend('pages/user/layout/main'); ?>

<?= $this->section('title'); ?>
Detail RBPD
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Detail RBPD
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6 text-lg">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Detail Rincian Biaya Perjalanan Dinas (RBPD)</h2>
        </div>

        <!-- Informasi Dokumen -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Detail Dokumen</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 border border-gray-300 rounded-lg p-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Nomor SPD</label>
                    <input type="text" value="<?= esc($surat['nomor_surat']) ?>" class="w-full rounded-md border-gray-300 p-3 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Tanggal Dibuat</label>
                    <input type="text" value="<?= date('Y-m-d', strtotime($surat['created_at'])) ?>" class="w-full rounded-md border-gray-300 p-3 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" readonly />
                </div>
            </div>
        </div>
        <!-- List RBPD -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Daftar RBPD</h3>
            <table class="w-full border-collapse border border-gray-300 mt-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 p-2">Nama Penerima</th>
                        <th class="border border-gray-300 p-2">NIP</th>
                        <th class="border border-gray-300 p-2">Status</th>
                        <th class="border border-gray-300 p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penerima as $user): ?>
                        <tr class="even:bg-gray-50 odd:bg-white">
                            <td class="border border-gray-300 p-3 text-gray-800 dark:text-neutral-300 text-center"><?= esc($user['nama']) ?></td>
                            <td class="border border-gray-300 p-3 text-gray-800 dark:text-neutral-300 text-center"><?= esc($user['nip']) ?></td>
                            <td class="border border-gray-300 p-3 text-center">
                                <?php if ($user['rbpd_created']): ?>
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded">Sudah Dibuat</span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded">Belum Dibuat</span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-gray-300 p-3 text-center">
                                <?php if ($user['rbpd_created']): ?>
                                    <form action="<?= base_url("dokumen/generateRBPD/{$surat['id']}/{$user['user_id']}") ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-block px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Lihat PDF</button>
                                    </form>
                                    <a href="<?= base_url("dokumen/editRBPD/{$surat['id']}/{$user['user_id']}") ?>" class="inline-block px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>
                                <?php else: ?>
                                    <a href="<?= base_url("dokumen/createRBPD/{$surat['id']}/{$user['user_id']}") ?>" class="inline-block px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Buat RBPD</a>
                                    <a href="<?= base_url("dokumen/editRBPD/{$surat['id']}/{$user['user_id']}") ?>" class="inline-block px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>
                                <?php endif; ?>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>