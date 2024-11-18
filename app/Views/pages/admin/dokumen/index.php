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

<div class="max-w-full mx-auto p-6 sm:px-6 lg:px-6">
    <div class="bg-white dark:bg-neutral-900 shadow-lg rounded-xl p-6">
        <!-- <div class="flex items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Semua Surat</h2>
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
                            <a href="/admin/dokumen/create" id="btnModalAddData" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Buat Surat
                            </a>

                            <button data-trashed="true" data-url="<?= base_url('dokumen/bulkArsip') ?>" type="button" class="bulkArsipBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Arsip
                            </button>

                            <button data-trashed="false" data-url="<?= base_url('dokumen/delete') ?>" type="button" class="bulkDeleteBtn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Hapus
                            </button>

                            <div class="hs-dropdown [--placement:bottom-right] relative inline-block">

                                <button id="export_drobdown_table" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                    </svg>
                                    Export
                                </button>
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-48 z-10 bg-white shadow-md rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="export_drobdown_table">

                                    <div class="py-2 first:pt-0 last:pb-0">
                                        <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400 dark:text-neutral-600">
                                            Export as
                                        </span>
                                        <button type="button" id="print_btn_table" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300" href="#">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                            </svg>
                                            Print
                                        </button>
                                        <button type="button" id="excel_btn_table" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300" href="#">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z" />
                                            </svg>
                                            Excel
                                        </button>
                                        <button type="button" id="pdf_btn_table" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300" href="#">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                            </svg>
                                            PDF
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Tugas </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanda Tangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan TTD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-neutral-900 dark:divide-neutral-800">
                    <?php $no = 1; ?>
                    <?php foreach ($surat_user as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><input type="checkbox" name="selected[]" value="<?= $item['id'] ?>" class="rowCheckbox form-checkbox"></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['nomor_surat']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['kepada']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['waktu']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['penanda_tangan']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= esc($item['jabatan_ttd']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?= base_url('/admin/dokumen/pdf/' . $item['id']) ?>" class="text-blue-600 hover:underline">PDF</a>
                                <a href="<?= base_url('/admin/dokumen/word/' . $item['id']) ?>" class="text-blue-600 hover:underline">Word</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

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
                    kepada: '<?= esc($item['kepada']) ?>',
                    waktu: '<?= esc($item['waktu']) ?>',
                    penanda_tangan: '<?= esc($item['penanda_tangan']) ?>',
                    jabatan_ttd: '<?= esc($item['jabatan_ttd']) ?>'
                },
            <?php endforeach; ?>
        ];


        function updateTable() {
            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;
            const paginatedData = data.slice(startIdx, endIdx);

            tableBody.innerHTML = '';

            paginatedData.forEach((item, index) => {
                const row = document.createElement('tr');
                const rowNumber = startIdx + index + 1; // Nomor baris
                row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap"><input type="checkbox" name="selected[]" value="${item.id}" class="rowCheckbox form-checkbox"></td>
            <td class="px-6 py-4 whitespace-nowrap">${rowNumber}</td> <!-- Tambahkan nomor -->
            <td class="px-6 py-4 whitespace-nowrap">${item.nomor_surat}</td>
            <td class="px-6 py-4 whitespace-nowrap">${item.kepada}</td>
            <td class="px-6 py-4 whitespace-nowrap">${item.waktu}</td>
            <td class="px-6 py-4 whitespace-nowrap">${item.penanda_tangan}</td>
            <td class="px-6 py-4 whitespace-nowrap">${item.jabatan_ttd}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <a href="<?= base_url('/admin/dokumen/pdf/' . $item['id']) ?>" class="text-blue-600 hover:underline">PDF</a>
                <a href="<?= base_url('/admin/dokumen/word/' . $item['id']) ?>" class="text-blue-600 hover:underline">Word</a>
            </td>
        `;
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
</script>

<?= $this->endSection(); ?>