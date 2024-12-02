<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PembebananAnggaranSeeder extends Seeder
{
  public function run()
    {
        $data = [
            [
                'instansi' => 'Balai Besar Pengawas Obat dan Makanan di Surabaya',
                'akun'     => '3165.BKB.001.052.F.524111',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'instansi' => 'Balai Besar Pengawas Obat dan Makanan di Surabaya',
                'akun'     => '3165.BKB.002.052.F.524112',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pembebanan_anggaran')->insertBatch($data);

    }
}
