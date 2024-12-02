<?= $this->extend('pages/user/layout/main'); ?>

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
        <!-- <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Arsip Surat</h2>
        </div> -->

        <div class=" py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
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

                    <div class="sm:col-span-2 md:grow">
                        <div class="flex justify-end gap-x-2">
                            <button data-trashed="false" data-url="<?= base_url('dokumen/unarchive') ?>" type="button" class="UnarchiveBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                              Pulihkan
                            </button>


                            <div class="relative inline-block text-left">
                                <button id="export_drobdown_table"
                                    type="button"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    Export
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden opacity-0 transition-transform transform scale-95 z-50"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="export_drobdown_table"
                                    id="dropdownMenu">
                                    <div class="py-2 w-full">
                                        <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400 w-full">
                                            Export as
                                        </span>
                                        <button type="button" onclick="exportPDF()" id="print_btn_table" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none">
                                            PDF
                                        </button>
                                        <button type="button" onclick="exportWord()" id="excel_btn_table" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none">
                                            Word
                                        </button>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="form-checkbox">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanda Tangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan TTD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-neutral-900 dark:divide-neutral-800">
                    <?php
                    $no = 1; 
                    foreach ($arsip_surat as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected[]" value="<?= $item['id'] ?>" class="rowCheckbox form-checkbox">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nomor_surat']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['kepada']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['waktu_mulai']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['penanda_tangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['jabatan_penanda_tangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?= base_url('dokumen/generateSPD/' . $item['id']) ?>"
                                    class="text-blue-600 hover:underline">
                                    Surat Perjalanan Dinas
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
        moment.locale('id');

        document.querySelectorAll('td[data-date]').forEach(td => {
            const dateValue = td.getAttribute('data-date');
            if (dateValue) {
                const formattedDate = moment(dateValue).format('D MMMM YYYY');
                td.textContent = formattedDate;
            }
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

    document.querySelector('.UnarchiveBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            swal("Peringatan", "Silakan pilih data yang ingin dipulihkan.", "warning");
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
                    swal("Sukses", "Data berhasil dipulihkan.", "success")
                    setTimeout(() => {
                        location.reload();
                    }, 2000)
                } else {
                    swal("Gagal", "Gagal memulihkan data.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Terjadi kesalahan saat memulihkan data.", "error");
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('export_drobdown_table');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', function(event) {
            event.stopPropagation();
            const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
            dropdownButton.setAttribute('aria-expanded', !isExpanded);
            dropdownMenu.classList.toggle('hidden');
            dropdownMenu.classList.toggle('opacity-0');
            dropdownMenu.classList.toggle('scale-95');
        });


        document.addEventListener('click', function() {
            dropdownButton.setAttribute('aria-expanded', false);
            dropdownMenu.classList.add('hidden');
            dropdownMenu.classList.add('opacity-0');
            dropdownMenu.classList.add('scale-95');
        });
    });

    function exportWord() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);
        if (selectedIds.length === 0) {
            alert('Silakan pilih data yang ingin di export');
            return;
        }
        window.location.href = `/dokumen/generate-word/${selectedIds}`;
    }

    function exportPDF() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);
        if (selectedIds.length === 0) {
            alert('Silakan pilih data yang ingin di export');
            return;
        }
        window.location.href = `/dokumen/generate/${selectedIds}`;
    }

 
</script>
    <?= $this->endSection(); ?>