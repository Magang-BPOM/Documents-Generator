<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surat;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $suratModel = new Surat();
    
        if (!$userId) {
            return redirect()->to('/login');
        }
    
    
        $totalSurat = $suratModel->where('pembuat_id', $userId)->countAllResults();
        $suratAktif = $suratModel->where('pembuat_id', $userId)
                                  ->where('status', 'aktif')
                                  ->countAllResults();
        $suratArsip = $suratModel->where('pembuat_id', $userId)
                                  ->where('status', 'arsip')
                                  ->countAllResults();
    
        $currentMonth = date('m');
        $currentYear = date('Y');
        $suratBaruBulanIni = $suratModel->where('pembuat_id', $userId)
                                         ->where('MONTH(created_at)', $currentMonth)
                                         ->where('YEAR(created_at)', $currentYear)
                                         ->countAllResults();
    
        $bulan = [];
        $jumlahSuratPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date('F', mktime(0, 0, 0, $i, 1));
            $jumlahSuratPerBulan[] = $suratModel->where('pembuat_id', $userId)
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
    
        return view('pages/dashboard', $data);
    }
    
}
