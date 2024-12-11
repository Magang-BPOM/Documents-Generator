<?php

namespace App\Controllers;

use App\Models\PembabananAnggaranModel;
use App\Models\Surat as Surat;
use App\Models\User as User;
use App\Models\Dasar as Dasar;
use App\Models\DasarSurat as DasarSurat;
use App\Models\SuratUser as SuratUser;
use Dompdf\Dompdf;
use Dompdf\Options;
use \ConvertApi\ConvertApi;
use DateTime;
use IntlDateFormatter;

class DokumenController extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $dasarModel;
    protected $dasarSurat;
    protected $SuratUserModel;

    protected $pembebanan_anggaran;

    public function __construct()
    {
        $this->suratModel = new Surat();
        $this->userModel = new User();
        $this->SuratUserModel = new SuratUser();
        $this->dasarModel = new Dasar();
        $this->dasarSurat = new DasarSurat();
        $this->pembebanan_anggaran = new PembabananAnggaranModel();
        $secretKey = 'secret_O8ZWpkGWj365X5w1';
        ConvertApi::setApiCredentials($secretKey);
    }

    public function index()
    {
        $role = session()->get('role');

        $suratUser = new SuratUser();
        $dataAdmin['surat_user'] = $suratUser->surat();
        $data['surat_user'] = $suratUser->suratbyUser();

        if ($role == 'admin') {
            return view('pages/admin/dokumen/index', $dataAdmin);
        } else {
            return view('pages/user/dokumen/index', $data);
        }
    }


    public function create()
    {
        $role = session()->get('role');
        $penanda_tangan = $this->userModel
            ->where('is_penanda_tangan', 1)
            ->where('role !=', 'admin')
            ->findAll();


        $data = [
            'users' => $this->userModel
                ->where('role !=', 'admin')
                ->findAll(),

            'dasar' => $this->dasarModel->findAll(),
            'pembebanan_anggaran' => $this->pembebanan_anggaran->findAll(),
            'penanda_tangan' => $penanda_tangan,
        ];

        if ($role == 'admin') {
            return view('pages/admin/dokumen/create', $data);
        } else {
            return view('pages/user/dokumen/create', $data);
        }
    }

    public function delete()
    {
        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];

        if (empty($selectedIds)) {
            return $this->response->setJSON(['error' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus']);
    }


    public function store()
    {
        $pembuatId = session()->get('user_id');
        $validationRules = [
            'nomor_surat' => 'required',
            'menimbang' => 'required',
            'selected_dasar' => 'required',
            'sebagai' => 'required',
            'waktu_mulai' => 'required|valid_date',
            'waktu_berakhir' => 'required|valid_date',
            'tujuan' => 'required',
            'kota_tujuan' => 'required',
            'biaya' => 'permit_empty|string',
            'kategori_biaya' => 'required',
            'id_pembebanan_anggaran' => 'required|integer',
            'penanda_tangan' => 'required',
            'ttd_tanggal' => 'required|valid_date',
            'selected_user' => 'required',
        ];

        if ($this->request->getPost('opsi_tambahan') === 'show') {
            $validationRules['untuk'] = 'required';
        }

        if (!$this->validate($validationRules)) {

            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon periksa input Anda.');
        }

        $waktuMulai = $this->request->getPost('waktu_mulai');
        $waktuBerakhir = $this->request->getPost('waktu_berakhir');
        $ttdTanggal = $this->request->getPost('ttd_tanggal');
        $nomorSurat = $this->request->getPost('nomor_surat');


        $suratModel = new Surat();
        if ($suratModel->where('nomor_surat', $nomorSurat)->first()) {
            return redirect()->back()->withInput()->with('error', 'Nomor surat sudah digunakan. Mohon gunakan nomor lain.');
        }

        $dayOfWeek = date('w', strtotime($ttdTanggal));

        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return redirect()->back()->withInput()->with('error', 'Tanggal Tanda Tangan tidak boleh pada hari Sabtu atau Minggu.');
        }

        if (new DateTime($waktuMulai) < new DateTime('today')) {
            return redirect()->back()->withInput()->with('error', 'Waktu pelaksanaan dimulai tidak boleh sebelum hari ini.');
        }

        if (new DateTime($waktuBerakhir) < new DateTime($waktuMulai)) {
            return redirect()->back()->withInput()->with('error', 'Waktu pelaksanaan berakhir tidak boleh sebelum waktu mulai.');
        }

        if ($this->isHoliday($waktuMulai) || $this->isHoliday($waktuBerakhir)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal Tanda Tangan tidak dapat dipilih karena merupakan hari libur.');
        }

        try {
            $dataSurat = [
                'nomor_surat' => $nomorSurat,
                'menimbang' => $this->request->getPost('menimbang'),
                'sebagai' => $this->request->getPost('sebagai'),
                'waktu_mulai' => $waktuMulai,
                'waktu_berakhir' => $waktuBerakhir,
                'tujuan' => $this->request->getPost('tujuan'),
                'kota_tujuan' => $this->request->getPost('kota_tujuan'),
                'biaya' => $this->request->getPost('biaya'),
                'kategori_biaya' => $this->request->getPost('kategori_biaya'),
                'id_pembebanan_anggaran' => $this->request->getPost('id_pembebanan_anggaran'),
                'ttd_tanggal' => $ttdTanggal,
                'id_penanda_tangan' => $this->request->getPost('penanda_tangan'),
            ];

            $suratModel = new Surat();
            $suratModel->insert($dataSurat);
            $suratId = $suratModel->getInsertID();


            $suratUserModel = new SuratUser();
            $userIds = explode(',', $this->request->getPost('selected_user'));
            foreach ($userIds as $userId) {
                if (!empty($userId)) {
                    $suratUserModel->insert([
                        'surat_id' => $suratId,
                        'user_id' => $userId,
                        'id_created' => $pembuatId,
                    ]);
                }
            }

            $dasarSuratModel = new DasarSurat();
            $selectDasar = explode(',', $this->request->getPost('selected_dasar'));
            foreach ($selectDasar as $dasar) {
                if (!empty($dasar)) {
                    $dasarSuratModel->insert([
                        'id_surat' => $suratId,
                        'id_dasar' => $dasar,
                    ]);
                }
            }


            $this->generate($suratId);


            $userRole = session()->get('role');
            if ($userRole === 'pegawai') {
                return redirect()->to('/dokumen');
            } elseif ($userRole === 'admin') {
                return redirect()->to('/admin/dokumen');
            } else {
                return redirect()->to('/');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan surat: ' . $e->getMessage());
        }
    }


    public function generate($suratId)
    {
        helper('url');
        $suratModel = new Surat();
        $surat = $suratModel->find($suratId);
        $userModel = new User();

        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }

        $suratUserModel = new SuratUser();
        $users = $suratUserModel
            ->select('user.*')
            ->join('user', 'user.id = surat_user.user_id')
            ->where('surat_user.surat_id', $suratId)
            ->findAll();

        log_message('debug', 'Users count: ' . count($users));
        if (!empty($users)) {
            log_message('debug', 'First user data: ' . print_r($users[0], true));
        } else {
            log_message('error', "No users found for surat ID $suratId.");
        }

        $DasarSuratModel = new DasarSurat();
        $listdasar = $DasarSuratModel
            ->select('dasar.*')
            ->join('dasar', 'dasar.id = dasarsurat.id_dasar')
            ->where('dasarsurat.id_surat', $suratId)
            ->findAll();


        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
        if (!$penandaTangan) {
            log_message('error', "Penanda tangan for ID {$surat['id_penanda_tangan']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penanda tangan not found.");
        }
        $data = [
            'surat' => $surat,
            'users' => $users,
            'dasar' => $listdasar,
            'penanda_tangan' => $penandaTangan,
            'header_image' => $this->convertImageToBase64('header.jpg'),
            'footer_image' => $this->convertImageToBase64('end.jpg'),
        ];


        $html = view('components/pdf_template', $data);


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $outputDir = WRITEPATH . "pdfs";
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPath = $outputDir . "/Surat-Tugas-$suratId.pdf";
        file_put_contents($outputPath, $dompdf->output());

        $dompdf->stream("Surat-Tugas-$suratId.pdf", ["Attachment" => false]);
    }


    public function generateSPD($suratId)
    {
        helper('url');

        $suratModel = new Surat();
        $pembebananAnggaranModel = new PembabananAnggaranModel();
        $userModel = new User();

        $surat = $suratModel->find($suratId);
        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }

        $suratUserModel = new SuratUser();
        $users = $suratUserModel
            ->select('user.*')
            ->join('user', 'user.id = surat_user.user_id')
            ->where('surat_user.surat_id', $suratId)
            ->findAll();

        if (empty($users)) {
            log_message('error', "No users found for surat ID $suratId.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No users found for this surat.");
        }

        $pembebananAnggaran = $pembebananAnggaranModel->getById($surat['id_pembebanan_anggaran']);
        if (!$pembebananAnggaran) {
            log_message('error', "Pembebanan anggaran for ID {$surat['id_pembebanan_anggaran']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Pembebanan anggaran not found.");
        }

        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
        if (!$penandaTangan) {
            log_message('error', "Penanda tangan for ID {$surat['id_penanda_tangan']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penanda tangan not found.");
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $outputDir = WRITEPATH . "pdfs";
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPath = $outputDir . "/Surat-Perjalanan-Tugas-$suratId.pdf";

        $combinedHtml = '';
        $totalUsers = count($users);
        $currentUserIndex = 0;

        foreach ($users as $user) {
            $currentUserIndex++;

            $data = [
                'surat' => $surat,
                'user' => $user,
                'pembebanan_anggaran' => $pembebananAnggaran,
                'penanda_tangan' => $penandaTangan,
            ];

            $html = view('components/template_SPD', $data);

            if ($currentUserIndex < $totalUsers) {
                $combinedHtml .= '<div style="page-break-after: always;">' . $html . '</div>';
            } else {
                $combinedHtml .= $html;
            }
        }

        $dompdf->loadHtml($combinedHtml);
        $dompdf->setPaper('F4', 'landscape');
        $dompdf->render();

        file_put_contents($outputPath, $dompdf->output());
        $dompdf->stream("Surat-Perjalanan-Tugas-$suratId.pdf", ["Attachment" => false]);
    }



    public function generateDocx($suratId)
    {
        helper('url');
        $suratModel = new Surat();
        $surat = $suratModel->find($suratId);
        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }

        $suratUserModel = new SuratUser();

        $users = $suratUserModel
            ->select('user.*')
            ->join('user', 'user.id = surat_user.user_id')
            ->where('surat_user.surat_id', $suratId)
            ->findAll();

        $DasarSuratModel = new DasarSurat();
        $listdasar = $DasarSuratModel
            ->select('dasar.*')
            ->join('dasar', 'dasar.id = dasarsurat.id_dasar')
            ->where('dasarsurat.id_surat', $suratId)
            ->findAll();

        $userModel = new User();
        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
        if (!$penandaTangan) {
            log_message('error', "Penanda tangan for ID {$surat['id_penanda_tangan']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penanda tangan not found.");
        }


        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $phpWord->setDefaultFontName('Bookman Old Style');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft' => 1440,
            'marginRight' => 1440,
            'marginTop' => 3000,
            'marginBottom' => 0,
            'pageSizeW' => 11906,
            'pageSizeH' => 18700,
            'differentFirstPageHeaderFooter' => true,

        ]);

        // Header
        $header = $section->addHeader();
        $header->firstPage();
        $header->addImage(FCPATH . 'header.jpg', [
            'width' => 560,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'marginLeft' => -280,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);

        $table = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMargin' => 20,
            'width' => 1000,
        ]);


        $table->addRow();
        $titleCell = $table->addCell(10000, ['gridSpan' => 3]);
        $titleCell->addText('SURAT TUGAS', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);


        $table->addRow();
        $numberCell = $table->addCell(10000, ['gridSpan' => 3]);
        $numberCell->addText('NOMOR: ' . ($surat['nomor_surat'] ?? ''), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $table->addRow();
        $table->addCell(10000, ['gridSpan' => 3])->addTextBreak();

        $table->addRow();
        $table->addCell(2000)->addText('Menimbang');
        $table->addCell(200)->addText(':', []);
        $table->addCell(7800)->addText('Bahwa dalam rangka melaksanakan kebijakan pengawasan di bidang obat dan makanan.', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]); // Column 3

        $table->addRow();
        $table->addCell(2000)->addText('Dasar');
        $table->addCell(200)->addText(':', []);
        $dasarCell = $table->addCell(7800);
        if (!empty($listdasar)) {
            $i = 1;
            foreach ($listdasar as $dasar) {
                $dasarCell->addText("{$i}. {$dasar['undang']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
                $i++;
            }
        } else {
            $dasarCell->addText('Tidak ada dasar yang ditemukan.', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        }

        $table->addRow();
        $memberiTugasCell = $table->addCell(10000, ['gridSpan' => 3]);
        $memberiTugasCell->addText('Memberi Tugas', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $table->addRow();
        $table->addCell(2000)->addText('Kepada');
        $table->addCell(200)->addText(':', []);
        $kepadaCell = $table->addCell(7800);
        $i = 1;
        foreach ($users as $user) {
            $kepadaCell->addText("{$i}. Nama: {$user['nama']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $kepadaCell->addText("   NIP: {$user['nip']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $kepadaCell->addText("   Pangkat/Gol: {$user['pangkat']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $kepadaCell->addText("   Jabatan: {$user['jabatan']}", [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $i++;
        }

        function formatTanggalRentang($mulai, $berakhir)
        {
            $formatterHari = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'EEEE');
            $formatterBulan = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'MMMM');

            $hariMulai = ucfirst($formatterHari->format(strtotime($mulai)));
            $hariBerakhir = ucfirst($formatterHari->format(strtotime($berakhir)));

            $tanggalMulai = date('d', strtotime($mulai));
            $tanggalBerakhir = date('d', strtotime($berakhir));

            $bulanMulai = ucfirst($formatterBulan->format(strtotime($mulai)));
            $bulanBerakhir = ucfirst($formatterBulan->format(strtotime($berakhir)));

            $tahunMulai = date('Y', strtotime($mulai));
            $tahunBerakhir = date('Y', strtotime($berakhir));

            if ($bulanMulai === $bulanBerakhir && $tahunMulai === $tahunBerakhir) {
                return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} - {$tanggalBerakhir} {$bulanMulai} {$tahunMulai}";
            }

            if ($tahunMulai === $tahunBerakhir) {
                return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} {$bulanMulai} - {$tanggalBerakhir} {$bulanBerakhir} {$tahunMulai}";
            }

            return "{$hariMulai} - {$hariBerakhir}, {$tanggalMulai} {$bulanMulai} {$tahunMulai} - {$tanggalBerakhir} {$bulanBerakhir} {$tahunBerakhir}";
        }


        $waktuMulai = $surat['waktu_mulai'] ?? '';
        $waktuBerakhir = $surat['waktu_berakhir'] ?? '';

        $waktuRentang = formatTanggalRentang($waktuMulai, $waktuBerakhir);

        $table->addRow();
        $table->addCell(2000)->addText('Untuk');
        $table->addCell(200)->addText(':', []);
        $untukCell = $table->addCell(7800);
        $untukCell->addText('1. Sebagai: ' . ($surat['sebagai'] ?? ''), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        $untukCell->addText(
            "2. Waktu: {$waktuRentang}",
            [],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
        );
        $untukCell->addText('3. Tujuan: ' . ($surat['tujuan'] ?? ''), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

        $table->addRow();
        $signatureCell = $table->addCell(10000, ['gridSpan' => 3, 'alignment' => 'right']);
        $signatureCell->addTextBreak();


        $signatureTable = $section->addTable([
            'alignment' => 'right',
        ]);

        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, [
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $signatureCell->addText(
            'Surabaya, ' . date('d F Y', strtotime($surat['created_at'] ?? date('Y-m-d'))),
            ['size' => 12],
            ['alignment' => 'right']
        );

        $signatureTable->addRow();
        $positionCell = $signatureTable->addCell(10000, [
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $positionCell->addText(
            $penandaTangan['jabatan'] ?? '',
            ['size' => 12],
            ['alignment' => 'right']
        );

        $signatureTable->addRow();
        $emptyCell = $signatureTable->addCell(10000, [
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $emptyCell->addTextBreak(3);

        $signatureTable->addRow();
        $nameCell = $signatureTable->addCell(10000, [
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $nameCell->addText(
            $penandaTangan['nama'] ?? '',
            ['size' => 12],
            ['alignment' => 'right']
        );


        $section->addText(
            'Petugas tidak diperkenankan menerima gratifikasi dalam bentuk apapun.',
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                'borderSize' => 1

            ]
        );



        $footer = $section->addFooter();
        $footer->firstPage();
        $footer->addImage(FCPATH . 'end.jpg', [
            'width' => 600,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'marginLeft' => -75,
            'marginTop' => -130,
            'wrappingStyle' => 'behind'
        ]);

        // Save the document
        $fileName = "Surat-Tugas-$suratId.docx";
        $tempFile = WRITEPATH . $fileName;

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return $this->response->download($tempFile, null)->setFileName($fileName);
    }

    private function convertImageToBase64($imagePath)
    {

        $fullPath = FCPATH . $imagePath;
        if (!file_exists($fullPath)) {
            log_message('error', "Image not found: $fullPath");
            return null;
        }

        $imageData = file_get_contents($fullPath);


        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }

    private function isHoliday($selectedDate)
    {
        $response = file_get_contents('https://api-harilibur.netlify.app/api');
        $holidays = json_decode($response, true);

        $holidayDates = array_column($holidays, 'holiday_date');

        return in_array($selectedDate, $holidayDates);
    }

    public function arsip_index()
    {
        $role = session()->get('role');
        $suratUser = new SuratUser();

        $data['arsip_surat'] = $suratUser->suratArsip();

        if ($role === 'admin') {
            return view('pages/admin/dokumen/archive', $data);
        } else if ($role === 'pegawai') {
            return view('pages/user/dokumen/archive', $data);
        } else {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }
    }


    public function bulkArsip()
    {
        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];

        if (empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->set(['status' => 'arsip'])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diarsipkan']);
    }

    public function unarchive()
    {
        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];

        if (empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->set(['status' => 'aktif'])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dipulihkan']);
    }
}
