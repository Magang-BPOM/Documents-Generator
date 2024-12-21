<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RincianBiayaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'surat_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'surat_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'perincian_biaya' => [
                'type' => 'TEXT',
            ],
            'jumlah' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tanggal_tanda_tangan' => [
                'type' => 'DATE',
            ],
            'bendahara_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_penanda_tangan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('surat_id', 'surat', 'id', 'CASCADE', 'CASCADE'); 
        $this->forge->addForeignKey('surat_user_id', 'surat_user', 'id', 'CASCADE', 'CASCADE'); 
        $this->forge->addForeignKey('bendahara_id', 'user', 'id', 'CASCADE', 'CASCADE'); 
        $this->forge->addForeignKey('id_penanda_tangan', 'user', 'id', 'CASCADE', 'CASCADE'); 
        $this->forge->createTable('rincian_biaya');
    }

    public function down()
    {
        $this->forge->dropTable('rincian_biaya_perjalanan_dinas');
    }
}

