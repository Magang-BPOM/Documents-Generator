<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PembebananAnggaranTable extends Migration
{
    public function up()
    {
        // Membuat tabel pembebanan_anggaran
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'akun' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true); 
        $this->forge->createTable('pembebanan_anggaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembebanan_anggaran');
    }
}
