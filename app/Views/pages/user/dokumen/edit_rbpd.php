<?= $this->extend('pages/user/layout/main'); ?>

<?= $this->section('title'); ?>
Edit Rincian Biaya Perjalanan Dinas (RBPD)
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-8 text-lg">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mb-6">Edit Rincian Biaya Perjalanan Dinas (RBPD)</h2>

        <!-- Form -->
        <form method="POST" action="<?= base_url('dokumen/updateRBPD') ?>">
            <?= csrf_field(); ?>

            <input type="hidden" name="surat_id" value="<?= esc($surat['id']) ?>">
            <input type="hidden" name="surat_user_id" value="<?= esc($rbpd[0]['surat_user_id']) ?>">

            <!-- Data Surat -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-2">Nomor Surat</label>
                <input type="text" value="<?= esc($surat['nomor_surat']) ?>" class="w-full rounded-md border-gray-300 p-3 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" readonly />
            </div>

            <!-- Data Bendahara -->
            <!-- Data Bendahara -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-2">Bendahara</label>
                <?php if (!empty($bendahara)): ?>
                    <input type="hidden" name="bendahara_id" value="<?= esc($bendahara['id']) ?>">
                    <input type="text" value="<?= esc($bendahara['nama']) ?> (<?= esc($bendahara['nip']) ?>)" class="w-full rounded-md border-gray-300 p-3 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" readonly />
                <?php else: ?>
                    <p class="text-red-500">Data bendahara tidak ditemukan.</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($penandaTangan)): ?>
                    <input type="hidden" name="id_penanda_tangan" value="<?= esc($penandaTangan['id']) ?>">
                <?php else: ?>
                    <p class="text-red-500">Data bendahara tidak ditemukan.</p>
            <?php endif; ?>


            <!-- Rincian Biaya -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-2">Rincian Biaya</label>
                <table class="w-full border-collapse border border-gray-300 mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">Perincian Biaya</th>
                            <th class="border border-gray-300 p-2">Jumlah</th>
                            <th class="border border-gray-300 p-2">Keterangan</th>
                            <th class="border border-gray-300 p-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-biaya-body">
                        <?php foreach ($rbpd as $biaya): ?>
                            <tr>
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="perincian_biaya[]" value="<?= esc($biaya['perincian_biaya']) ?>" class="w-full rounded-md border-gray-300 p-2" required />
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="jumlah[]" value="<?= esc($biaya['jumlah']) ?>" class="w-full rounded-md border-gray-300 p-2" required />
                                </td>
                                <td class="border border-gray-300 p-2">
                                    <input type="text" name="keterangan[]" value="<?= esc($biaya['keterangan']) ?>" class="w-full rounded-md border-gray-300 p-2" />
                                </td>
                                <td class="border border-gray-300 p-2 text-center">
                                    <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-3 py-2 rounded">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" onclick="addRow()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Tambah Baris</button>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function addRow() {
        const tbody = document.getElementById('rincian-biaya-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border border-gray-300 p-2">
                <input type="text" name="perincian_biaya[]" class="w-full rounded-md border-gray-300 p-2" required />
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" name="jumlah[]" class="w-full rounded-md border-gray-300 p-2" required />
            </td>
            <td class="border border-gray-300 p-2">
                <input type="text" name="keterangan[]" class="w-full rounded-md border-gray-300 p-2" />
            </td>
            <td class="border border-gray-300 p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-3 py-2 rounded">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function removeRow(button) {
        button.closest('tr').remove();
    }
</script>
<?= $this->endSection(); ?>