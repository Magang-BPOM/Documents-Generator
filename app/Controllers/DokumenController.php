<?php

namespace App\Controllers;

use App\Models\Surat as Surat;
use App\Models\User as User;
use App\Models\SuratUser as SuratUser;
use Dompdf\Dompdf;
use Dompdf\Options;

class DokumenController extends BaseController
{
    protected $suratModel;
    protected $userModel;

    protected $SuratUserModel;

    public function __construct()
    {
        $this->suratModel = new Surat();
        $this->userModel = new User();
        $this->SuratUserModel = new SuratUser();
    }


    public function index()
    {
        helper('url');
        $user['users'] = $this->userModel->findAll();
        return view('pages/dokumen', $user);
    }

    public function surat()
    {
        $suratModel = new Surat();
        
        // Mengambil semua data surat
        $data['surat'] = $suratModel->findAll();
        return view('pages/surat', $data);
    }

    public function store()
    {

        $validation = $this->validate([
            'nomor_surat' => 'required',
            'menimbang' => 'required',
            'dasar' => 'required',
            'untuk'=>'required',
            'ttd_tanggal' => 'required',
            'penanda_tangan' => 'required',
            'jabatan_ttd'=>'required',
            'selected_user' => 'required'
        ]);


        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $ttdTanggal = $this->request->getPost('ttd_tanggal');
        
        if ($this->isHoliday($ttdTanggal)) {
            $this->validator->setError('ttd_tanggal', 'Tanggal tidak dapat dipilih karena merupakan hari libur.');
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $dataSurat = [
            'nomor_surat' => $this->request->getPost('nomor_surat'),
            'menimbang' => $this->request->getPost('menimbang'),
            'dasar' => implode("; ", array_filter(array_map('trim', explode("\n", $this->request->getPost('dasar'))))), 
            'untuk' => implode("; ", array_filter(array_map('trim', explode("\n", $this->request->getPost('untuk'))))),
           'ttd_tanggal' => $ttdTanggal,
            'penanda_tangan' => $this->request->getPost('penanda_tangan'),
            'jabatan_ttd' => $this->request->getPost('jabatan_ttd'),

        ];
        
        $suratModel = new Surat();
        $suratModel->insert($dataSurat);
        $suratId = $suratModel->getInsertID();

        $userIds = explode(',', $this->request->getPost('selected_user'));
        log_message('debug', 'User IDs: ' . implode(',', $userIds));


        foreach ($userIds as $userId) {
            if (!empty($userId)) {
                $suratUserModel = new SuratUser();
                $suratUserModel->insert([
                    'surat_id' => $suratId,
                    'user_id' => $userId
                ]);
            }
        }

        return $this->generate($suratId);
    }

    public function generate($suratId)
    {
        helper('url');
        log_message('debug', "Generating PDF for suratId: $suratId");
    
        // Fetching surat details
        $suratModel = new Surat();
        $surat = $suratModel->find($suratId);
        if (!$surat) {
            log_message('error', "Surat with ID $suratId not found.");
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat with ID $suratId not found.");
        }
    
        // Fetching users
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
    
        $data = [
            'surat' => $surat,
            'users' => $users,
            'header_image' => $this->convertImageToBase64('header.jpg'),
            'footer_image' => $this->convertImageToBase64('end.jpg'),
        ];


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
    
}
