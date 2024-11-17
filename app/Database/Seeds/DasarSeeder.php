<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DasarSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'undang' => 'Peraturan Presiden No. 80 Tahun 2017 tentang Badan Pengawasan Obat dan Makanan.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'undang' => 'Peraturan Mentri Dalam Negeri Nomor 41 Tahun 2018 tentang peningkatan Koordinasi Pembinaan dan Pengawasan Obat dan Makanan di Daerah.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'undang' => 'Peraturan Badan Pengawasan Obat dan Makanan No. 22 Tahun 2021 tentang Tata Cara Penerbitan Izin Penerapan Cara Produksi Pangan Oalahan yang baik.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('dasar')->insertBatch($data);
    }
}
