<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Surat;
use App\Models\SuratUser;

class SuratsSeeder extends Seeder
{
    public function run()
    {
        // Example of request data that should come from the form
        $requestData = [
            'nomor_surat' => 'PW.01.05.11A.07.24.1234',
            'menimbang' => 'Bahwa dalam rangka melaksanakan kebijakan pengawasan di bidang obat dan makanan.',
            'dasar' => "Peraturan Presiden No. 80 Tahun 2017 tentang Badan Pengawasan Obat dan Makanan;\nPeraturan Mentri Dalam Negeri Nomor 41 Tahun 2018 tentang peningkatan Koordinasi Pembinaan dan Pengawasan Obat dan Makanan di Daerah;\nPeraturan Badan Pengawasan Obat dan Makanan No. 22 Tahun 2021 tentang Tata Cara Penerbitan Izin Penerapan Cara Produksi Pangan Oalahan yang baik.",
            'untuk' => "Sebagai Narasumber kegiatan pelatihan yang diadakan oleh UPT Pelatihan Pertanian Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur berjudul \"Pelatihan Pengolahan Tanaman Pangan dan Holtikultura Bagi KWT Angkatan I\";\nWaktu : Rabu, 23 Oktober 2024;\nTujuan : Kantor UPT Pelatihan Pertanian, Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur Jl.Raya Dr. Cipto No. 123, Bedali Lawang Malang\nBiaya : DIPA Balai Besar POM di Surabaya Tahun 2024.\nMAK : 3165.QIC.004.053.B.524113",
            'ttd_tanggal' => '2024-10-24',
            'penanda_tangan' => 'Budi Sulistyowati, S.Farm, Apt',
            'jabatan_ttd' => 'Plt. Kepala Balai Besar POM di Surabaya',
            'selected_user' => '2,3', // List of user IDs that will be inserted into surat_user table
        ];

        // Prepare data for 'surat' table
        $dataSurat = [
            'nomor_surat' => $requestData['nomor_surat'],
            'menimbang' => $requestData['menimbang'],
            'dasar' => implode("; ", array_filter(array_map('trim', explode("\n", $requestData['dasar'])))),
            'untuk' => implode("; ", array_filter(array_map('trim', explode("\n", $requestData['untuk'])))),
            'ttd_tanggal' => $requestData['ttd_tanggal'],
            'penanda_tangan' => $requestData['penanda_tangan'],
            'jabatan_ttd' => $requestData['jabatan_ttd'],
            'status' => 'aktif',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert the surat data into 'surat' table
        $suratModel = new Surat();
        $suratModel->insert($dataSurat);
        $suratId = $suratModel->getInsertID();

        // Handle user relations (surat_user)
        $userIds = explode(',', $requestData['selected_user']);
        log_message('debug', 'User IDs: ' . implode(',', $userIds));

        $pembuatId = 1; // Set the creator ID for the record, adjust accordingly if needed
        
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
