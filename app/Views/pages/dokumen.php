<?= $this->extend('layout/main'); ?>

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
<div class="m-11 bg-white dark:bg-neutral-800 rounded-xl">
    <div class="m-7 ">
        <h1 class="text-3xl text-center p-10 font-bold">Formulir Pembuatan Surat</h1>
        <div class="flex p-10 gap-7">
            <div class="flex-1 w-12 text-xl">
                <div class="mb-4">
                    <label for="judul" class="block font-medium">Nama Surat:</label>
                    <select id="judul" name="judul" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" required>
                        <option value=""> Jenis Surat </option>
                        <option value="Surat Tugas">Surat Tugas</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nosurat" class="block text-lg font-medium">Nomor Surat:</label>
                    <input type="text" id="nosurat" name="nosurat" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" required oninput="nomorsurat()">
                    <div id="dropdown" class="bg-white dark:bg-gray-600 border border-gray-300 dark:border-black rounded mt-1 max-h-60 overflow-auto hidden">
                        <!-- Options will be dynamically populated here -->
                    </div>
                </div>
                <div class="mb-4">
                    <label for="menimbang" class="block font-medium">Menimbang:</label>
                    <textarea id="menimbang" name="menimbang" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" rows="4" required></textarea>
                </div>
            </div>
            <div class="flex-1 w-46 ">
                <form id="suratForm">
                    <div class="mb-4">
                        <label for="dasar" class="block text-lg font-medium">Dasar:</label>
                        <textarea id="dasar" name="dasar" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" rows="4" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="kepada" class="block text-lg font-medium">Kepada:</label>
                        <input type="text" id="kepada" name="kepada" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" required oninput="petugas()">
                        <div id="dropdown" class="bg-white dark:bg-gray-600 border border-gray-300 dark:border-black rounded mt-1 max-h-60 overflow-auto hidden">
                            <!-- Options will be dynamically populated here -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tombol untuk mencetak menjadi PDF -->
        <div class="">
            <label class="block text-lg font-medium">Untuk </label>
            <label class="block text-lg font-medium">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" class="w-full p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" rows="4" required></textarea>
            <label for="waktu" class="block mt-4 text-lg font-medium">Tanggal  : <input type="date" id="nosurat" name="nosurat" class="w-60 p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" required></label>
            <label for="waktu" class="block mt-4 text-lg font-medium">Tujuan : <input type="text" id="nosurat" name="nosurat" class="w-60 p-2 border border-gray-300 dark:border-black dark:bg-gray-600 dark:text-white rounded" required></label>
            <div class=" mt-6 mb-60 ">
                <button id="printPdf" class="bg-green-500 text-white p-2 rounded">Print PDF</button>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Buat Surat</button>
            </div>
        </div>


    </div>
</div>

<!-- Tambahkan jsPDF CDN untuk konversi PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    document.getElementById("waktu").addEventListener("input", function() {
        const inputDate = new Date(this.value);
        const day = inputDate.getDay();
        const holidays = [
            '2024-01-01',  // Contoh Hari Libur: Tahun Baru
            '2024-12-25',  // Contoh Hari Libur: Natal
            // Tambahkan hari libur lainnya dalam format 'YYYY-MM-DD'
        ];

        const formattedDate = inputDate.toISOString().split('T')[0];
        
        if (day === 0 || day === 6 || holidays.includes(formattedDate)) {
            alert("Tanggal yang Anda pilih adalah hari libur atau akhir pekan. Silakan pilih hari kerja lain.");
            this.value = "";
        }
    });
</script>

<script>
        document.getElementById('printPdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Ambil data dari form
            const judul = document.getElementById('judul').value;
            const nosurat = document.getElementById('nosurat').value;
            const menimbang = document.getElementById('menimbang').value;
            const dasar = document.getElementById('dasar').value;
            const kepada = document.getElementById('kepada').value;
            const deskripsi = document.getElementById('deskripsi').value;

            // URL gambar
            const header = '<?= base_url("assets/dokumen/header.png"); ?>';
            const end = '<?= base_url("assets/dokumen/end.png"); ?>';

            // Fungsi untuk mengonversi gambar ke base64
            const toDataURL = (url, callback) => {
                const xhr = new XMLHttpRequest();
                xhr.onload = () => {
                    const reader = new FileReader();
                    reader.onloadend = () => callback(reader.result);
                    reader.readAsDataURL(xhr.response);
                };
                xhr.open('GET', url);
                xhr.responseType = 'blob';
                xhr.send();
            };

            // Konversi gambar dan tambahkan ke PDF
            toDataURL(header, function(base64Img) {
                doc.addImage(base64Img, 'PNG', 10, 10, 190, 30); // Tambah gambar di PDF
                toDataURL(end, function(base64ImgEnd) {
                    doc.setFontSize(14);
                    doc.text("SURAT TUGAS", 87, 55); // Geser teks di bawah gambar 87 kiri kanan 
    
                    doc.setFontSize(12);
                    doc.text('NOMOR: ' + judul, 65, 60);
    
                    doc.setFontSize(12);
                    doc.text('Menimbang :', 33, 70);
                    doc.text('menimbang', 64, 70);
    
                    doc.text('Dasar            :', 33, 100);
                    doc.text("dasar", 64, 100);
    
                    doc.text('Kepada         :', 33, 150);
                    doc.text('kepada', 64, 150);
    
                    doc.text('Untuk            :', 33, 190);
                    doc.text('deskripsi', 64, 190);
    
                    doc.text('Agar yang bersangkutan melaksanakan tugas dengan baik dan penuh tanggung jawab.', 30, 230);
    
                    doc.addImage(base64ImgEnd, 'PNG', 0, 230, 210, 70); 
                    doc.text('Surabaya, 25 Oktober 2024', 135, 240);
                    doc.text('Plt. Kepala Balai Besar POM di Surabaya,', 120, 245);
                    doc.text('Surabaya, 25 Oktober 2024', 135, 270);
                    doc.addImage(base64Img, 'PNG', 10, 10, 190, 30);
    
                    // Simpan sebagai PDF
                    doc.save('surat.pdf');
                });
            });
        });
    </script>

<script>
    function petugas() {
        const input = document.getElementById("kepada").value;
        const dropdown = document.getElementById("dropdown");

        if (input.length > 1) {
            // Send a request to the backend to fetch matching names
            fetch(`/autocomplete/names?query=${input}`)
                .then(response => response.json())
                .then(data => {
                    // Clear the dropdown before adding new options
                    dropdown.innerHTML = '';
                    if (data.length > 0) {
                        dropdown.classList.remove("hidden");
                        data.forEach(name => {
                            const option = document.createElement("div");
                            option.className = "p-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700";
                            option.textContent = name;
                            option.onclick = () => {
                                document.getElementById("kepada").value = name;
                                dropdown.classList.add("hidden");
                            };
                            dropdown.appendChild(option);
                        });
                    } else {
                        dropdown.classList.add("hidden");
                    }
                });
        } else {
            dropdown.classList.add("hidden");
        }
    }
</script>
<?= $this->endSection(); ?>