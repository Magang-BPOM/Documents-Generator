<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surat;
use App\Models\SuratUser;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $suratModel = new Surat();

        $totalSurat = $suratModel->countAllResults();
        $suratAktif = $suratModel->where('status', 'aktif')->countAllResults();
        $suratArsip = $suratModel->where('status', 'arsip')->countAllResults();

        // Menghitung surat baru untuk bulan ini tanpa filter 'pembuat_id'
        $currentMonth = date('m');
        $currentYear = date('Y');
        $suratBaruBulanIni = $suratModel->where('MONTH(created_at)', $currentMonth)
            ->where('YEAR(created_at)', $currentYear)
            ->countAllResults();

        // Menghitung jumlah surat per bulan tanpa filter 'pembuat_id'
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
        $suratModel = new Surat();
        $suratUserModel = new SuratUser(); // Model untuk surat_user

        // Ambil semua surat_id dari surat_user berdasarkan user_id saat ini
        $suratUserIds = $suratUserModel->where('id_created', $userId)->findColumn('surat_id');

        if (!$suratUserIds) {
            // Jika tidak ada surat yang terkait dengan user ini, set semua nilai ke 0 atau kosong
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

        // Total surat berdasarkan surat_id dari surat_user
        $totalSurat = $suratModel->whereIn('id', $suratUserIds)->countAllResults();

        // Surat aktif
        $suratAktif = $suratModel->whereIn('id', $suratUserIds)
            ->where('status', 'aktif')
            ->countAllResults();

        // Surat arsip
        $suratArsip = $suratModel->whereIn('id', $suratUserIds)
            ->where('status', 'arsip')
            ->countAllResults();

        // Surat baru bulan ini
        $currentMonth = date('m');
        $currentYear = date('Y');
        $suratBaruBulanIni = $suratModel->whereIn('id', $suratUserIds)
            ->where('MONTH(created_at)', $currentMonth)
            ->where('YEAR(created_at)', $currentYear)
            ->countAllResults();

        // Menghitung jumlah surat per bulan untuk tahun berjalan
        $bulan = [];
        $jumlahSuratPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date('F', mktime(0, 0, 0, $i, 1));
            $jumlahSuratPerBulan[] = $suratModel->whereIn('id', $suratUserIds)
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
