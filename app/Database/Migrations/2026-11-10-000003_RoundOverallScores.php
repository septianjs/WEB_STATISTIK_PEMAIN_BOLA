<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RoundOverallScores extends Migration
{
    public function up()
    {
        $this->db->query('UPDATE player_stats SET average = ROUND(average)');
    }

    public function down()
    {
    }
}
