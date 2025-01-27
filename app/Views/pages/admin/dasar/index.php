<?= $this->extend('pages/admin/layout/main'); ?>

<?= $this->section('title'); ?>
Semua User
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Semua User
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">

        <!-- Header and search bar -->
        <div class="py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
            <div class="sm:col-span-1">
                <label for="search_table" class="sr-only">Search</label>
                <div class="relative">
                    <input type="text" id="search_table" name="search_table" class="py-2 px-3 ps-11 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Search">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                        <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow">
                <div class="flex justify-end gap-x-2">
                    <button id="btnModalAddData" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Tambah Undang-undang
                    </button>
                </div>
            </div>
        </div>

        <!-- Table to display users -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="form-checkbox">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Undang-undang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-neutral-900 dark:divide-neutral-800">
                    <?php if (!empty($dasar)): ?>
                        <?php foreach ($dasar as $key => $item): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="form-checkbox">
                                </td>
                                <td class="px-6 py-4"><?= $key + 1; ?></td>
                                <td class="px-6 py-4"><?= esc($item['undang']); ?></td>
                                <td class="px-6 py-4">
                                    <button class="text-blue-600 hover:underline edit-btn"
                                        data-id="<?= $item['id'] ?>"
                                        data-undang="<?= esc($item['undang']) ?>">
                                        Edit
                                    </button> |
                                    <button class="text-red-500 hover:text-red-700 delete-btn"
                                        data-id="<?= $item['id'] ?>"
                                        data-undang="<?= esc($item['undang']) ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<div id="addModalTemplate" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-lg w-[600px] mx-auto relative z-50">
        <h3 class="text-xl font-semibold mb-4">Tambah Undang-undang</h3>
        <form id="addForm" action="/dasar/store" method="POST" enctype="multipart/form-data">

            <!-- undang -->
            <div class="mb-4">
                <label for="undang" class="block text-sm font-medium text-gray-700">Undang-undang</label>
                <input type="text" id="undang" name="undang" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>

        <button id="closeAddModal" class="absolute top-2 right-2 text-xl font-bold text-gray-500">&times;</button>
    </div>
</div>

<div id="editModalTemplate" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-lg w-[600px] mx-auto relative z-50">
        <h3 class="text-xl font-semibold mb-4">Edit Undang-undang</h3>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">

            <input type="hidden" id="id_undang" name="id">
            <!-- undang -->
            <div class="mb-4">
                <label for="undang" class="block text-sm font-medium text-gray-700">undang</label>
                <input type="text" id="editundang" name="undang" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-lg">Simpan Perubahan</button>
            </div>
        </form>

        <button id="closeModal" class="absolute top-2 right-2 text-xl font-bold text-gray-500">&times;</button>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const dasarId = button.getAttribute('data-id');
                const undang = button.getAttribute('data-undang');

                // Konfirmasi hapus
                Swal.fire({
                    title: `Apakah Anda yakin ingin menghapus undang-undang "${undang}"?`,
                    text: "Data yang dihapus tidak dapat dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim request hapus
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/dasar/delete/${dasarId}`;

                        // Menambahkan metode DELETE melalui input (karena form tidak bisa langsung mengirim DELETE)
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        // Menambahkan form ke body dan mengsubmitnya
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000, // 3 seconds
                showConfirmButton: false
            }).then(() => {
                window.location.href = "<?= base_url('/dasar') ?>";
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Modal Add Data
        const addModalTemplate = document.getElementById('addModalTemplate');
        const btnModalAddData = document.getElementById('btnModalAddData');
        const closeAddModalBtn = document.getElementById('closeAddModal');

        // Modal Edit Data
        const editModalTemplate = document.getElementById('editModalTemplate');
        const closeModalBtn = document.getElementById('closeModal');
        const editForm = document.getElementById('editForm');
        const editButtons = document.querySelectorAll('.edit-btn');

        // Tampilkan modal tambah data
        btnModalAddData.addEventListener('click', function() {
            addModalTemplate.classList.remove('hidden');
        });

        // Tutup modal tambah data
        closeAddModalBtn.addEventListener('click', function() {
            addModalTemplate.classList.add('hidden');
        });

        // Tampilkan modal edit data dan isi form dengan data yang ada
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const UndangId = button.getAttribute('data-id');
                const undang = button.getAttribute('data-undang');

                document.getElementById('id_undang').value = UndangId;
                document.getElementById('editundang').value = undang;

                // Update action form untuk mengarah ke route update yang sesuai
                editForm.setAttribute('action', `/dasar/update/${UndangId}`);

                // Tampilkan modal edit
                editModalTemplate.classList.remove('hidden');
            });
        });

        // Tutup modal edit data
        closeModalBtn.addEventListener('click', function() {
            editModalTemplate.classList.add('hidden');
        });
    });
</script>



<?= $this->endSection(); ?>