<?php

namespace App\Controllers;

use App\Models\Surat as Surat;
use App\Models\User as User;
use App\Models\Dasar as Dasar;
use App\Models\DasarSurat as DasarSurat;
use App\Models\SuratUser as SuratUser;
use Dompdf\Dompdf;
use Dompdf\Options;

class DokumenController extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $dasarModel;
    protected $dasarSurat;
    protected $SuratUserModel;

    public function __construct()
    {
        $this->suratModel = new Surat();
        $this->userModel = new User();
        $this->SuratUserModel = new SuratUser();
        $this->dasarModel = new Dasar();
        $this->dasarModel = new DasarSurat();
    }

    public function index()
    {
        $role = session()->get('role');

        $suratUser = new SuratUser();
        $dataAdmin['surat_user'] = $suratUser->surat();



        if ($role == 'admin') {
            return view('pages/admin/dokumen/index', $dataAdmin);
        } else {
            $data['surat_user'] = $suratUser->suratbyUser();
            return view('pages/user/dokumen/index', $data);
        }
    }


    public function create()
    {
        $role = session()->get('role');

        $data = [
            'users' => $this->userModel->findAll(),
            'dasar' => $this->dasarModel->findAll(),
        ];
        // header('Content-Type: application/json');
        // echo json_encode($data);
        // exit;

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
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diarsipkan']);
    }


    public function store()
    {
        $pembuatId = session()->get('user_id');
        $opsiTambahan = $this->request->getPost('opsi_tambahan');

        $validation = $this->validate([
            'nomor_surat' => 'required',
            'menimbang' => 'required',
            'selected_dasar' => 'required',
            'sebagai' => 'required',
            'waktu' => 'required|valid_date',
            'tujuan' => 'required',
            'penanda_tangan' => 'required',
            'jabatan_ttd' => 'required',
            'selected_user' => 'required'
        ]);

        if ($this->request->getPost('opsi_tambahan') === 'show') {
            $validation = $this->validate(array_merge($validation, [
                'untuk' => 'required',
            ]));
        }

        // Jika validasi gagal
        if (!$validation) {
            // Ambil pesan error
            $errors = $this->validator->getErrors();

            // Tampilkan JSON
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'errors' => $errors]);
            exit;
        }


        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $ttdTanggal = $this->request->getPost('waktu');

        if ($this->isHoliday($ttdTanggal)) {
            $this->validator->setError('waktu', 'Tanggal tidak dapat dipilih karena merupakan hari libur.');
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataSurat = [
            'nomor_surat' => $this->request->getPost('nomor_surat'),
            'menimbang' => $this->request->getPost('menimbang'),
            'sebagai' => $this->request->getPost('sebagai'),
            // 'dasar' => implode("; ", array_filter(array_map('trim', explode("\n", $this->request->getPost('dasar'))))),
            'untuk' => implode("; ", array_filter(array_map('trim', explode("\n", $this->request->getPost('untuk'))))),
            'waktu' => $ttdTanggal,
            'tujuan' => $this->request->getPost('tujuan'),
            'penanda_tangan' => $this->request->getPost('penanda_tangan'),
            'jabatan_ttd' => $this->request->getPost('jabatan_ttd'),
        ];

        $suratModel = new Surat();
        $suratModel->insert($dataSurat);
        $suratId = $suratModel->getInsertID();


        $userIds = explode(',', $this->request->getPost('selected_user'));

        foreach ($userIds as $userId) {
            if (!empty($userId)) {
                $suratUserModel = new SuratUser();
                $suratUserModel->insert([
                    'surat_id' => $suratId,
                    'user_id' => $userId,
                    'id_created' => $pembuatId
                ]);
            }
        }


        // //insert dasar
        $selectDasar = explode(',', $this->request->getPost('selected_dasar'));
        // header('Content-Type: application/json');
        // echo json_encode($selectDasar);
        // exit;

        foreach ($selectDasar as $dasar) {
            if (!empty($dasar)) {
                $dasarsuratModel = new DasarSurat();
                $dasarsuratModel->insert([
                    'id_surat' => $suratId,
                    'id_dasar' => $dasar,
                ]);
            }
        }

        return $this->generate($suratId);
    }




    public function generate($suratId)
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

        $data = [
            'surat' => $surat,
            'users' => $users,
            'dasar' => $listdasar,
            'header_image' => $this->convertImageToBase64('header.jpg'),
            'footer_image' => $this->convertImageToBase64('end.jpg'),
        ];

        // header('Content-Type: application/json');
        // echo json_encode($data);
        // exit;


        $html = view('components/pdf_template', $data);
        log_message('debug', "Generated HTML for PDF: $html");

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("Surat-Tugas-$suratId.pdf", ["Attachment" => false]);
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

        function formatTGL($tanggal, $format = 'tanggal')
        {
            // Tentukan format berdasarkan pilihan
            $dateType = ($format === 'hari') ? \IntlDateFormatter::FULL : \IntlDateFormatter::LONG;

            $fmt = new \IntlDateFormatter(
                'id_ID', // Locale Indonesia
                $dateType, // Pilihan format (FULL untuk hari, LONG untuk tanggal)
                \IntlDateFormatter::NONE, // Tidak menggunakan format waktu
                'Asia/Jakarta', // Timezone
                \IntlDateFormatter::GREGORIAN // Kalender Gregorian
            );

            return $fmt->format(new \DateTime($tanggal));
        }

        // Buat Dokumen Word
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Menambahkan Halaman
        $section = $phpWord->addSection();

        // Menambahkan Header
        $header = $section->addHeader();
        $header->addImage(FCPATH . 'header.jpg', [
            'width' => 500,
            'height' => 95,
            'alignment' => 'left'
        ]);

        // Tambahkan Konten Surat
        $section->addTextBreak();

        $section->addText("Isi Surat:", ['size' => 12]);
        $section->addText($surat['nomor_surat'] ?? '', ['size' => 12]);
        $section->addTextBreak();

        $section->addText("Menimbang:", ['size' => 12]);
        $section->addText($surat['menimbang'] ?? '', ['size' => 12]);

        $section->addText("Dasar :");
        $no = 1;
        foreach ($listdasar as $dasar) {
            $section->addText(
                $no . ". " . $dasar['undang'],
                ['size' => 12]
            );
            $no++;
        }
        // header('Content-Type: application/json');
        // echo json_encode($users);
        // exit;
        $section->addText("Memberi Tugas", ['size' => 12, 'bold' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak();

        $section->addText("Kepada :", ['size' => 12]);

        if (count($users) > 2) {
            $section->addText("Nama-nama terlampir", ['size' => 12]);
        } else {
            foreach ($users as $user) {
                $section->addListItem("Nama: " . $user['nama'], 0, ['size' => 12]);
                $section->addListItem("NIP: " . $user['nip'], 0, ['size' => 12]);
                $section->addListItem("Pangkat/Gol: " . $user['pangkat'], 0, ['size' => 12]);
                $section->addListItem("Jabatan: " . $user['jabatan'], 0, ['size' => 12]);
                $section->addTextBreak();
            }
        }

        $section->addText("Untuk :", ['size' => 12, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addText($surat['sebagai'] ?? '', ['size' => 12]);
        $section->addText('Waktu  : ' . formatTGL($surat['waktu'] ?? '', 'hari'), ['size' => 12]);
        $section->addText('Tujuan : ' . ($surat['tujuan'] ?? ''), ['size' => 12]);
        if (!empty($surat['untuk'])) {
            $nod = 4;
            foreach ($listdasar as $dasar) {
                $section->addText(
                    $nod . ". " . $surat['untuk'],
                    ['size' => 12]
                );
                $nod++;
            }
        }

        // Tambahkan teks instruksi tugas
        $section->addText(
            "Agar yang bersangkutan melaksanakan tugas dengan baik dan penuh tanggung jawab.",
            ['size' => 12],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
        );

        // Tambahkan baris kosong untuk jarak
        $section->addTextBreak(1);

        // Tambahkan tanggal dan jabatan di sebelah kanan
        $section->addText(
            'Surabaya, ' . formatTGL($surat['created_at'], 'tanggal') . ',',
            ['size' => 12],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );

        $section->addText(
            $surat['jabatan_ttd'] ?? '',
            ['size' => 12],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );

        // Tambahkan ruang kosong untuk tanda tangan
        $section->addTextBreak(3);

        // Tambahkan nama penanda tangan
        $section->addText(
            $surat['penanda_tangan'] ?? '',
            ['size' => 12],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );

        // Menambahkan Footer
        $footer = $section->addFooter();
        $footer->addImage(FCPATH . 'end.jpg', ['width' => 500, 'height' => 150, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);

        // Simpan File DOCX
        $fileName = "Surat-Tugas-$suratId.docx";
        $tempFile = WRITEPATH . $fileName;

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Berikan File ke User
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

        $suratUser = new SuratUser();
        $data['arsip_surat'] = $suratUser->suratArsip();

        return view('pages/admin/dokumen/archive', $data);
    }


    public function bulkArsip()
    {
        $request = $this->request->getJSON();
        $selectedIds = $request->selectedIds ?? [];
        // header('Content-Type: application/json');
        // echo json_encode($request);
        // exit;

        if (empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->set(['status' => 'arsip'])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diarsipkan']);
    }
}
