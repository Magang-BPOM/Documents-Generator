<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TemplatesTable extends Migration
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
            'jenis_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => 100
            ],
            'menimbang' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'dasar' => [
                'type'       => 'TEXT',
                'null'       => true
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
        $this->forge->createTable('templates');
    }

    public function down()
    {
        $this->forge->dropTable('templates');
    }
}
