<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // 1. Table Users (Admin)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'admin',
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
        $this->forge->createTable('users', true);

        // 2. Table Players (Data Pemain)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nomor_punggung' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'posisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Penyerang',
            ],
            'is_captain' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'kaki_terkuat' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Kanan',
            ],
            'usia' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('players', true);

        // 3. Table Player Stats (Statistik per Indikator / Minggu)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'player_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'indikator' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Minggu 1',
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'weak' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 80,
            ],
            'tendangan' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'passing' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'control' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'agility' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'fisik' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'average' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('player_id', 'players', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('player_stats', true);
    }

    public function down()
    {
        $this->forge->dropTable('player_stats', true);
        $this->forge->dropTable('players', true);
        $this->forge->dropTable('users', true);
    }
}
