<?= $this->extend('pages/admin/layout/main'); ?>

<?= $this->section('title'); ?>
Tambah Pengguna
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
Pengguna
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb_current'); ?>
Tambah Pengguna Baru
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Tambah Pengguna Baru</h2>
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
        <form method="POST" action="<?= base_url('user/store') ?>" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="col-span-1">
                    <label for="nama" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Nama</label>
                    <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" value="<?= old('nama') ?>" required>
                </div>

                <div class="col-span-1">
                    <label for="nip" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">NIP</label>
                    <input type="text" name="nip" id="nip" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" value="<?= old('nip') ?>" required>
                    <p id="nip-error" class="text-red-600 text-sm mt-1 hidden">NIP harus terdiri dari minimal 8 karakter!</p>
                </div>


                <div class="col-span-1">
                    <label for="jabatan" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Jabatan</label>
                    <input type="text" name="jabatan" placeholder="Pengawas Farmasi dan Makanan Ahli Madya" id="jabatan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" value="<?= old('jabatan') ?>" required>
                </div>

                <div class="col-span-1">
                    <label for="pangkat" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Pangkat/Gol</label>
                    <input type="text" placeholder="Pembina/Gol.IV-a" name="pangkat" id="pangkat" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" value="<?= old('pangkat') ?>">
                </div>

                <div class="col-span-1">
                    <label for="foto_profil" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Foto Profil</label>
                    <input type="file" name="foto_profil" id="foto_profil" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12">
                </div>

                <div class="col-span-1">
                    <label for="password" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                    <p id="password-error" class="text-red-600 text-sm mt-1 hidden">Password harus terdiri dari minimal 6 karakter!</p>
                </div>


                <div class="col-span-1">
                    <label for="role" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                        <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="pegawai" <?= old('role') == 'pegawai' ? 'selected' : '' ?>>Pegawai</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="is_penanda_tangan" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Akses TTD PPK</label>
                    <select name="is_penanda_tangan" id="is_penanda_tangan" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                        <option value="0" <?= old('is_penanda_tangan') === '0' ? 'selected' : '' ?>>Tidak</option>
                        <option value="1" <?= old('is_penanda_tangan') === '1' ? 'selected' : '' ?>>Ya</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="kepala_balai" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Kepala balai</label>
                    <select name="kepala_balai" id="kepala_balai" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                        <option value="0" <?= old('kepala_balai') === '0' ? 'selected' : '' ?>>Tidak</option>
                        <option value="1" <?= old('kepala_balai') === '1' ? 'selected' : '' ?>>Kepala Balai</option>
                        <option value="1" <?= old('kepala_balai') === '2' ? 'selected' : '' ?>>Plt.Kepala Balai</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="is_bendahara" class="required block text-sm font-medium text-gray-700 dark:text-neutral-300">Akses Bendahara?</label>
                    <select name="is_bendahara" id="is_bendahara" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 p-3 h-12" required>
                        <option value="0" <?= old('is_bendahara') === '0' ? 'selected' : '' ?>>Tidak</option>
                        <option value="1" <?= old('is_bendahara') === '1' ? 'selected' : '' ?>>Ya</option>
                    </select>
                </div>

            </div>

            <!-- Button -->
            <div class="col-span-2 flex justify-start space-x-2 mt-6">

                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-neutral-900">
                    Tambah Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nipInput = document.getElementById("nip");
        const passwordInput = document.getElementById("password");
        const form = document.querySelector("form");

        // Validasi saat pengguna mengetik NIP
        nipInput.addEventListener("input", function() {
            const nipError = document.getElementById("nip-error");
            if (nipInput.value.length < 8) {
                nipError.textContent = "NIP harus terdiri dari minimal 8 karakter!";
                nipError.style.display = "block";
            } else {
                nipError.style.display = "none";
            }
        });

        // Validasi saat pengguna mengetik password
        passwordInput.addEventListener("input", function() {
            const passwordError = document.getElementById("password-error");
            if (passwordInput.value.length < 6) {
                passwordError.textContent = "Password harus terdiri dari minimal 6 karakter!";
                passwordError.style.display = "block";
            } else {
                passwordError.style.display = "none";
            }
        });

        // Validasi saat submit form
        form.addEventListener("submit", function(event) {
            let errors = [];

            if (nipInput.value.length < 8) {
                errors.push('NIP harus terdiri dari minimal 8 karakter!');
            }

            if (passwordInput.value.length < 6) {
                errors.push('Password harus terdiri dari minimal 6 karakter!');
            }

            if (errors.length > 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    html: errors.join('<br>'),
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>

<?= $this->endSection(); ?>