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
                    <button data-trashed="false" data-url="<?= base_url('user/delete') ?>" type="button" class="bulkDeleteBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Hapus
                    </button>
                    <a href="/user/create" id="btnModalAddData" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Tambah User
                    </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanda Tangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-neutral-900 dark:divide-neutral-800">
                    <?php $no = 1; ?>
                    <?php foreach ($user as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><input type="checkbox" name="selected[]" value="<?= $item['id'] ?>" class="rowCheckbox form-checkbox"></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nip']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nama']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['jabatan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['pangkat']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['role']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= esc($item['is_penanda_tangan'] == 1 ? 'Ya' : 'Tidak') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:underline edit-btn"
                                    data-id="<?= $item['id'] ?>"
                                    data-nip="<?= esc($item['nip']) ?>"
                                    data-nama="<?= esc($item['nama']) ?>"
                                    data-jabatan="<?= esc($item['jabatan']) ?>"
                                    data-pangkat="<?= esc($item['pangkat']) ?>"
                                    data-role="<?= esc($item['role']) ?>"
                                    data-foto="<?= esc($item['foto_profil']) ?>"
                                    data-is_bendahara="<?= esc($item['is_bendahara']) ?>"
                                    data-is_penanda_tangan= "<?= esc($item['is_penanda_tangan']) ?>"
                                    data-kepala_balai= "<?= esc($item['kepala_balai']) ?>">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Untuk Edit User -->
<div id="editModalTemplate" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-lg w-[600px] mx-auto relative z-50">
        <h3 class="text-xl font-semibold mb-4">Edit User</h3>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">

            <input type="hidden" id="user_id" name="id">

            <!-- NIP -->
            <div class="mb-4">
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" id="nip" name="nip" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="nama" name="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>

            <!-- Jabatan -->
            <div class="mb-4">
                <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>

            <!-- Pangkat -->
            <div class="mb-4">
                <label for="pangkat" class="block text-sm font-medium text-gray-700">Pangkat</label>
                <input type="text" id="pangkat" name="pangkat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
                    <option value="admin">Admin</option>
                    <option value="pegawai">Pegawai</option>
                </select>
            </div>

            <!-- Penanda Tangan -->
            <div class="mb-4">
                <label for="is_penanda_tangan" class="block text-sm font-medium text-gray-700">Penanda Tangan</label>
                <select id="is_penanda_tangan" name="is_penanda_tangan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>

            <!-- Bendahara -->
            <div class="mb-4">
                <label for="is_bendahara" class="block text-sm font-medium text-gray-700">Bendahara</label>
                <select id="is_bendahara" name="is_bendahara" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>

            <!-- Kepala Balai -->
            <div class="mb-4">
                <label for="kepala_balai" class="block text-sm font-medium text-gray-700">Apakah Kepala Balai?</label>
                <select id="kepala_balai" name="kepala_balai" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:focus:ring-blue-500" required>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>

            <!-- Profile Picture -->
            <div class="mb-4">
                <label for="foto" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" id="foto" name="foto_profil" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                <p id="fotoPreview" class="text-sm text-gray-500 mt-2"></p>
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

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000, // 3 seconds
                showConfirmButton: false
            }).then(() => {
                window.location.href = "<?= base_url('admin/listuser') ?>";
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
        const editButtons = document.querySelectorAll('.edit-btn');
        const editModalTemplate = document.getElementById('editModalTemplate');
        const closeModalBtn = document.getElementById('closeModal');
        const editForm = document.getElementById('editForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ambil data dari atribut tombol
                const userId = button.getAttribute('data-id');
                const nip = button.getAttribute('data-nip');
                const nama = button.getAttribute('data-nama');
                const jabatan = button.getAttribute('data-jabatan');
                const pangkat = button.getAttribute('data-pangkat');
                const role = button.getAttribute('data-role');
                const foto = button.getAttribute('data-foto');
                const bendahara = button.getAttribute('data-is_bendahara');
                const penandatangan = button.getAttribute('data-is_penanda_tangan');
                const kepala = button.getAttribute('data-kepala_balai');
                console.log(kepala);
                
                

                document.getElementById('user_id').value = userId;
                document.getElementById('nip').value = nip;
                document.getElementById('nama').value = nama;
                document.getElementById('jabatan').value = jabatan;
                document.getElementById('pangkat').value = pangkat;
                document.getElementById('is_bendahara').value = bendahara;
                document.getElementById('is_penanda_tangan').value = penandatangan;
                document.getElementById('kepala_balai').value = kepala;



                const roleSelect = document.getElementById('role');
                Array.from(roleSelect.options).forEach(option => {
                    option.selected = (option.value === role);
                });

                const fotoPreview = document.getElementById('fotoPreview');
                if (foto) {
                    fotoPreview.textContent = `File saat ini: ${foto}`;
                } else {
                    fotoPreview.textContent = 'Belum ada foto.';
                }

                editForm.setAttribute('action', `/user/update/${userId}`);

                editModalTemplate.classList.remove('hidden');
            });
        });

        closeModalBtn.addEventListener('click', function() {
            editModalTemplate.classList.add('hidden');
        });
    });

    document.querySelector('.bulkDeleteBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert('Silakan pilih data yang ingin hapus.');
            return;
        }

        fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Sukses", "Data berhasil dihapus.", "success")
                    setTimeout(() => {
                        location.reload();
                    }, 2000)
                } else {
                    swal("Gagal", "Gagal menghapus data.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Terjadi kesalahan saat menghapus data.", "error");
            });
    });


    document.getElementById("search_table").addEventListener("keyup", function() {
        const searchValue = this.value.toLowerCase();
        const table = document.querySelector("tbody");
        const rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName("td");
            let isMatch = false;

            for (let j = 0; j < cells.length - 1; j++) {
                if (cells[j].textContent.toLowerCase().includes(searchValue)) {
                    isMatch = true;
                    break;
                }
            }

            row.style.display = isMatch ? "" : "none";
        }
    });
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>

<?= $this->endSection(); ?>