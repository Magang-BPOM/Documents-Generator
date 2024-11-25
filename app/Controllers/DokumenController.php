<?php

namespace App\Controllers;

use App\Models\Surat as Surat;
use App\Models\User as User;
use App\Models\Dasar as Dasar;
use App\Models\DasarSurat as DasarSurat;
use App\Models\SuratUser as SuratUser;
use Dompdf\Dompdf;
use Dompdf\Options;
use \ConvertApi\ConvertApi;

class DokumenController extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $dasarModel;
    protected $dasarSurat;
    protected $SuratUserModel;

    private function convertPdfToWord($pdfPath, $wordPath)
    {
        // Pastikan file PDF ada
        if (!file_exists($pdfPath)) {
            throw new \RuntimeException("PDF file not found: $pdfPath");
        }

        // Buat direktori tujuan jika belum ada
        $outputDir = dirname($wordPath);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // Jalankan perintah LibreOffice untuk konversi
        $command = "libreoffice --headless --convert-to docx --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($pdfPath);
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            throw new \RuntimeException("LibreOffice conversion failed: " . implode("\n", $output));
        }

        // Pastikan file hasil ada
        if (!file_exists($wordPath)) {
            throw new \RuntimeException("Word file not created: $wordPath");
        }

        return $wordPath;
    }


    public function __construct()
    {
        $this->suratModel = new Surat();
        $this->userModel = new User();
        $this->SuratUserModel = new SuratUser();
        $this->dasarModel = new Dasar();
        $this->dasarSurat = new DasarSurat();
        $secretKey = 'secret_O8ZWpkGWj365X5w1';
        ConvertApi::setApiCredentials($secretKey);
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
        $validationRules = [
            'nomor_surat' => 'required',
            'menimbang' => 'required',
            'selected_dasar' => 'required',
            'sebagai' => 'required',
            'waktu_mulai' => 'required|valid_date',
            'waktu_berakhir' => 'required|valid_date',
            'tujuan' => 'required',
            'biaya' => 'permit_empty|string',
            'penanda_tangan' => 'required',
            'jabatan_ttd' => 'required',
            'selected_user' => 'required',
        ];

        if ($this->request->getPost('opsi_tambahan') === 'show') {
            $validationRules['untuk'] = 'required';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ttdTanggal = $this->request->getPost('waktu_mulai');

        if ($this->isHoliday($ttdTanggal)) {
            return redirect()->back()->withInput()->with('errors', [
                'waktu' => 'Tanggal tidak dapat dipilih karena merupakan hari libur.',
            ]);
        }

        $dataSurat = [
            'nomor_surat' => $this->request->getPost('nomor_surat'),
            'menimbang' => $this->request->getPost('menimbang'),
            'sebagai' => $this->request->getPost('sebagai'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_berakhir' => $this->request->getPost('waktu_berakhir'),
            'tujuan' => $this->request->getPost('tujuan'),
            'biaya' => $this->request->getPost('biaya'),
            'penanda_tangan' => $this->request->getPost('penanda_tangan'),
            'jabatan_ttd' => $this->request->getPost('jabatan_ttd'),
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


        $html = view('components/pdf_template', $data);


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'portrait');
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
        ];


        $html = view('components/template_SPD', $data);


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();

        $outputDir = WRITEPATH . "pdfs";
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPath = $outputDir . "/Surat-Perjalanan-Tugas-$suratId.pdf";
        file_put_contents($outputPath, $dompdf->output());

        $dompdf->stream("Surat-Tugas-$suratId.pdf", ["Attachment" => false]);
    }

    public function convertPdfToWordWithConvertApi($pdfPath)
    {
        try {
            $result = ConvertApi::convert('docx', [
                'File' => $pdfPath,
            ], 'pdf');

            $outputDir = WRITEPATH . 'word/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            $savedFiles = $result->saveFiles($outputDir);

            $wordFilePath = $savedFiles[0];

            return $this->response->download($wordFilePath, null)->setFileName(basename($wordFilePath));
        } catch (\Exception $e) {
            log_message('error', 'Error converting PDF to Word with ConvertAPI: ' . $e->getMessage());
            throw new \RuntimeException('Failed to convert PDF to Word.');
        }
    }

    public function generateWord($suratId)
    {
        $pdfPath = WRITEPATH . "pdfs/Surat-Tugas-$suratId.pdf";
        $wordPath = WRITEPATH . "docs/Surat-Tugas-$suratId.docx";

        if (!file_exists($pdfPath)) {
            throw new \RuntimeException("PDF file not found: $pdfPath");
        }

        try {
            $convertedWord = $this->convertPdfToWord($pdfPath, $wordPath);
            return $this->response->download($convertedWord, null)->setFileName("Surat-Tugas-$suratId.docx");
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Konversi gagal.']);
        }
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

        if (empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $this->suratModel->whereIn('id', $selectedIds)->set(['status' => 'arsip'])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diarsipkan']);
    }
}
