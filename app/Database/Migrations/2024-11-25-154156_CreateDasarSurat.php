<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDasarSurat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_surat'  => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_dasar'   => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('id_dasar', 'dasar', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_surat', 'surat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dasarsurat');
    }

    public function down()
    {
        $this->forge->dropTable('dasarsurat');
    }
}


