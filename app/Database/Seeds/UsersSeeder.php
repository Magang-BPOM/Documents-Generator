<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'John Doe',
                'nip' => '123456789',
                'pangkat_gol' => 'III/a',
                'jabatan' => 'Manager',
                'password' => password_hash('123456789', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Jane Smith',
                'nip' => '987654321',
                'pangkat_gol' => 'IV/b',
                'jabatan' => 'Supervisor',
                'password' => password_hash('987654321', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
    
        $this->db->table('users')->insertBatch($data);
    }
}
