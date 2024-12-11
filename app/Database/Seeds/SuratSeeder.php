<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Surat;
use App\Models\SuratUser;

class SuratSeeder extends Seeder
{
    public function run()
    {
        
        $requestData = [
            'nomor_surat' => 'PW.01.05.11A.07.24.1234',
            'menimbang' => 'Bahwa dalam rangka melaksanakan kebijakan pengawasan di bidang obat dan makanan.',
            'dasar' => "Peraturan Presiden No. 80 Tahun 2017 tentang Badan Pengawasan Obat dan Makanan;\nPeraturan Mentri Dalam Negeri Nomor 41 Tahun 2018 tentang peningkatan Koordinasi Pembinaan dan Pengawasan Obat dan Makanan di Daerah;\nPeraturan Badan Pengawasan Obat dan Makanan No. 22 Tahun 2021 tentang Tata Cara Penerbitan Izin Penerapan Cara Produksi Pangan Oalahan yang baik.",
            'sebagai'=>"Sebagai Narasumber kegiatan pelatihan yang diadakan oleh UPT Pelatihan Pertanian Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur berjudul \"Pelatihan Pengolahan Tanaman Pangan dan Holtikultura Bagi KWT Angkatan I\";",
            'waktu_mulai' => '2024-10-22', 
            'waktu_berakhir' => '2024-10-24', 
            'tujuan'=>"Kantor UPT Pelatihan Pertanian, Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur Jl.Raya Dr. Cipto No. 123, Bedali Lawang Malang",
            'kota_tujuan'=>"Malang",
            'biaya'=>"DIPA Balai Besar POM di Surabaya Tahun 2024.MAK : 3165.QIC.004.053.B.524113",
            'kategori_biaya'=>'A',
            'id_pembebanan_anggaran'=>'1',
            'ttd_tanggal' => '2024-10-24',
            'id_penanda_tangan' => '2',
            'selected_user' => '2,3', 
        ];

        $dataSurat = [
            'nomor_surat' => $requestData['nomor_surat'],
            'menimbang' => $requestData['menimbang'],
            'dasar' => implode("; ", array_filter(array_map('trim', explode("\n", $requestData['dasar'])))),
            'sebagai'=> $requestData['sebagai'],
            'waktu_mulai' => $requestData['waktu_mulai'],
            'waktu_berakhir' => $requestData['waktu_berakhir'],
            'tujuan'=>$requestData['tujuan'],
            'kota_tujuan'=>$requestData['kota_tujuan'],
            'biaya'=> $requestData['biaya'],
            'kategori_biaya'=> $requestData['kategori_biaya'],
            'id_pembebanan_anggaran'=> $requestData['id_pembebanan_anggaran'],
            'ttd_tanggal' => $requestData['ttd_tanggal'],
            'id_penanda_tangan' => $requestData['id_penanda_tangan'],
            'ttd_tanggal' => date('Y-m-d H:i:s'),
            'status' => 'aktif',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $suratModel = new Surat();
        $suratModel->insert($dataSurat);
        $suratId = $suratModel->getInsertID();

        $userIds = explode(',', $requestData['selected_user']);
        log_message('debug', 'User IDs: ' . implode(',', $userIds));

        $pembuatId = 1; 
        
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
    }
}
