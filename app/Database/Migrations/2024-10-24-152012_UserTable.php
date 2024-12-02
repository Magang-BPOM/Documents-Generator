<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama'        => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nip'         => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'jabatan'     => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'pangkat'     => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'foto_profil' => [
                'type' => 'VARCHAR',
                'constraint' => 255, 
                'null' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255, 
                'null' => false,
            ],
            'role'        => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'is_penanda_tangan' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, 
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME', 
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user'); 
    }

    public function down()
    {
        $this->forge->dropTable('user'); 
    }
}
