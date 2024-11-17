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
        <form method="POST" action="<?= base_url('dokumen/store') ?>" ">
            <?= csrf_field(); ?>

            <div class=" grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="col-span-1">
                <label for="nomor_surat" class="required block font-medium text-gray-700 dark:text-neutral-300">Nomor Surat</label>
                <input type="text" name="nomor_surat" id="nomor_surat" placeholder="contoh:PW.01.05.11A.07.24.1816" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <div class="col-span-1">
                <label for="menimbang" class="required block font-medium text-gray-700 dark:text-neutral-300">Menimbang</label>
                <input type="text" name="menimbang" id="menimbang" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
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

            <!-- untuk -->
            <div class="col-span-1 font-normal text-lg">
                <label for="untuk" class="required block text-gray-700 dark:text-neutral-300">Untuk :</label>
                <div class="px-6">
                    <div class="mb-4">
                        <label for="sebagai" class="required block mt-2 text-gray-700 dark:text-neutral-300">Sebagai</label>
                        <input type="text" name="sebagai" id="sebagai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                    </div>

                    <div class="mb-4">
                        <label for="waktu" class="required mt-7 block text-gray-700 dark:text-neutral-300">Waktu pelaksanaan</label>
                        <input type="date" name="waktu" id="waktu" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                        <span id="error-message" class="text-red-500 mt-2 hidden"></span>
                    </div>

                    <div class="mb-4">
                        <label for="tujuan" class="required block mt-2 text-gray-700 dark:text-neutral-300">Tujuan</label>
                        <input type="text" name="tujuan" id="tujuan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                    </div>

                    <!-- Radio untuk mengontrol opsi tambahan -->
                    <div class="mb-4 flex flex-col items-center">
                        <label class="block mb-2 text-gray-700 dark:text-neutral-300">Opsi Tambahan</label>
                        <div class="flex items-center justify-center gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="opsi_tambahan" value="show" class="mr-2" onclick="toggleOpsiTambahan(true)">
                                Tambah
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="opsi_tambahan" value="hide" class="mr-2" onclick="toggleOpsiTambahan(false)" checked>
                                Tidak
                            </label>
                        </div>
                    </div>

                    <!-- Textarea Opsi Tambahan -->
                    <div class="mb-4 hidden" id="opsi-tambahan-container">
                        <textarea name="untuk" id="untuk" rows="4" placeholder="Contoh input 'Biaya : DIPA Balai Besar POM di Surabaya Tahun 2024.;' akhiran ';'" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3"></textarea>
                    </div>

                </div>
            </div>

            <div class="col-span-1 pt-4">
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


            <div class="col-span-1">
                <div class="mb-4">
                    <label for="penanda_tangan" class="required block font-medium text-gray-700 dark:text-neutral-300">Penanda Tangan</label>
                    <input type="text" name="penanda_tangan" id="penanda_tangan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                </div>
                <div>
                    <label for="jabatan_ttd" class="required block font-medium text-gray-700 dark:text-neutral-300">Jabatan Penanda Tangan</label>
                    <input type="text" name="jabatan_ttd" id="jabatan_ttd" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script>
    function toggleOpsiTambahan(show) {
        const opsiTambahanContainer = document.getElementById('opsi-tambahan-container');
        const opsiTambahanInput = document.getElementById('opsi_tambahan_input');
        if (show) {
            opsiTambahanContainer.classList.remove('hidden');
            opsiTambahanInput.value = 'show';
        } else {
            opsiTambahanContainer.classList.add('hidden');
        }
    }



    // Script Dasar
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


    // Script Users
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
</script>
<script>
    // Atur Moment.js ke bahasa Indonesia
    moment.locale('id');

    // Ambil elemen input
    const dateInput = document.getElementById('waktu');

    // Tambahkan event listener untuk mengolah input
    dateInput.addEventListener('change', () => {
        // Ambil nilai dari input type="date"
        const dateValue = dateInput.value; // Format default: YYYY-MM-DD

        // Ubah ke format bahasa Indonesia
        const formattedDate = moment(dateValue).format('dddd, D MMMM YYYY'); // Contoh format: Senin, 28 Oktober 2024

        // Tampilkan hasilnya di console atau di elemen lain
        console.log('Formatted Date in Indonesian:', formattedDate);
    });
</script>



<?= $this->endSection(); ?>