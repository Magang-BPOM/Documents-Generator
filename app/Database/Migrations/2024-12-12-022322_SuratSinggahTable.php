<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuratSinggahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'surat_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'berangkat_dari' => ['type' => 'VARCHAR', 'constraint' => 255],
            'ke' => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'nama_tempat' => ['type' => 'VARCHAR', 'constraint' => 255],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('surat_id', 'surat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('surat_singgah');
    }

    public function down()
    {
        $this->forge->dropTable('surat_singgah');
    }
}
