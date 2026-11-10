<?php

namespace App\Database\Migrations;

use App\Models\PlayerStatModel;
use CodeIgniter\Database\Migration;

class RecalculateOutfieldOverallScores extends Migration
{
    public function up()
    {
        $rows = $this->db->table('player_stats')
            ->select('player_stats.*, players.posisi')
            ->join('players', 'players.id = player_stats.player_id')
            ->where('players.posisi !=', 'Kiper (GK)')
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $scores = PlayerStatModel::calculateScores($row, $row['posisi']);
            $this->db->table('player_stats')
                ->where('id', $row['id'])
                ->update(['average' => $scores['average']]);
        }
    }

    public function down()
    {
        $this->db->query("UPDATE player_stats
            JOIN players ON players.id = player_stats.player_id
            SET player_stats.average = ROUND(
                (player_stats.tendangan + player_stats.passing + player_stats.control + player_stats.agility + player_stats.fisik) / 5,
                2
            )
            WHERE players.posisi != 'Kiper (GK')");
    }
}
