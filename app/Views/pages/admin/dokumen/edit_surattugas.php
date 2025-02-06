<?= $this->extend('pages/admin/layout/main'); ?>

<?= $this->section('title'); ?>
Edit Surat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Surat Menyurat
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Edit Dokumen
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6 text-lg">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Edit Dokumen Baru</h2>
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
        <form method="POST" action="/admin/dokumen/update/<?= $surat['id']; ?>">
            <?= csrf_field(); ?>

            <div class=" grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="col-span-1">
                    <label for="nomor_surat" class="required block font-medium text-gray-700 dark:text-neutral-300">Nomor Surat</label>
                    <input value="<?= old('nomor_surat', $surat['nomor_surat']) ?>" type="text" name="nomor_surat" id="nomor_surat" placeholder="contoh:PW.01.05.11A.07.24.1816" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                </div>

                <div class="col-span-1">
                    <label for="menimbang" class="required block font-medium text-gray-700 dark:text-neutral-300">Menimbang</label>
                    <input value="<?= old('menimbang', $surat['menimbang']) ?>" type="text" name="menimbang" id="menimbang" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                </div>

                <!-- Dasar -->
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
                    <textarea name="sebagai" id="sebagai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 min-h-[3rem]" required><?= old('sebagai', $surat['sebagai']) ?></textarea>
                </div>

                <div class="col-span-1">
                    <label for="waktu_mulai" class="required block text-gray-700 dark:text-neutral-300">Waktu pelaksanaan dimulai</label>
                    <input <input value="<?= old('waktu_mulai', $surat['waktu_mulai']) ?>" type="date" name="waktu_mulai" id="waktu_mulai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                    <span id="error-message-start" class="text-red-500 mt-2 hidden"></span>
                </div>

                <div class="col-span-1">
                    <label for="waktu_berakhir" class="required block text-gray-700 dark:text-neutral-300">Waktu pelaksanaan berakhir</label>
                    <input value="<?= old('waktu_berakhir', $surat['waktu_berakhir']) ?>" type="date" name="waktu_berakhir" id="waktu_berakhir" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                    <span id="error-message-end" class="text-red-500 mt-2 hidden"></span>
                </div>

                <div class="col-span-1">
                    <label for="tujuan" class="required block mt-2 text-gray-700 dark:text-neutral-300">Alamat Lengkap Tujuan</label>
                    <input value="<?= old('tujuan', $surat['tujuan']) ?>" type="text" name="tujuan" id="tujuan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                </div>


                <div class="col-span-1">
                    <label for="kota_tujuan" class="required block mt-2 text-gray-700 dark:text-neutral-300">Kota Tujuan</label>
                    <input value="<?= old('kota_tujuan', $surat['kota_tujuan']) ?>" type="text" name="kota_tujuan" id="kota_tujuan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
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
                                <input type="radio" name="opsi_tambahan" value="hide"
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
                                class="mt-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 text-black p-3 dark:text-neutral-300"><?= old('biaya', $surat['biaya']) ?></textarea>
                            <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                                Masukkan informasi tambahan dengan format yang sesuai.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bagian penambahan tempat singgah -->
                <div class="col-span-2">
                    <label class="block text-lg font-medium text-gray-700 dark:text-neutral-300">Tempat Singgah/Hotel</label>
                    <div id="tempat-singgah-container">
                        <?php if (!empty($tempatSinggahData)) : ?>
                            <?php foreach ($tempatSinggahData as $index => $tempat) : ?>
                                <div class="flex items-center mb-4 mt-4">
                                    <input type="text"
                                        value="<?= old("tempat_singgah.berangkat_dari.$index", $tempat['berangkat_dari']) ?>"
                                        name="tempat_singgah[berangkat_dari][]"
                                        placeholder="Berangkat Dari"
                                        class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                                        required>

                                    <input type="text"
                                        value="<?= old("tempat_singgah.ke.$index", $tempat['ke']) ?>"
                                        name="tempat_singgah[ke][]"
                                        placeholder="Ke"
                                        class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                                        required>

                                    <input type="text"
                                        value="<?= old("tempat_singgah.nama_tempat.$index", $tempat['nama_tempat']) ?>"
                                        name="tempat_singgah[nama_tempat][]"
                                        placeholder="Nama Hotel/Tempat Singgah"
                                        class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                                        required>

                                    <input type="date"
                                        value="<?= old("tempat_singgah.tanggal.$index", $tempat['tanggal']) ?>"
                                        name="tempat_singgah[tanggal][]"
                                        class="flex-grow mr-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12"
                                        required>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <!-- Jika tidak ada data, tampilkan satu input kosong -->
                            <div class="flex items-center mb-4 mt-4">
                                <input type="text" name="tempat_singgah[berangkat_dari][]" placeholder="Berangkat Dari" class="flex-grow mr-2 block w-full rounded-md border border-gray-300 p-3 h-12" required>
                                <input type="text" name="tempat_singgah[ke][]" placeholder="Ke" class="flex-grow mr-2 block w-full rounded-md border border-gray-300 p-3 h-12" required>
                                <input type="text" name="tempat_singgah[nama_tempat][]" placeholder="Nama Hotel/Tempat Singgah" class="flex-grow mr-2 block w-full rounded-md border border-gray-300 p-3 h-12" required>
                                <input type="date" name="tempat_singgah[tanggal][]" class="flex-grow mr-2 block w-full rounded-md border border-gray-300 p-3 h-12" required>
                            </div>
                        <?php endif; ?>
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
                        <option value="<?= old('kategori_biaya', $surat['kategori_biaya']) ?>"><?= old('kategori_biaya', $surat['kategori_biaya']) ? 'Kategori ' . old('kategori_biaya', $surat['kategori_biaya']) : '' ?></option>
                        <option value="A" <?= old('kategori_biaya') == 'A' ? 'selected' : '' ?>>Kategori A</option>
                        <option value="B" <?= old('kategori_biaya') == 'B' ? 'selected' : '' ?>>Kategori B</option>
                        <option value="C" <?= old('kategori_biaya') == 'C' ? 'selected' : '' ?>>Kategori C</option>
                        <option value="D" <?= old('kategori_biaya') == 'D' ? 'selected' : '' ?>>Kategori D</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="penanda_tangan" class="required block font-medium text-gray-700 dark:text-neutral-300">
                        PPK Penanda Tangan
                    </label>
                    <select name="penanda_tangan" id="penanda_tangan"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>

                        <?php foreach ($userttd as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= (old('penanda_tangan') == $item['id']) ? 'selected' : '' ?>>
                                <?= $item['nama'] ?> - <?= $item['jabatan'] ?>
                            </option>
                        <?php endforeach; ?>

                        <?php foreach ($penanda_tangan as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= (old('penanda_tangan') == $item['id']) ? 'selected' : '' ?>>
                                <?= $item['nama'] ?> - <?= $item['jabatan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-span-1">
                    <label for="kepala_balai" class="required block font-medium text-gray-700 dark:text-neutral-300">Kepala Balai</label>
                    <select name="kepala_balai" id="kepala_balai"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                        <?php foreach ($kepala_balai as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['nama'] ?> - <?= $item['jabatan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="ttd_tanggal" class="required block text-gray-700 dark:text-neutral-300">Tanggal Tanda Tangan</label>
                    <input value="<?= old('ttd_tanggal', $surat['ttd_tanggal']) ?>" type="date" name="ttd_tanggal" id="ttd_tanggal" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-gray-600 dark:text-neutral-300 p-3 h-12" required>
                    <span id="error-message-end" class="text-red-500 mt-2 hidden"></span>
                </div>


                <!-- Button -->
                <div class="col-span-2 flex justify-start space-x-2 justify-center">
                    <a href="/admin/dokumen" class="inline-flex items-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-gray-500 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                        Kembali
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuBerakhirInput = document.getElementById('waktu_berakhir');
        const tglSinggahInputs = document.querySelectorAll('input[name="tempat_singgah[tanggal][]"]');
        const ttdPKK = document.getElementById('ttd_tanggal');
        const container = document.getElementById('opsi-tambahan-container');
        const data = <?= json_encode($surat); ?>;
        const biaya = data.biaya;

        if (biaya && biaya.trim() !== "") {
            setCheckedRadio('show');
            container.classList.remove('hidden');
        } else {
            setCheckedRadio('hide');
            container.classList.add('hidden');
        }

        function setCheckedRadio(value) {
            const radios = document.querySelectorAll('input[name="opsi_tambahan"]');
            radios.forEach(radio => {
                radio.checked = radio.value === value; // Set checked berdasarkan nilai
            });
        }

        function toggleOpsiTambahan(show) {
            if (show) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        function updateDateConstraints() {
            const waktuMulai = waktuMulaiInput.value;

            // Set min pada input waktu mulai agar tidak bisa memilih hari kemarin
            waktuMulaiInput.setAttribute('min', waktuMulai);

            if (waktuMulai) {
                waktuBerakhirInput.setAttribute('min', waktuMulai);
                tglSinggahInputs.forEach(input => input.setAttribute('min', waktuMulai));
                ttdPKK.setAttribute('min', waktuMulai);
            } else {
                waktuBerakhirInput.removeAttribute('min');
                tglSinggahInputs.forEach(input => input.removeAttribute('min'));
                ttdPKK.removeAttribute('min');
            }
        }

        function validateDates() {
            const waktuMulai = waktuMulaiInput.value;
            const waktuBerakhir = waktuBerakhirInput.value;

            if (waktuMulai && waktuBerakhir && waktuBerakhir < waktuMulai) {
                alert("Waktu Berakhir tidak boleh lebih awal dari Waktu Mulai!");
                waktuBerakhirInput.value = "";
            }

            tglSinggahInputs.forEach(input => {
                if (waktuMulai && input.value && input.value < waktuMulai) {
                    alert("Tanggal Singgah tidak boleh sebelum Waktu Mulai!");
                    input.value = "";
                }
            });

            if (waktuMulai && ttdPKK.value && ttdPKK.value < waktuMulai) {
                alert("Tanggal PKK tidak boleh sebelum Waktu Mulai!");
                ttdPKK.value = "";
            }
        }

        // Set batas tanggal saat halaman dimuat
        updateDateConstraints();

        // Event listener untuk setiap perubahan tanggal
        waktuMulaiInput.addEventListener('change', () => {
            updateDateConstraints();
            validateDates();
        });

        waktuBerakhirInput.addEventListener('change', validateDates);

        tglSinggahInputs.forEach(input => {
            input.addEventListener('change', validateDates);
        });

        ttdPKK.addEventListener('change', validateDates);
    });

    // Fungsi untuk menambahkan input tempat singgah baru
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

    function toggleOpsiTambahan(isVisible) {
        const container = document.getElementById('opsi-tambahan-container');
        if (isVisible) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    // Fungsi untuk menghapus input tempat singgah
    function hapusTempatSinggah(btn) {
        btn.closest('.flex').remove(); // Fungsi untuk menghapus input tempat singgah
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

    // Logika dropdown untuk memilih dasar hukum
    (function() {
        const selectedDasar = new Set();
        const dropdownDasar = document.getElementById('list-dasar');
        const dasarInput = document.getElementById('selected_dasar');
        const dasarContainer = document.getElementById('selected-dasar-container');
        const dasarHiddenInput = document.getElementById('selected_dasar_input');

        let dasar = <?= json_encode($dasar); ?>;
        let idsurat = <?= json_encode($dasarSuratData); ?>;

        // Inisialisasi selectedDasar dengan data dari backend
        idsurat.forEach(dasar => {
            selectedDasar.add(String(dasar.id));
            addSelectedDasar(dasar);
        });

        function renderDropdown(showDasar) {
            dropdownDasar.innerHTML = showDasar.map(list => {
                const isSelected = selectedDasar.has(String(list.id));
                return `
                <div class="dasar-option p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer ${isSelected ? 'bg-gray-200 dark:bg-neutral-800 text-gray-400' : ''}"
                    data-id="${list.id}"
                    data-undang="${list.undang.replace(/\n/g, '')}">
                    <div class="font-medium">
                        ${list.undang} ${isSelected ? `<span class="text-green-600 dark:text-green-400">(Sudah dipilih)</span>` : ''}
                    </div>
                </div>
            `;
            }).join('');

            document.querySelectorAll('.dasar-option').forEach(option => {
                option.addEventListener('click', function() {
                    const dasarId = String(this.dataset.id);
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
            selectedDasar.add(String(dasar.id));

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
            selectedDasar.delete(String(dasarId));
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

    // Logika dropdown untuk memilih pengguna
    (function() {
        const selectedUsers = new Set();
        const dropdownList = document.getElementById('dropdown-list');
        const userInput = document.getElementById('selected_user');
        const usersContainer = document.getElementById('selected-users-container');
        const usersHiddenInput = document.getElementById('selected_users_input');

        let users = <?= json_encode($users); ?>;
        let iduser = <?= json_encode($petugas); ?>;

        // Inisialisasi selectedUsers dengan data dari backend
        iduser.forEach(user => {
            selectedUsers.add(String(user.id));
            addSelectedUser(user); // Perbaikan pemanggilan fungsi
        });

        function renderDropdown(filteredUsers) {
            dropdownList.innerHTML = filteredUsers.map(user => {
                const isSelected = selectedUsers.has(String(user.id)); // Perbaikan perbandingan ID
                return `
            <div class="user-option p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer ${isSelected ? 'bg-gray-200 dark:bg-neutral-800 text-gray-400' : ''}"
                data-id="${user.id}"
                data-nama="${user.nama}"
                data-nip="${user.nip}"
                data-jabatan="${user.jabatan}"
                data-pangkat="${user.pangkat}">
                <div class="font-medium">
                    ${user.nama}
                </div>
                <div class="text-gray-600 dark:text-gray-400">
                    NIP: ${user.nip} | ${user.jabatan} ${isSelected ? `<span class="text-green-600 dark:text-green-400">(Sudah dipilih)</span>` : ''}
                </div>
            </div>
        `;
            }).join('');

            document.querySelectorAll('.user-option').forEach(option => {
                option.addEventListener('click', function() {
                    const userId = String(this.dataset.id);
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

        // Fungsi untuk menambahkan pengguna ke daftar yang dipilih
        function addSelectedUser(user) {
            selectedUsers.add(String(user.id));

            const userElement = document.createElement('div');
            userElement.className = 'flex items-center justify-between p-3 mb-2 bg-gray-100 dark:bg-neutral-700 rounded-md';
            userElement.innerHTML = `
            <div>
                <div class="font-medium">${user.nama}</div>
                <div class="text-gray-600 dark:text-gray-300">
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

        // Fungsi untuk menghapus pengguna dari daftar yang dipilih
        window.removeUser = function(userId, button) {
            selectedUsers.delete(String(userId));
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
</script>


<?= $this->endSection(); ?>