<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'      => 'Admin User',
                'nip'       => '12',
                'jabatan'   => 'Administrator',
                'pangkat'   => 'Admin / N/A',
                'foto_profil' => 'https://i.pravatar.cc/150?img=3', 
                'password' => password_hash('12', PASSWORD_BCRYPT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama'      => 'Dra. Hesti Sila Rahayu, Apt',
                'nip'       => '123456789',
                'jabatan'   => 'Pengawas Farmasi dan Makanan Ahli Madya',
                'pangkat'   => 'Pembina / Gol.IV-a',
                'foto_profil' => 'https://i.pravatar.cc/150?img=1', 
                'password' => password_hash('123456789', PASSWORD_BCRYPT),
                'role' => 'pegawai',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Bagoes Lanang, S.Farm, Apt, M.Farm',
                'nip' => '987654321',
                'pangkat' => 'Penata Tk.I / Gol.III-d',
                'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
                'foto_profil' => 'https://i.pravatar.cc/150?img=2', 
                'password' => password_hash('987654321', PASSWORD_BCRYPT),
                'role' => 'pegawai',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Agus Salim, S.Farm, Apt, M.Farm',
                'nip' => '987654321',
                'pangkat' => 'Penata Tk.I / Gol.II-d',
                'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
                'foto_profil' => 'https://i.pravatar.cc/150?img=2', 
                'password' => password_hash('987654321', PASSWORD_BCRYPT),
                'role' => 'pegawai',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}
