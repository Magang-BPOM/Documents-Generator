<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuratsSeeder extends Seeder
{
    public function run()
    {
        // Insert data into surats table
        $data = [
            [
                'nomor_surat'    => 'SK-001/2024',
                'template_id'    => 1,  
                'user_id'        => 1, 
                'kepada'         => 'Kepala Bagian Keuangan',
                'untuk'          => 'Pengajuan Laporan Tahunan',
                'tanggal_surat'  => '2024-01-15',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ],
            [
                'nomor_surat'    => 'SP-002/2024',
                'template_id'    => 2, 
                'user_id'        => 2, 
                'kepada'         => 'Direktur Utama',
                'untuk'          => 'Undangan Rapat Bulanan',
                'tanggal_surat'  => '2024-02-10',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('surats')->insertBatch($data);
    }
}
