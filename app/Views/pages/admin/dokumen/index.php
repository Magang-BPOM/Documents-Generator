<?= $this->extend('pages/admin/layout/main'); ?>

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

<div class="relative max-w-full min-h-screen mx-auto p-6 sm:px-6 lg:px-6">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
     
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
                            <a href="/dokumen/create" id="btnModalAddData" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Buat Surat
                            </a>

                            <button data-trashed="true" data-url="<?= base_url('admin/dokumen/bulkArsip') ?>" type="button" class="bulkArsipBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Arsip
                            </button>

                            <button data-trashed="false" data-url="<?= base_url('admin/dokumen/delete') ?>" type="button" class="bulkDeleteBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Hapus
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

        <!-- Tabel utnuk menampilkan list surat -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                    <?php $no = 1; ?>
                    <?php foreach ($surat_user as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected[]" value="<?= $item['id'] ?>" class="rowCheckbox form-checkbox">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= esc($item['nomor_surat']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (is_array($item['kepada']) && isset($item['kepada'][0]['nama'])): ?>
                                    <?= esc($item['kepada'][0]['nama']); ?> (<?= esc($item['kepada'][0]['nip']); ?>)
                                <?php else: ?>
                                    Tidak ada data
                                <?php endif; ?>

                                <?php if (is_array($item['kepada']) && count($item['kepada']) > 1): ?>
                                    <button
                                        onclick="showUsersModal(<?= htmlspecialchars(json_encode($item['kepada'])) ?>)"
                                        class="text-blue-600 hover:underline ml-2">Selengkapnya</button>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['waktu_mulai']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['penanda_tangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['jabatan_penanda_tangan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Menampilkan semua daftar petugas -->
        <div id="modal" class="absolute inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
            <div class="bg-white dark:bg-neutral-800 rounded-lg p-6 w-full max-w-lg shadow-lg relative">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-100">Daftar petugas</h2>
                    <button onclick="closeModal()" class="text-gray-600 dark:text-neutral-400 hover:text-gray-800 dark:hover:text-neutral-200">
                        ✕
                    </button>
                </div>
                <div id="modal-content" class="mt-4 text-gray-700 dark:text-neutral-300">
                    <!-- Content goes here -->
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center">
            <!-- Filter for items per page -->
            <div class="flex items-center gap-2">
                <label for="itemsPerPage" class="text-sm text-gray-600 dark:text-neutral-400">Show</label>
                <select id="itemsPerPage" class="py-1 px-2 border border-gray-200 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-neutral-400">items per page</span>
            </div>

            <div class="flex items-center gap-2">
                <button id="prevPage" class="py-1 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" disabled>
                    Prev
                </button>

                <span id="currentPage" class="text-sm text-gray-600 dark:text-neutral-400">1</span>

                <button id="nextPage" class="py-1 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700">
                    Next
                </button>
            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script>

    // Fungsi untuk menampilkan modal semua petugas jika lebih dari 1 petugas
     function showUsersModal(users) {
        const modalContent = document.getElementById('modal-content');
        const modal = document.getElementById('modal');

        modalContent.innerHTML = `
        <ul class="space-y-2">
            ${users.map(user => {
                return `
                    <li class="flex justify-between items-center bg-gray-50 dark:bg-neutral-700 p-3 rounded-md shadow-sm">
                        <span class="text-sm font-medium">${user.nama}</span>
                        <span class="text-sm text-gray-500 dark:text-neutral-400">${user.nip}</span>
                    </li>
                `;
            }).join('')}
        </ul>
    `;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }


    // Logika untuk pagination
    document.addEventListener('DOMContentLoaded', function() {
        const itemsPerPageSelect = document.getElementById('itemsPerPage');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const currentPageDisplay = document.getElementById('currentPage');
        const tableBody = document.querySelector('tbody');
        let currentPage = 1;
        let itemsPerPage = parseInt(itemsPerPageSelect.value);

        const data = [
            <?php foreach ($surat_user as $item): ?> {
                    id: <?= $item['id'] ?>,
                    nomor_surat: '<?= esc($item['nomor_surat']) ?>',
                    kepada: <?= json_encode($item['kepada']) ?>,
                    waktu_mulai: '<?= esc($item['waktu_mulai']) ?>',
                    penanda_tangan: '<?= esc($item['penanda_tangan']) ?>',
                    jabatan_penanda_tangan: '<?= esc($item['jabatan_penanda_tangan']) ?>'
                },
            <?php endforeach; ?>
        ];
        function updateTable() {
            const userId = <?= json_encode(session()->get('user_id')); ?>;
            console.log("User ID:", userId);

            if (!userId) {
                console.error("User ID is not defined or null.");
                return;
            }

            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;
            const paginatedData = data.slice(startIdx, endIdx);

            console.log("Paginated Data:", paginatedData);

            tableBody.innerHTML = '';

            paginatedData.forEach((item, index) => {
                console.log("Kepada Data Lengkap:", item.kepada);

                const rowNumber = startIdx + index + 1;
                const row = document.createElement('tr');

                const statusCell = document.createElement('td');
                statusCell.className = "px-6 py-4 whitespace-nowrap";
                const userId = <?= json_encode(session()->get('user_id')); ?>; 

                const currentUser = item.kepada.find(user => parseInt(user.user_id) === parseInt(userId));
                const isUnreadForCurrentUser = currentUser && parseInt(currentUser.is_read || 0) === 0;

                statusCell.innerHTML = isUnreadForCurrentUser
                    ? `<span class="text-xs font-semibold text-white bg-green-500 px-2 py-1 rounded-full">New</span>`
                    : `<span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded-full">Old</span>`;
                row.appendChild(statusCell);



                const checkboxCell = document.createElement('td');
                checkboxCell.className = "px-6 py-4 whitespace-nowrap";
                checkboxCell.innerHTML = `<input type="checkbox" name="selected[]" value="${item.id}" class="rowCheckbox form-checkbox">`;
                row.appendChild(checkboxCell);

                const numberCell = document.createElement('td');
                numberCell.className = "px-6 py-4 whitespace-nowrap";
                numberCell.textContent = rowNumber;
                row.appendChild(numberCell);

                const suratCell = document.createElement('td');
                suratCell.className = "px-6 py-4 whitespace-nowrap";
                suratCell.textContent = item.nomor_surat;
                row.appendChild(suratCell);

                const kepadaCell = document.createElement('td');
                kepadaCell.className = "px-6 py-4 whitespace-nowrap";
                kepadaCell.innerHTML = `
                    ${item.kepada.length > 0 ? `${item.kepada[0].nama} | ${item.kepada[0].nip}` : 'Tidak ada data'}
                    ${item.kepada.length > 1 ? `<button onclick='showUsersModal(${JSON.stringify(item.kepada)})' class="text-blue-600 hover:underline">Selengkapnya</button>` : ''}
                `;
                row.appendChild(kepadaCell);

                const waktuCell = document.createElement('td');
                waktuCell.className = "px-6 py-4 whitespace-nowrap";
                waktuCell.textContent = item.waktu_mulai;
                row.appendChild(waktuCell);

                const penandaCell = document.createElement('td');
                penandaCell.className = "px-6 py-4 whitespace-nowrap";
                penandaCell.textContent = item.penanda_tangan;
                row.appendChild(penandaCell);

                const jabatanCell = document.createElement('td');
                jabatanCell.className = "px-6 py-4 whitespace-nowrap";
                jabatanCell.textContent = item.jabatan_penanda_tangan;
                row.appendChild(jabatanCell);

                const actionsCell = document.createElement('td');
                actionsCell.className = "px-6 py-4 whitespace-nowrap";
                actionsCell.innerHTML = `
                    <a href="dokumen/generateSPD/${item.id}" class="text-blue-600 hover:underline block">SPD</a>
                    <a href="dokumen/detailRBPD/${item.id}" class="text-blue-600 hover:underline block">RBPD</a>
                `;
                row.appendChild(actionsCell);

                tableBody.appendChild(row);
            });

            currentPageDisplay.textContent = currentPage;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage * itemsPerPage >= data.length;
        }


        itemsPerPageSelect.addEventListener('change', (e) => {
            itemsPerPage = parseInt(e.target.value);
            currentPage = 1;
            updateTable();
        });


        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage * itemsPerPage < data.length) {
                currentPage++;
                updateTable();
            }
        });

        updateTable();
    });


    document.addEventListener('DOMContentLoaded', () => {
        moment.locale('id');

        document.querySelectorAll('td[data-date]').forEach(td => {
            const dateValue = td.getAttribute('data-date');
            if (dateValue) {
                const formattedDate = moment(dateValue).format('D MMMM YYYY');
                td.textContent = formattedDate;
            }
        });
    });


    // Logika untuk pencarian surat
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

    // Chekckbox pilih semua surat
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Fungsi untuk meng arsipkan surat yang dipilih
    document.querySelector('.bulkArsipBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            swal("Peringatan", "Silakan pilih data yang ingin diarsipkan.", "warning");
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
                    swal("Sukses", "Data berhasil diarsipkan.", "success")
                    setTimeout(() => {
                        location.reload();
                    }, 2000)
                } else {
                    swal("Gagal", "Gagal mengarsipkan data.", "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Terjadi kesalahan saat mengarsipkan data.", "error");
            });
    });

    // Fungsi untuk Delete surat tugas yang dipilih
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


    // Event listener untuk menampilkan dropdown Export
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


    // Fungsi export surat ke word
    function exportWord() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);
        if (selectedIds.length === 0) {
            alert('Silakan pilih data yang ingin di export');
            return;
        }
        window.location.href = `/admin/dokumen/generate-word/${selectedIds}`;
    }

    
    // Fungsi export surat ke PDF
    function exportPDF() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);
        if (selectedIds.length === 0) {
            alert('Silakan pilih data yang ingin di export');
            return;
        }
        window.location.href = `/admin/dokumen/generate/${selectedIds}`;
    }
</script>

<?= $this->endSection(); ?>