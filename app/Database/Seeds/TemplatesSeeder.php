<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TemplatesSeeder extends Seeder
{
    public function run()
    {
        // Insert data into templates table
        $data = [
            [
                'jenis_surat' => 'Surat Keputusan',
                'menimbang'   => 'Menimbang bahwa perlu diadakan perubahan pada sistem...',
                'dasar'       => 'Berdasarkan Undang-Undang No. 12 Tahun 2020...',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'jenis_surat' => 'Surat Pemberitahuan',
                'menimbang'   => 'Menimbang bahwa akan dilaksanakan acara...',
                'dasar'       => 'Berdasarkan rapat yang telah dilakukan pada tanggal...',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('templates')->insertBatch($data);
    }
}
