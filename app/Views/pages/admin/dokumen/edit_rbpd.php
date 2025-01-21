<?= $this->extend('pages/admin/layout/main'); ?>

<?= $this->section('title'); ?>
Edit Rincian Biaya Perjalanan Dinas (RBPD)
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Edit RBPD
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-8 text-lg">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mb-6">Edit Rincian Biaya Perjalanan Dinas (RBPD)</h2>

        <!-- Success Alert -->
        <?php if (session()->getFlashdata('message')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?= session()->getFlashdata('message') ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Alert -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc pl-5">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?= base_url('dokumen/updateRBPD') ?>">
            <?= csrf_field(); ?>
            <input type="hidden" name="surat_id" value="<?= esc($surat['id']) ?>">
            <input type="hidden" name="penerima_id" value="<?= esc($penerima['id']) ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nomor SPD -->
                <div class="border border-gray-300 rounded-lg p-4">
                    <label class="block text-gray-700 dark:text-neutral-300 mb-2 font-medium">Lampiran SPD Nomor</label>
                    <input type="text" name="nomor_spd" value="<?= esc($surat['nomor_surat']) ?>" class="w-full rounded-md border-gray-300 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3" readonly />
                </div>

                <!-- Tanggal -->
                <div class="border border-gray-300 rounded-lg p-4">
                    <label class="block text-gray-700 dark:text-neutral-300 mb-2 font-medium">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= esc($rbpd['tanggal']) ?>" class="w-full rounded-md border-gray-300 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3" required />
                </div>
            </div>

            <!-- Rincian Biaya -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium">Rincian Biaya</label>
                <table class="w-full border-collapse border border-gray-300 mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2 text-left font-medium text-gray-700">Perincian Biaya</th>
                            <th class="border border-gray-300 p-2 text-left font-medium text-gray-700">Jumlah</th>
                            <th class="border border-gray-300 p-2 text-left font-medium text-gray-700">Keterangan</th>
                            <th class="border border-gray-300 p-2 text-center font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-biaya-body">
                        <?php if (!empty($rbpd)): ?>
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
                        <?php else: ?>
                            <!-- Tampilkan baris kosong jika data tidak ada -->
                            <tr>
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
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
                <button type="button" onclick="addRow()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Tambah Baris</button>
            </div>

            <!-- Bendahara -->
            <div class="mb-4 border border-gray-300 rounded-lg p-4">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-2">Bendahara</label>
                <select name="bendahara_id" class="w-full rounded-md border-gray-300 p-3" required>
                    <?php foreach ($bendahara as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= $item['id'] == $rbpd['bendahara_id'] ? 'selected' : '' ?>>
                            <?= esc($item['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Penanda Tangan -->
            <div class="mb-6 border border-gray-300 rounded-lg p-4">
                <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-2">Penanda Tangan</label>
                <?php if (isset($penanda_tangan)): ?>
                    <select name="id_penanda_tangan" class="w-full rounded-md border-gray-300 p-3" required>
                        <option value="<?= esc($penanda_tangan['id']) ?>" selected><?= esc($penanda_tangan['nama']) ?> - <?= esc($penanda_tangan['nip']) ?></option>
                    </select>
                <?php else: ?>
                    <p class="text-red-500">Data penanda tangan tidak tersedia.</p>
                <?php endif; ?>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>

    // fungsi untuk menambahkan bagian rincian biaya perjalanan
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

    // fungsi untuk menghapus item rincian biaya yang dipilih
    function removeRow(button) {
        button.closest('tr').remove();
    }
</script>

<?= $this->endSection(); ?>
