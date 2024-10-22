<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuratTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => 50
            ],
            'template_id' => [
                'type'       => 'INT',
                'unsigned'   => true
            ],
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true
            ],
            'kepada' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'untuk' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'tanggal_surat' => [
                'type'       => 'DATE'
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('template_id', 'templates', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('surats');
    }

    public function down()
    {
        $this->forge->dropTable('surats');
    }
}
