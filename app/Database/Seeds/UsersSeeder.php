<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'      => 'Admin',
                'nip'       => '121212',
                'jabatan'   => 'Administrator',
                'pangkat'   => 'Admin / N/A',
                'foto_profil' => 'https://i.pravatar.cc/150?img=3', 
                'password' => password_hash('12', PASSWORD_BCRYPT),
                'role' => 'admin',
                'is_penanda_tangan' => 1,
                'is_bendahara'=>0,
                'kepala_balai'=>0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama'      => 'Dra. Hesti Sila Rahayu, Apt',
                'nip'       => '12345678',
                'jabatan'   => 'Kepala BPOM',
                'pangkat'   => 'Admin / N/A',
                'foto_profil' => 'https://i.pravatar.cc/150?img=3', 
                'password' => password_hash('12', PASSWORD_BCRYPT),
                'role' => 'admin',
                'is_penanda_tangan' => 1,
                'is_bendahara'=>0,
                'kepala_balai'=>0,
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
                'is_penanda_tangan' => 0,
                'is_bendahara'=>0,
                'kepala_balai'=>0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Agus Salim, S.Farm, Apt, M.Farm',
                'nip' => '121203456',
                'pangkat' => 'Penata Tk.I / Gol.II-d',
                'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
                'foto_profil' => 'https://i.pravatar.cc/150?img=2', 
                'password' => password_hash('987654321', PASSWORD_BCRYPT),
                'role' => 'pegawai',
                'is_penanda_tangan' => 0,
                'is_bendahara'=>0,
                'kepala_balai'=>1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Nia Ramadhani, S.Farm, Apt, M.Farm',
                'nip' => '121203456121',
                'pangkat' => 'Penata Tk.I / Gol.II-d',
                'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
                'foto_profil' => 'https://i.pravatar.cc/150?img=2', 
                'password' => password_hash('987654321', PASSWORD_BCRYPT),
                'role' => 'pegawai',
                'is_penanda_tangan' => 0,
                'is_bendahara'=>1,
                'kepala_balai'=>0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}
