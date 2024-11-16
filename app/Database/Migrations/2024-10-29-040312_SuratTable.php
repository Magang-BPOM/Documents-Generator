<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuratTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_surat'   => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'menimbang'     => [
                'type'       => 'TEXT',
            ],
            'dasar'         => [
                'type'       => 'TEXT',
            ],
            'untuk'         => [
                'type'       => 'TEXT',
            ],
            'ttd_tanggal'   => [
                'type'       => 'DATE',
            ],
            'penanda_tangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jabatan_ttd' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'status'       => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'arsip'],
                'default'    => 'aktif',
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
        $this->forge->createTable('surat');
    }

    public function down()
    {
        $this->forge->dropTable('surat');
    }
}
