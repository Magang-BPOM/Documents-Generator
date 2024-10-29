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

<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Semua Surat</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal TTD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanda Tangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan TTD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-neutral-900 dark:divide-neutral-800">
                    <?php
                    $no = 1;
                    foreach ($surat_user as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nomor_surat']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nama']) ?> | <?= esc($item['nip']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['ttd_tanggal']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['penanda_tangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['jabatan_ttd']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?= base_url('dokumen/generate/' . $item['id']) ?>"
                                    class="text-blue-600 hover:underline">
                                    Lihat PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        moment.locale('id'); // Set locale ke bahasa Indonesia

        // Format semua tanggal di kolom Tanggal TTD
        document.querySelectorAll('td[data-date]').forEach(td => {
            const dateValue = td.getAttribute('data-date');
            if (dateValue) {
                const formattedDate = moment(dateValue).format('D MMMM YYYY');
                td.textContent = formattedDate;
            }
        });
    });
</script>

<?= $this->endSection(); ?>