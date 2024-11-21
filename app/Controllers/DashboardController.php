<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surat;
use App\Models\SuratUser;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Inisialisasi database
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        $suratModel = new Surat();

        $totalSurat = $suratModel->countAllResults();
        $suratAktif = $suratModel->where('status', 'aktif')->countAllResults();
        $suratArsip = $suratModel->where('status', 'arsip')->countAllResults();

        $currentMonth = date('m');
        $currentYear = date('Y');
        $suratBaruBulanIni = $suratModel->where('MONTH(created_at)', $currentMonth)
            ->where('YEAR(created_at)', $currentYear)
            ->countAllResults();

        $bulan = [];
        $jumlahSuratPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date('F', mktime(0, 0, 0, $i, 1));
            $jumlahSuratPerBulan[] = $suratModel->where('MONTH(created_at)', $i)
                ->where('YEAR(created_at)', $currentYear)
                ->countAllResults();
        }

        $data = [
            'total_surat' => $totalSurat,
            'surat_aktif' => $suratAktif,
            'surat_arsip' => $suratArsip,
            'surat_baru_bulan_ini' => $suratBaruBulanIni,
            'bulan' => $bulan,
            'jumlah_surat_per_bulan' => $jumlahSuratPerBulan,
        ];

        return view('pages/admin/dashboard', $data);
    }


    public function user()
    {
        $userId = session()->get('user_id');
        $userName = session()->get('nama');
        $suratModel = new Surat();
        $suratUserModel = new SuratUser();
    

        $suratUserIds = $this->db->table('surat_user')
            ->select('surat.id')
            ->join('surat', 'surat_user.surat_id = surat.id')
            ->groupStart()
                ->where('surat_user.user_id', $userId) 
                ->orWhere('surat_user.id_created', $userId) 
            ->groupEnd()
            ->groupBy('surat.id')
            ->get()
            ->getResultArray();
    
        if (empty($suratUserIds)) {
            $data = [
                'total_surat' => 0,
                'surat_aktif' => 0,
                'surat_arsip' => 0,
                'surat_baru_bulan_ini' => 0,
                'bulan' => [],
                'jumlah_surat_per_bulan' => []
            ];
            return view('pages/user/dashboard', $data);
        }
    
        $suratIds = array_column($suratUserIds, 'id');
    
        $totalSurat = count($suratIds);
    
        $suratAktif = $this->db->table('surat')
            ->whereIn('id', $suratIds)
            ->where('status', 'aktif')
            ->countAllResults();
    
        $suratArsip = $this->db->table('surat')
            ->whereIn('id', $suratIds)
            ->where('status', 'arsip')
            ->countAllResults();

        $currentMonth = date('m');
        $currentYear = date('Y');
        $suratBaruBulanIni = $this->db->table('surat')
            ->whereIn('id', $suratIds)
            ->where('MONTH(created_at)', $currentMonth)
            ->where('YEAR(created_at)', $currentYear)
            ->countAllResults();
   
        $bulan = [];
        $jumlahSuratPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date('F', mktime(0, 0, 0, $i, 1));
            $jumlahSuratPerBulan[] = $this->db->table('surat')
                ->whereIn('id', $suratIds)
                ->where('MONTH(created_at)', $i)
                ->where('YEAR(created_at)', $currentYear)
                ->countAllResults();
        }
    
        $data = [
            'total_surat' => $totalSurat,
            'surat_aktif' => $suratAktif,
            'surat_arsip' => $suratArsip,
            'surat_baru_bulan_ini' => $suratBaruBulanIni,
            'bulan' => $bulan,
            'jumlah_surat_per_bulan' => $jumlahSuratPerBulan,
        ];
    
        return view('pages/user/dashboard', $data);
    }
    
}
