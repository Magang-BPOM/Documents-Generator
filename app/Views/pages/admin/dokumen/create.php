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
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6">
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
                <label for="nomor_surat" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Nomor Surat</label>
                <input type="text" name="nomor_surat" id="nomor_surat" placeholder="contoh:PW.01.05.11A.07.24.1816" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
            </div>


            <div class="col-span-1">
                <label for="menimbang" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Menimbang</label>
                <input type="text" name="menimbang" id="menimbang" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <div class="col-span-1">
                <label for="dasar" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Dasar</label>
                <textarea name="dasar" id="dasar" rows="4" placeholder="Masukkan setiap poin dasar dengan akhiran ;" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3" required></textarea>

            </div>

            <div class="col-span-1">
                <label for="untuk" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Untuk</label>
                <textarea name="untuk" id="untuk" rows="4" placeholder="Masukkan setiap poin tugas dengan akhiran ;" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3" required></textarea>
            </div>



            <div class="col-span-2">
                <label class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Kepada</label>
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
                <label for="penanda_tangan" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Penanda Tangan</label>
                <input type="text" name="penanda_tangan" id="penanda_tangan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <div class="col-span-1">
                <label for="jabatan_ttd" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Jabatan Penanda Tangan</label>
                <input type="text" name="jabatan_ttd" id="jabatan_ttd" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
            </div>

            <!-- Tanggal TTD -->
            <div class="col-span-1">
                <label for="ttd_tanggal" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Tanggal TTD</label>
                <input type="date" name="ttd_tanggal" id="ttd_tanggal" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                <span id="error-message" class="text-red-500 text-sm mt-2 hidden"></span>
            </div>


            <!-- Button -->
            <div class="col-span-2 flex justify-start space-x-2">
                <a href="/surat" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-500 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                    Kembali
                </a>

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
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
    const selectedUsers = new Set();
    const dropdownList = document.getElementById('dropdown-list');
    const userInput = document.getElementById('selected_user');
    let users = <?= json_encode($users) ?>;


    function renderDropdown(filteredUsers) {
        dropdownList.innerHTML = filteredUsers.map(user => `
        <div class="user-option p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer"
             data-id="${user.id}"
             data-nama="${user.nama}"
             data-nip="${user.nip}"
             data-jabatan="${user.jabatan}"
             data-pangkat="${user.pangkat}">
            <div class="font-medium">${user.nama}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                NIP: ${user.nip} | ${user.jabatan}
            </div>
        </div>
    `).join('');

        // Add click handlers to options
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

    // Setup input listener
    userInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        if (searchTerm.length < 1) {
            renderDropdown(users);
            dropdownList.classList.remove('hidden');
            return;
        }

        const filteredUsers = users.filter(user =>
            user.nama.toLowerCase().includes(searchTerm)
        );

        renderDropdown(filteredUsers);
        dropdownList.classList.remove('hidden');
    });

    // Show all users on input focus
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
            <div class="text-sm text-gray-600 dark:text-gray-300">
                NIP: ${user.nip} | Jabatan: ${user.jabatan} | Pangkat: ${user.pangkat}
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800" onclick="removeUser('${user.id}', this)">
            ✕
        </button>
    `;

        document.getElementById('selected-users-container').appendChild(userElement);
        updateSelectedUsersInput();
    }

    function removeUser(userId, button) {
        selectedUsers.delete(userId);
        button.closest('div').remove();
        updateSelectedUsersInput();
    }

    function updateSelectedUsersInput() {
        document.getElementById('selected_users_input').value = Array.from(selectedUsers).join(',');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userInput.contains(e.target) && !dropdownList.contains(e.target)) {
            dropdownList.classList.add('hidden');
        }
    });

    // Handle Enter key
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const firstOption = dropdownList.querySelector('.user-option');
            if (firstOption) {
                firstOption.click();
            }
        }
    });

    
</script>
<script>
    // Atur Moment.js ke bahasa Indonesia
    moment.locale('id');

    // Ambil elemen input
    const dateInput = document.getElementById('ttd_tanggal');

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