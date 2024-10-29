<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuratsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nomor_surat' => 'PW.01.05.11A.07.24.1234',
                'menimbang' => 'Bahwa dalam rangka melaksanakan kebijakan pengawasan di bidang obat dan makanan.',
                'dasar' => '1. Undang-Undang No. 8 Tahun 1999 tentang Perlindungan Konsumen; 2. Undang-Undang No. 17 Tahun 2023 tentang Kesehatan;',
                'untuk' => '1. Melaksanakan Tugas Pengawasan dan pemeriksaan peredaran obat dan makanan dan/atau pengambilan sampel pada sarana produksi, sarana distribusi, dan/atau sarana pelayanan
		                    2. Waktu : Senin, 8 Juli 2024
		                    3. Tujuan : Kota Surabaya
		                    4. Biaya	: DIPA Balai Besar POM di Surabaya Tahun 2024. 
			                MAK : 3165.QIC.004.053.B.524113',
                'ttd_tanggal' => '2024-10-24',
                'penanda_tangan' => 'Dr. John Doe',
                'jabatan_ttd' => 'Plt. Kepala Balai Besar POM di Surabaya',
                'pembuat_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('surat')->insertBatch($data);
    }
}
