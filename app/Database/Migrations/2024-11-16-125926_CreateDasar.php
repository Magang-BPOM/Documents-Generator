<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDasar extends Migration
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
            'undang'  => [
                'type'       => 'text',
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
        $this->forge->createTable('dasar');
    }

    public function down()
    {
        $this->forge->dropTable('dasar');
    }
}
