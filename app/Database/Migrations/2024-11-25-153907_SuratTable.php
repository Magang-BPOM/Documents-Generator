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
            'sebagai'         => [
                'type'       => 'TEXT',
            ],
            'waktu_mulai'   => [
                'type'       => 'DATE',
            ],
            'waktu_berakhir'   => [
                'type'       => 'DATE',
            ],
            'tujuan'         => [
                'type'       => 'TEXT',
            ],
            'kota_tujuan'         => [
                'type'       => 'TEXT',
            ],
            'biaya'         => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'kategori_biaya' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'C', 'D'],
                'default'    => 'A',
            ],

            'id_pembebanan_anggaran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],

            'id_penanda_tangan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
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
        $this->forge->addKey('id_pembebanan_anggaran');
        $this->forge->addKey('id_penanda_tangan');

        $this->forge->addForeignKey('id_pembebanan_anggaran', 'pembebanan_anggaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_penanda_tangan', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('surat');
    }

    public function down()
    {
        $this->forge->dropTable('surat');
    }
}
