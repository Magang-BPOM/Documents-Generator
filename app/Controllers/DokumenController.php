<?php

namespace App\Controllers;

use App\Models\PembabananAnggaranModel;
use App\Models\Surat as Surat;
use App\Models\SuratSinggah;
use App\Models\User as User;
use App\Models\Dasar as Dasar;
use App\Models\DasarSurat as DasarSurat;
use App\Models\RincianBiayaModel;
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
            $validationRules['biaya'] = 'required|string';
        }
        log_message('debug', 'Data biaya: ' . $this->request->getPost('biaya'));

        if (!$this->validate($validationRules)) {

            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon periksa input Anda.');
        }

        $waktuMulai = $this->request->getPost('waktu_mulai');
        $waktuBerakhir = $this->request->getPost('waktu_berakhir');
        $ttdTanggal = $this->request->getPost('ttd_tanggal');
        $nomorSurat = $this->request->getPost('nomor_surat');
        $biaya = $this->request->getPost('biaya') ?? null;

        $isNew = 1;
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

        if ($this->isHoliday($ttdTanggal)) {
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
                'biaya' => $biaya,
                'ttd_tanggal' => $ttdTanggal,
                'id_penanda_tangan' => $this->request->getPost('penanda_tangan'),
                'is_new' => $isNew
            ];


            $suratModel = new Surat();
            $suratModel->insert($dataSurat);
            $suratId = $suratModel->getInsertID();

            $tempatSinggahModel = new SuratSinggah();
            $tempatSinggahData = $this->request->getPost('tempat_singgah');
            $tempatSinggahBatch = [];

            foreach ($tempatSinggahData['nama_tempat'] as $index => $namaTempat) {
                $tempatSinggahBatch[] = [
                    'surat_id' => $suratId,
                    'berangkat_dari' => $tempatSinggahData['berangkat_dari'][$index] ?? null,
                    'ke' => $tempatSinggahData['ke'][$index] ?? null,
                    'nama_tempat' => $namaTempat,
                    'tanggal' => $tempatSinggahData['tanggal'][$index] ?? null,
                ];
            }

            $tempatSinggahModel->insertBatch($tempatSinggahBatch);

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
        $userId = session()->get('user_id');


        $suratModel = new Surat();
        $pembebananAnggaranModel = new PembabananAnggaranModel();
        $userModel = new User();
        $suratUser = new SuratUser();

        $selected = $suratUser->select('id')->where('user_id', $userId)->first();
        // Mengatur header untuk respons JSON
        // header('Content-Type: application/json');
        // echo json_encode($selected);
        // exit;

        $data = ['is_read' => 1];

        $suratUser->update($selected, $data);

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

        $suratSinggahModel = new SuratSinggah();
        $tempatSinggah = $suratSinggahModel->where('surat_id', $surat['id'])->findAll();

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
                'tempatSinggah' => $tempatSinggah
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
        $titleCell->addText('SURAT TUGAS', ['size' => 12], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);


        $table->addRow();
        $numberCell = $table->addCell(10000, ['gridSpan' => 3]);
        $numberCell->addText('NOMOR: ' . ($surat['nomor_surat'] ?? ''), [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $table->addRow();
        $table->addCell(10000, ['gridSpan' => 3])->addTextBreak();

        $table->addRow();
        $table->addCell(2000)->addText('Menimbang');
        $table->addCell(200)->addText(':', []);
        $table->addCell(7800)->addText(($surat['menimbang'] ?? ''), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]); // Column 3

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
            $dasarCell->addText('Tidak ada dasar yang ditemukan.', [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
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
            $kepadaCell->addText("{$i}. Nama: {$user['nama']}", [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $kepadaCell->addText("   NIP: {$user['nip']}", [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
            $kepadaCell->addText("   Pangkat/Gol: {$user['pangkat']}", [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
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
        $untukCell->addText('1. Sebagai: ' . ($surat['sebagai'] ?? ''), [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        $untukCell->addText(
            "2. Waktu: {$waktuRentang}",
            [],
            ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
        );
        $untukCell->addText('3. Tujuan: ' . ($surat['tujuan'] ?? ''), [], ['spaceAfter' => 0,'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);

        $table->addRow();
        $signatureCell = $table->addCell(10000, ['spaceAfter' => 0,'gridSpan' => 3, 'alignment' => 'right']);
        $signatureCell->addTextBreak();


        $signatureTable = $section->addTable(['spaceAfter' => 0,
            'alignment' => 'right',
        ]);

        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(10000, ['spaceAfter' => 0,
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $signatureCell->addText(
            'Surabaya, ' . date('d F Y', strtotime($surat['created_at'] ?? date('Y-m-d'))),
            ['size' => 12],
            ['spaceAfter' => 0,'alignment' => 'right']
        );

        $signatureTable->addRow();
        $positionCell = $signatureTable->addCell(10000, ['spaceAfter' => 0,
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $positionCell->addText(
            $penandaTangan['jabatan'] ?? '',
            ['size' => 12],
            ['spaceAfter' => 0,'alignment' => 'right']
        );

        $signatureTable->addRow();
        $emptyCell = $signatureTable->addCell(10000, ['spaceAfter' => 0,
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $emptyCell->addTextBreak(3);

        $signatureTable->addRow();
        $nameCell = $signatureTable->addCell(10000, ['spaceAfter' => 0,
            'gridSpan' => 3,
            'alignment' => 'right',
        ]);
        $nameCell->addText(
            $penandaTangan['nama'] ?? '',
            ['size' => 12],
            ['spaceAfter' => 0,'alignment' => 'right']
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


    public function createRBPD($suratId, $userId)
    {


        $suratModel = new Surat();
        $userModel = new User();
        $suratUserModel = new SuratUser();
        $userRole = session()->get('role');

        $surat = $suratModel->find($suratId);

        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }

        $penerima = $suratUserModel->where('surat_id', $suratId)
            ->where('user_id', $userId)
            ->join('user', 'surat_user.user_id = user.id')
            ->select('user.id, user.nama, user.nip')
            ->first();

        if (!$penerima) {
            return redirect()->back()->with('error', 'Penerima tidak valid.');
        }

        $bendahara = $userModel->where('role', 'admin')->findAll();

        $penandaTangan = $userModel->select('id, nama, nip,jabatan')
            ->where('id', $surat['id_penanda_tangan'])
            ->first();

        if (!$penandaTangan) {
            return redirect()->back()->with('error', 'Penanda tangan tidak valid.');
        }

        if ($userRole === 'admin') {
            return view('pages/admin/dokumen/create_rbpd', [
                'surat' => $surat,
                'penerima' => $penerima,
                'bendahara' => $bendahara,
                'penanda_tangan' => $penandaTangan,
            ]);
        } elseif ($userRole === 'pegawai') {
            return view('pages/user/dokumen/create_rbpd', [
                'surat' => $surat,
                'penerima' => $penerima,
                'bendahara' => $bendahara,
                'penanda_tangan' => $penandaTangan,
            ]);
        } else {
            return redirect()->back()->with('error', 'Role tidak dikenali.');
        }
    }


    public function detailRBPD($suratId)
    {
        $suratModel = new Surat();
        $suratUserModel = new SuratUser();
        $userRole = session()->get('role');

        $surat = $suratModel->find($suratId);
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'User tidak valid.');
        }

        $updated = $suratUserModel
            ->where('surat_id', $suratId)
            ->where('user_id', $userId)
            ->set(['is_read' => 1])
            ->update();


        if (!$updated) {
            log_message('error', "Failed to update is_read for surat_id: $suratId and user_id: $userId");
            return redirect()->back()->with('error', 'Gagal memperbarui status baca.');
        } else {
            log_message('info', "Successfully updated is_read for surat_id: $suratId and user_id: $userId");
        }
        $penerima = $suratUserModel
            ->select('surat_user.id as surat_user_id, user.id as user_id, user.nama, user.nip, COUNT(rincian_biaya.id) as jumlah_rincian, MAX(rincian_biaya.id) as rbpd_created')
            ->join('user', 'surat_user.user_id = user.id')
            ->join('rincian_biaya', 'rincian_biaya.surat_user_id = surat_user.id', 'left')
            ->where('surat_user.surat_id', $suratId)
            ->groupBy('surat_user.id')
            ->get()
            ->getResultArray();


        if ($userRole === 'admin') {
            return view('pages/admin/dokumen/detail_rbpd', [
                'surat' => $surat,
                'penerima' => $penerima,
            ]);
        } elseif ($userRole === 'pegawai') {
            return view('pages/user/dokumen/detail_rbpd', [
                'surat' => $surat,
                'penerima' => $penerima,
            ]);
        } else {
            return redirect()->back()->with('error', 'Role tidak dikenali.');
        }
    }



    public function storeRBPD()
    {
        $rbpdModel = new RincianBiayaModel();
        $suratUserModel = new SuratUser();
        $userRole = session()->get('role');

        $validation = $this->validate([
            'nomor_spd' => 'required',
            'tanggal' => 'required|valid_date',
            'perincian_biaya' => 'required',
            'jumlah' => 'required',
            'bendahara_id' => 'required|integer',
            'penerima_id' => 'required|integer',
            'id_penanda_tangan' => 'required|integer',
            'surat_id' => 'required|integer',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $suratId = $this->request->getPost('surat_id');
        $userId = $this->request->getPost('penerima_id');
        $tanggal = $this->request->getPost('tanggal');
        $bendaharaId = $this->request->getPost('bendahara_id');
        $idPenandaTangan = $this->request->getPost('id_penanda_tangan');
        $perincianBiaya = $this->request->getPost('perincian_biaya');
        $jumlah = $this->request->getPost('jumlah');
        $keterangan = $this->request->getPost('keterangan');

        $suratUser = $suratUserModel->where('surat_id', $suratId)
            ->where('user_id', $userId)
            ->first();

        if (!$suratUser) {
            return redirect()->back()->withInput()->with('error', 'Data surat_user tidak ditemukan.');
        }

        $suratUserId = $suratUser['id'];

        try {
            foreach ($perincianBiaya as $index => $biaya) {
                $existingData = $rbpdModel->where('surat_id', $suratId)
                    ->where('surat_user_id', $suratUserId)
                    ->where('perincian_biaya', $biaya)
                    ->first();

                if ($existingData) {
                    continue;
                }

                $data = [
                    'surat_id' => $suratId,
                    'surat_user_id' => $suratUserId,
                    'tanggal' => $tanggal,
                    'perincian_biaya' => $biaya,
                    'jumlah' => $jumlah[$index],
                    'keterangan' => $keterangan[$index] ?? null,
                    'bendahara_id' => $bendaharaId,
                    'id_penanda_tangan' => $idPenandaTangan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if (!$rbpdModel->insert($data)) {
                    throw new \Exception('Gagal menyimpan rincian biaya.');
                }
            }

            if ($userRole === 'admin') {
                return redirect()->back()->with('message', 'RBPD berhasil disimpan.');
            } else if ($userRole === 'pegawai') {
                return redirect()->back()->with('message', 'RBPD berhasil disimpan.');
            } else {
                return redirect()->back()->with('error', 'Role tidak dikenali.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function generateRBPD($suratId, $penerimaId)
    {
        $suratModel = new Surat();
        $rincianBiayaModel = new RincianBiayaModel();
        $userModel = new User();
        $suratUserModel = new SuratUser();

        $surat = $suratModel->find($suratId);
        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }

        $penerima = $userModel->find($penerimaId);
        if (!$penerima) {
            log_message('error', "Penerima with ID $penerimaId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penerima with ID $penerimaId not found.");
        }

        // Fetch surat_user_id
        $suratUser = $suratUserModel
            ->where('surat_id', $suratId)
            ->where('user_id', $penerimaId)
            ->first();

        if (!$suratUser) {
            log_message('error', "Surat user with surat ID $suratId and penerima ID $penerimaId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat user not found.");
        }

        $suratUserId = $suratUser['id'];

        $rincianBiaya = $rincianBiayaModel
            ->where('surat_id', $suratId)
            ->where('surat_user_id', $suratUserId)
            ->findAll();

        if (empty($rincianBiaya)) {
            log_message('error', "No rincian biaya found for surat ID $suratId and surat_user ID $suratUserId.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No rincian biaya found for this RBPD.");
        }

        // Fetch penanda tangan data
        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
        if (!$penandaTangan) {
            log_message('error', "Penanda tangan for ID {$surat['id_penanda_tangan']} not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Penanda tangan not found.");
        }

        $data = [
            'surat' => $surat,
            'penerima' => $penerima,
            'rincian_biaya' => $rincianBiaya,
            'penanda_tangan' => $penandaTangan,
            'header_image' => $this->convertImageToBase64('header.jpg'),
            'footer_image' => $this->convertImageToBase64('end.jpg'),
        ];

        $html = view('components/pdf_template_rbpd', $data);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();

        $dompdf->stream("RBPD-$suratId-$penerimaId.pdf", ["Attachment" => false]);
    }


    public function editRBPD($suratId, $userId)
    {
        $rbpdModel = new RincianBiayaModel();
        $suratModel = new Surat();
        $userModel = new User();
        $suratUserModel = new SuratUser();
    
        // Cari surat_user_id yang terkait dengan user_id
        $suratUser = $suratUserModel->where('surat_id', $suratId)
            ->where('user_id', $userId)
            ->first();
    
        if (!$suratUser) {
            return redirect()->back()->with('error', 'Data surat_user tidak ditemukan.');
        }
    
        $suratUserId = $suratUser['id'];
 
        $rbpd = $rbpdModel->where('surat_id', $suratId)
            ->where('surat_user_id', $suratUserId)
            ->findAll();
    
        if (empty($rbpd)) {
            return redirect()->back()->with('error', 'Data RBPD tidak ditemukan.');
        }

        $surat = $suratModel->find($suratId);
    
        if (!$surat) {
            return redirect()->back()->with('error', 'Data surat tidak ditemukan.');
        }
    
        $penerima = $userModel->find($userId);
    
        if (!$penerima) {
            return redirect()->back()->with('error', 'Data penerima tidak ditemukan.');
        }
   
        $bendaharaId = $rbpd[0]['bendahara_id'] ?? null;
        $bendahara = $bendaharaId ? $userModel->find($bendaharaId) : null;

        $penandaTangan = $userModel->find($surat['id_penanda_tangan']);
    
        return view('pages/user/dokumen/edit_rbpd', [
            'rbpd' => $rbpd,
            'surat' => $surat,
            'penerima' => $penerima,
            'bendahara' => $bendahara,
            'penandaTangan'=>$penandaTangan
        ]);
    }
    

    public function updateRBPD()
    {
        $request = service('request');
        $rbpdModel = new RincianBiayaModel();
    
        $data = $request->getPost();

        // print_r($data);
        // die();
    
        // Validasi input wajib
        if (
            empty($data['perincian_biaya']) || empty($data['jumlah']) ||
            empty($data['bendahara_id']) || empty($data['id_penanda_tangan'])
        ) {
            return redirect()->back()->with('error', 'Harap isi semua data yang diperlukan.');
        }
    
        // Validasi jumlah data
        if (count($data['perincian_biaya']) !== count($data['jumlah'])) {
            return redirect()->back()->with('error', 'Jumlah rincian biaya tidak sesuai.');
        }
    
        $rbpdModel->where('surat_id', $data['surat_id'])
            ->where('surat_user_id', $data['surat_user_id'])
            ->delete();
    
        // Simpan data baru
        foreach ($data['perincian_biaya'] as $index => $perincian) {
            $rbpdModel->insert([
                'surat_id' => $data['surat_id'],
                'surat_user_id' => $data['surat_user_id'],
                'tanggal' => date('Y-m-d'),
                'perincian_biaya' => $perincian,
                'jumlah' => $data['jumlah'][$index],
                'keterangan' => $data['keterangan'][$index] ?? null,
                'bendahara_id' => $data['bendahara_id'],
                'id_penanda_tangan' => $data['id_penanda_tangan'],
            ]);
        }
    
        return redirect()->to('/dokumen/detailRBPD/' . $data['surat_id'])->with('message', 'RBPD berhasil diperbarui.');
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


    public function updateIsRead($suratId, $userId)
    {
        $suratUser = new SuratUser();



        $updated = $suratUser
            ->where('surat_id', $suratId)
            ->where('user_id', $userId)
            ->set(['is_read' => 1])
            ->update();


        if ($updated) {
            log_message('info', "Successfully updated is_read for surat_id: $suratId, user_id: $userId");
            return ['success' => true, 'message' => 'Status updated successfully'];
        } else {
            log_message('error', "Failed to update is_read for surat_id: $suratId, user_id: $userId");
            return ['success' => false, 'message' => 'Failed to update status'];
        }
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
