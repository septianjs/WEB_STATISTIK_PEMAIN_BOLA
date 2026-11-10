<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPositionWeightedStats extends Migration
{
    public function up()
    {
        $columns = [];
        $fields = [
            'finishing', 'att_positioning', 'shot_power', 'long_shots', 'volleys',
            'short_pass', 'long_pass', 'vision', 'crossing',
            'ball_control', 'dribbling', 'reactions',
            'agility_stat', 'acceleration', 'sprint_speed',
            'stamina', 'strength', 'aggression', 'jumping',
            'marking', 'standing_tackle', 'sliding_tackle', 'interceptions',
            'diving', 'handling', 'positioning', 'reflexes', 'kicking',
            'heading',
        ];

        foreach ($fields as $field) {
            $columns[$field] = [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ];
        }

        $columns['defending'] = [
            'type'       => 'DECIMAL',
            'constraint' => '5,2',
            'default'    => 0,
        ];
        $this->forge->addColumn('player_stats', $columns);

        $decimalColumns = [];
        foreach (['tendangan', 'passing', 'control', 'agility', 'fisik', 'average'] as $field) {
            $decimalColumns[$field] = [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0,
            ];
        }
        $this->forge->modifyColumn('player_stats', $decimalColumns);

        $this->db->query('UPDATE player_stats SET
            finishing = tendangan, att_positioning = tendangan, shot_power = tendangan, long_shots = tendangan, volleys = tendangan,
            short_pass = passing, long_pass = passing, vision = passing, crossing = passing,
            ball_control = control, dribbling = control, reactions = control,
            agility_stat = agility, acceleration = agility, sprint_speed = agility,
            stamina = fisik, strength = fisik, aggression = fisik, jumping = fisik');

        $positionMap = [
            'Penyerang' => 'Penyerang utama (ST)',
            'Gelandang' => 'Gelandang tengah (CM)',
            'Bek' => 'Bek tengah (CB)',
            'Kiper' => 'Kiper (GK)',
        ];
        foreach ($positionMap as $oldPosition => $newPosition) {
            $this->db->table('players')
                ->where('posisi', $oldPosition)
                ->update(['posisi' => $newPosition]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('player_stats', [
            'finishing', 'att_positioning', 'shot_power', 'long_shots', 'volleys',
            'short_pass', 'long_pass', 'vision', 'crossing',
            'ball_control', 'dribbling', 'reactions',
            'agility_stat', 'acceleration', 'sprint_speed',
            'stamina', 'strength', 'aggression', 'jumping',
            'marking', 'standing_tackle', 'sliding_tackle', 'interceptions',
            'diving', 'handling', 'positioning', 'reflexes', 'kicking',
            'heading', 'defending',
        ]);

        $integerColumns = [];
        foreach (['tendangan', 'passing', 'control', 'agility', 'fisik'] as $field) {
            $integerColumns[$field] = [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ];
        }
        $this->forge->modifyColumn('player_stats', $integerColumns);

        foreach ([
            'Penyerang' => ['Penyerang utama (ST)', 'Penyerang tengah (CF)', 'Sayap kanan/kiri (RW/LW)'],
            'Gelandang' => ['Gelandang serang (CAM)', 'Gelandang kanan/kiri (RM/LM)', 'Gelandang tengah (CM)', 'Gelandang bertahan (CDM)'],
            'Bek' => ['Bek sayap (RWB/LWB)', 'Bek kanan/kiri (RB/LB)', 'Bek tengah (CB)'],
            'Kiper' => ['Kiper (GK)'],
        ] as $oldPosition => $newPositions) {
            $this->db->table('players')
                ->whereIn('posisi', $newPositions)
                ->update(['posisi' => $oldPosition]);
        }
    }
}
