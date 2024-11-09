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
                'dasar' => 'Peraturan Presiden No. 80 Tahun 2017 tentang Badan Pengawasan Obat dan Makanan;; Peraturan Mentri Dalam Negeri Nomor 41 Tahun 2018 tentang peningkatan Koordinasi Pembinaan dan Pengawasan Obat dan Makanan di Daerah;; Peraturan Badan Pengawasan Obat dan Makanan No. 22 Tahun 2021 tentang Tata Cara Penerbitan Izin Penerapan Cara Produksi Pangan Oalahan yang baik.',
                'untuk' => 'Sebagai Narasumber kegiatan pelatihan yang diadakan oleh UPT Pelatihan Pertanian Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur berjudul "Pelatihan Pengolahan Tanaman Pangan dan Holtikultura Bagi KWT Angkatan I"; 
                            Waktu : Rabu, 23 Oktober 2024; 
                            Tujuan : Kantor UPT Pelatihan Pertanian, Dinas Pertanian dan Ketahanan Pangan Provinsi Jawa Timur Jl.Raya Dr. Cipto No. 123, Bedali Lawang Malang
		                    Biaya	: DIPA Balai Besar POM di Surabaya Tahun 2024. 
			                MAK : 3165.QIC.004.053.B.524113',
                'ttd_tanggal' => '2024-10-24',
                'penanda_tangan' => 'Budi Sulistyowati, S.Farm, Apt',
                'jabatan_ttd' => 'Plt. Kepala Balai Besar POM di Surabaya',
                'pembuat_id' => 1,
                'status'        => 'aktif',
                'created_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('surat')->insertBatch($data);
    }
}
