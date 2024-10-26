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
