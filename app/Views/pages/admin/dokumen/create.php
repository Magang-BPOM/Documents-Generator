<?= $this->extend('pages/admin/layout/main'); ?>

<?= $this->section('title'); ?>
Pembuatan Surat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Pembuatan Dokumen
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6 text-lg">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Buat Dokumen Baru</h2>
        </div>

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
        <form method="POST" action="<?= base_url('admin/dokumen/store') ?>" ">
            <?= csrf_field(); ?>

            <div class=" grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="col-span-1">
                <label for="nomor_surat" class="required block font-medium text-gray-700 dark:text-neutral-300">Nomor Surat</label>
                <input value="<?= old('nomor_surat') ?>" type="text" name="nomor_surat" id="nomor_surat" placeholder="contoh:PW.01.05.11A.07.24.1816" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <div class="col-span-1">
                <label for="menimbang" class="required block font-medium text-gray-700 dark:text-neutral-300">Menimbang</label>
                <input value="<?= old('menimbang') ?>" type="text" name="menimbang" id="menimbang" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <div class="col-span-2 text-lg">
                <label class="required block font-medium text-gray-700 dark:text-neutral-300">Dasar</label>
                <div id="selected-dasar-container" class="mb-4"></div>

                <input type="hidden" name="selected_dasar" id="selected_dasar_input" value="<?= old('selected_dasar') ?>">

                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" id="selected_dasar"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            autocomplete="off"
                            placeholder="Ketik nama dasar...">
                        <div id="list-dasar" class="hidden absolute w-full mt-1 max-h-60 overflow-auto bg-white dark:bg-neutral-800 border border-gray-300 rounded-md shadow-lg z-10">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-2">
                <label class="required block font-medium text-gray-700 dark:text-neutral-300">Kepada</label>
                <div id="selected-users-container" class="mb-4">
                </div>

                <input type="hidden" name="selected_user" id="selected_users_input" value="<?= old('selected_user') ?>">

                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" id="selected_user"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            autocomplete="off"
                            placeholder="Ketik nama user...">
                        <div id="dropdown-list" class="hidden absolute w-full mt-1 max-h-60 overflow-auto bg-white dark:bg-neutral-800 border border-gray-300 rounded-md shadow-lg z-10">
                        </div>
                    </div>
                </div>
            </div>

            <!-- untuk -->

            <label for="untuk" class="required block font-medium text-gray-700 dark:text-neutral-300">Isian section Untuk</label>

            <div class="col-span-2">
                <label for="sebagai" class="required block text-gray-700 dark:text-neutral-300">Sebagai/Tujuan</label>
                <textarea name="sebagai" id="sebagai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 min-h-[3rem]" required><?= old('sebagai') ?></textarea>
            </div>
            <div class="col-span-1">
                <label for="waktu_mulai" class="required block text-gray-700 dark:text-neutral-300">Waktu pelaksanaan dimulai</label>
                <input value="<?= old('waktu_mulai') ?>" type="date" name="waktu_mulai" id="waktu_mulai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                <span id="error-message-start" class="text-red-500 mt-2 hidden"></span>
            </div>

            <div class="col-span-1">
                <label for="waktu_berakhir" class="required block text-gray-700 dark:text-neutral-300">Waktu pelaksanaan berakhir</label>
                <input value="<?= old('waktu_berakhir') ?>" type="date" name="waktu_berakhir" id="waktu_berakhir" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                <span id="error-message-end" class="text-red-500 mt-2 hidden"></span>
            </div>

            <div class="col-span-1">
                <label for="tujuan" class="required block mt-2 text-gray-700 dark:text-neutral-300">Alamat Lengkap Tujuan</label>
                <input value="<?= old('tujuan') ?>" type="text" name="tujuan" id="tujuan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
            </div>


            <div class="col-span-1">
                <label for="kota_tujuan" class="required block mt-2 text-gray-700 dark:text-neutral-300">Kota Tujuan</label>
                <input value="<?= old('kota_tujuan') ?>" type="text" name="kota_tujuan" id="kota_tujuan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
            </div>


            <!-- Opsi Tambahan -->
            <div class="col-span-2">
                <label class="block text-lg font-medium text-gray-700 dark:text-neutral-300 mb-4">
                    Opsi Tambahan Untuk Biaya
                </label>
                <div class="flex flex-col space-y-4">
                    <!-- Radio Button Group -->
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="opsi_tambahan" value="show"
                                class="form-radio text-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700"
                                onclick="toggleOpsiTambahan(true)">
                            <span class="text-gray-700 dark:text-neutral-300">Tambah</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="opsi_tambahan" value="hide" checked
                                class="form-radio text-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700"
                                onclick="toggleOpsiTambahan(false)">
                            <span class="text-gray-700 dark:text-neutral-300">Tidak</span>
                        </label>
                    </div>

                    <!-- Textarea for Opsi Tambahan -->
                    <div id="opsi-tambahan-container" class="hidden transition-all duration-300">
                        <textarea
                            name="biaya"
                            id="biaya"
                            rows="4"
                            placeholder="Contoh input: 'DIPA Balai Besar POM di Surabaya Tahun 2024.' (akhiran ';')"
                            class="mt-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 text-black p-3"></textarea>

                        <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                            Masukkan informasi tambahan dengan format yang sesuai.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-span-2">
                <label class="block text-lg font-medium text-gray-700 dark:text-neutral-300">Tempat Singgah/Hotel</label>
                <div id="tempat-singgah-container">
                    <div class="flex items-center mb-4 mt-4">
                        <input type="text"
                            name="tempat_singgah[berangkat_dari][]"
                            placeholder="Berangkat Dari"
                            class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            required>
                        <input type="text"
                            name="tempat_singgah[ke][]"
                            placeholder="Ke"
                            class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            required>
                        <input type="text"
                            name="tempat_singgah[nama_tempat][]"
                            placeholder="Nama Hotel/Tempat Singgah"
                            class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            required>
                        <input type="date"
                            name="tempat_singgah[tanggal][]"
                            class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                            required>
                        <button type="button" onclick="hapusTempatSinggah(this)" class="bg-red-500 text-white px-3 py-2 rounded">-</button>
                    </div>
                </div>
                <button type="button" onclick="tambahTempatSinggah()" class="w-full bg-neutral-50 border border-blue-500 px-3 py-2 rounded text-blue-500">Tambah Tempat Singgah</button>
            </div>



            <!-- Pembebanan Anggaran Dropdown -->
            <div class="col-span-1">
                <label for="id_pembebanan_anggaran" class="required block font-medium text-gray-700 dark:text-neutral-300">Pembebanan Anggaran</label>
                <select name="id_pembebanan_anggaran" id="id_pembebanan_anggaran"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                    <?php foreach ($pembebanan_anggaran as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= $item['akun'] ?> - <?= $item['instansi'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-span-1">
                <label for="kategori_biaya" class="required block font-medium text-gray-700 dark:text-neutral-300">Kategori Biaya</label>
                <select name="kategori_biaya" id="kategori_biaya"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                    <option value="A" <?= old('kategori_biaya') == 'A' ? 'selected' : '' ?>>Kategori A</option>
                    <option value="B" <?= old('kategori_biaya') == 'B' ? 'selected' : '' ?>>Kategori B</option>
                    <option value="C" <?= old('kategori_biaya') == 'C' ? 'selected' : '' ?>>Kategori C</option>
                    <option value="D" <?= old('kategori_biaya') == 'D' ? 'selected' : '' ?>>Kategori D</option>
                </select>
            </div>


            <div class="col-span-1">
                <label for="penanda_tangan" class="required block font-medium text-gray-700 dark:text-neutral-300">Penanda Tangan</label>
                <select name="penanda_tangan" id="penanda_tangan"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                    <?php foreach ($penanda_tangan as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= $item['nama'] ?> - <?= $item['jabatan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="col-span-1">
                <label for="ttd_tanggal" class="required block text-gray-700 dark:text-neutral-300">Tanggal Tanda Tangan</label>
                <input type="date" name="ttd_tanggal" id="ttd_tanggal" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                <span id="error-message-end" class="text-red-500 mt-2 hidden"></span>
            </div>


            <!-- Button -->
            <div class="col-span-2 flex justify-start space-x-2">
                <a href="/surat" class="inline-flex items-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-gray-500 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                    Kembali
                </a>

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                    Buat Surat
                </button>
            </div>
    </div>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function tambahTempatSinggah() {
        const container = document.getElementById('tempat-singgah-container');
        const newInput = document.createElement('div');
        newInput.className = 'flex items-center mb-2';
        newInput.innerHTML = `
                     
            <input type="text" 
                name="tempat_singgah[berangkat_dari][]" 
                placeholder="Berangkat Dari"
                class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                required>
            <input type="text" 
                name="tempat_singgah[ke][]" 
                placeholder="Ke"
                class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                required>
            <input type="text" 
                name="tempat_singgah[nama_tempat][]" 
                placeholder="Nama Hotel/Tempat Singgah"
                class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                required>
            <input type="date" 
                name="tempat_singgah[tanggal][]" 
                class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                required>
            
            <button type="button" onclick="hapusTempatSinggah(this)" class="bg-red-500 text-white px-3 py-2 rounded">-</button>
     
        `;
        container.appendChild(newInput);
    }

    function hapusTempatSinggah(btn) {
        btn.closest('.flex').remove();
    }

    document.addEventListener("DOMContentLoaded", function() {

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 4000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "<?= base_url('admin/dokumen/create') ?>";
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


    function toggleOpsiTambahan(isVisible) {
        const container = document.getElementById('opsi-tambahan-container');
        if (isVisible) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }


    (function() {
        const selectedDasar = new Set();
        const dropdownDasar = document.getElementById('list-dasar');
        const dasarInput = document.getElementById('selected_dasar');
        const dasarContainer = document.getElementById('selected-dasar-container');
        const dasarHiddenInput = document.getElementById('selected_dasar_input');

        let dasar = <?= json_encode($dasar); ?>;


        function renderDropdown(showDasar) {
            dropdownDasar.innerHTML = showDasar.map(list => `
            <div class="dasar-option p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer"
                data-id="${list.id}"
                data-undang="${list.undang.replace(/\n/g, '')}">
                <div class="font-medium">${list.undang}</div>
            </div>
        `).join('');

            document.querySelectorAll('.dasar-option').forEach(option => {
                option.addEventListener('click', function() {
                    const dasarId = this.dataset.id;
                    if (!selectedDasar.has(dasarId)) {
                        addSelectedDasar({
                            id: dasarId,
                            undang: this.dataset.undang
                        });
                    }
                    dasarInput.value = '';
                    dropdownDasar.classList.add('hidden');
                });
            });
        }

        dasarInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const showDasar = searchTerm ?
                dasar.filter(d => d.undang.toLowerCase().includes(searchTerm)) :
                dasar;

            renderDropdown(showDasar);
            dropdownDasar.classList.remove('hidden');
        });

        dasarInput.addEventListener('focus', function() {
            renderDropdown(dasar);
            dropdownDasar.classList.remove('hidden');
        });

        function addSelectedDasar(dasar) {
            selectedDasar.add(dasar.id);

            const dasarElement = document.createElement('div');
            dasarElement.className = 'flex items-center justify-between p-3 mb-2 bg-gray-100 dark:bg-neutral-700 rounded-md';
            dasarElement.innerHTML = `
            <div>
                <div class="font-medium">${dasar.undang}</div>
            </div>
            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeDasar('${dasar.id}', this)">
                ✕
            </button>
        `;

            dasarContainer.appendChild(dasarElement);
            updateSelectedDasarInput();
        }

        window.removeDasar = function(dasarId, button) {
            selectedDasar.delete(dasarId);
            button.closest('div').remove();
            updateSelectedDasarInput();
        };


        function updateSelectedDasarInput() {
            dasarHiddenInput.value = Array.from(selectedDasar).join(',');
        }

        document.addEventListener('click', function(e) {
            if (!dasarInput.contains(e.target) && !dropdownDasar.contains(e.target)) {
                dropdownDasar.classList.add('hidden');
            }
        });
    })();


    (function() {
        const selectedUsers = new Set();
        const dropdownList = document.getElementById('dropdown-list');
        const userInput = document.getElementById('selected_user');
        const usersContainer = document.getElementById('selected-users-container');
        const usersHiddenInput = document.getElementById('selected_users_input');

        let users = <?= json_encode($users); ?>;

        function renderDropdown(filteredUsers) {
            dropdownList.innerHTML = filteredUsers.map(user => `
            <div class="user-option p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer"
                data-id="${user.id}"
                data-nama="${user.nama}"
                data-nip="${user.nip}"
                data-jabatan="${user.jabatan}"
                data-pangkat="${user.pangkat}">
                <div class="font-medium">${user.nama}</div>
                <div class= text-gray-600 dark:text-gray-400">
                    NIP: ${user.nip} | ${user.jabatan}
                </div>
            </div>
        `).join('');

            document.querySelectorAll('.user-option').forEach(option => {
                option.addEventListener('click', function() {
                    const userId = this.dataset.id;
                    if (!selectedUsers.has(userId)) {
                        addSelectedUser({
                            id: userId,
                            nama: this.dataset.nama,
                            nip: this.dataset.nip,
                            jabatan: this.dataset.jabatan,
                            pangkat: this.dataset.pangkat
                        });
                    }
                    userInput.value = '';
                    dropdownList.classList.add('hidden');
                });
            });
        }

        userInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredUsers = searchTerm ?
                users.filter(u => u.nama.toLowerCase().includes(searchTerm)) :
                users;

            renderDropdown(filteredUsers);
            dropdownList.classList.remove('hidden');
        });

        userInput.addEventListener('focus', function() {
            renderDropdown(users);
            dropdownList.classList.remove('hidden');
        });

        function addSelectedUser(user) {
            selectedUsers.add(user.id);

            const userElement = document.createElement('div');
            userElement.className = 'flex items-center justify-between p-3 mb-2 bg-gray-100 dark:bg-neutral-700 rounded-md';
            userElement.innerHTML = `
            <div>
                <div class="font-medium">${user.nama}</div>
                <div class= text-gray-600 dark:text-gray-300">
                    NIP: ${user.nip} | Jabatan: ${user.jabatan} | Pangkat: ${user.pangkat}
                </div>
            </div>
            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeUser('${user.id}', this)">
                ✕
            </button>
        `;

            usersContainer.appendChild(userElement);
            updateSelectedUsersInput();
        }

        window.removeUser = function(userId, button) {
            selectedUsers.delete(userId);
            button.closest('div').remove();
            updateSelectedUsersInput();
        };


        function updateSelectedUsersInput() {
            usersHiddenInput.value = Array.from(selectedUsers).join(',');
        }

        document.addEventListener('click', function(e) {
            if (!userInput.contains(e.target) && !dropdownList.contains(e.target)) {
                dropdownList.classList.add('hidden');
            }
        });
    })();


    moment.locale('id');
    const dateInput = document.getElementById('waktu');

    dateInput.addEventListener('change', () => {

        const dateValue = dateInput.value;
        const formattedDate = moment(dateValue).format('dddd, D MMMM YYYY');
        console.log('Formatted Date in Indonesian:', formattedDate);
    });


    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split("T")[0];

        const waktuMulai = document.getElementById("waktu_mulai");
        const waktuBerakhir = document.getElementById("waktu_berakhir");

        // Set minimum date untuk waktu mulai dan berakhir
        waktuMulai.setAttribute("min", today);
        waktuBerakhir.setAttribute("min", today);

        // Validasi waktu mulai
        waktuMulai.addEventListener("change", function() {
            const selectedStartDate = waktuMulai.value;

            if (new Date(selectedStartDate) < new Date(today)) {
                alert("Waktu pelaksanaan dimulai tidak boleh sebelum hari ini.");
                waktuMulai.value = today;
            }

            // Pastikan waktu berakhir tidak bisa sebelum waktu mulai
            waktuBerakhir.setAttribute("min", selectedStartDate);
        });

        // Validasi waktu berakhir
        waktuBerakhir.addEventListener("change", function() {
            const startDate = new Date(waktuMulai.value);
            const endDate = new Date(waktuBerakhir.value);

            if (endDate < startDate) {
                alert("Waktu pelaksanaan berakhir tidak boleh sebelum waktu mulai.");
                waktuBerakhir.value = "";
            }
        });
    });
</script>


<?= $this->endSection(); ?>