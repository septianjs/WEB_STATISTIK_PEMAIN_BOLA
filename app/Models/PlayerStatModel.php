<?php

namespace App\Models;

use CodeIgniter\Model;

class PlayerStatModel extends Model
{
    protected $table            = 'player_stats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'player_id',
        'indikator',
        'tanggal',
        'diving',
        'handling',
        'positioning',
        'reflexes',
        'reactions',
        'kicking',
        'heading',
        'weak',
        'tendangan',
        'passing',
        'control',
        'agility',
        'fisik',
        'defending',
        'average',
        'catatan',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    private const FIELD_STAT_GROUPS = [
        'shooting' => ['label' => 'SHO', 'name' => 'Shooting', 'field' => 'tendangan'],
        'passing' => ['label' => 'PAS', 'name' => 'Passing', 'field' => 'passing'],
        'control' => ['label' => 'CON', 'name' => 'Control', 'field' => 'control'],
        'agility' => ['label' => 'AGI', 'name' => 'Agility', 'field' => 'agility'],
        'physical' => ['label' => 'PHY', 'name' => 'Physical', 'field' => 'fisik'],
        'defending' => ['label' => 'DEF', 'name' => 'Defending', 'field' => 'defending'],
    ];

    private const GOALKEEPER_STATS = [
        'diving' => ['label' => 'Diving', 'code' => 'DIV'],
        'handling' => ['label' => 'Handling', 'code' => 'HAN'],
        'positioning' => ['label' => 'Positioning', 'code' => 'POS'],
        'reflexes' => ['label' => 'Reflexes', 'code' => 'REF'],
        'reactions' => ['label' => 'Reactions', 'code' => 'REA'],
        'kicking' => ['label' => 'Kicking', 'code' => 'KIC'],
    ];

    private const ROLE_WEIGHTS = [
        'ST' => ['shooting' => 58, 'control' => 29, 'agility' => 10, 'physical' => 3],
        'CF' => ['shooting' => 46, 'passing' => 12, 'control' => 34, 'agility' => 8],
        'RW/LW' => ['shooting' => 32, 'passing' => 27, 'control' => 32, 'agility' => 8],
        'CAM' => ['shooting' => 28, 'passing' => 32, 'control' => 32, 'agility' => 8],
        'RM/LM' => ['shooting' => 7, 'passing' => 42, 'control' => 33, 'agility' => 13, 'physical' => 5],
        'CM' => ['shooting' => 13, 'passing' => 40, 'control' => 27, 'physical' => 6, 'defending' => 14],
        'CDM' => ['passing' => 32, 'control' => 18, 'physical' => 17, 'defending' => 32],
        'RWB/LWB' => ['passing' => 20, 'control' => 25, 'agility' => 7, 'physical' => 8, 'defending' => 40],
        'RB/LB' => ['passing' => 14, 'control' => 16, 'agility' => 5, 'physical' => 14, 'defending' => 51],
        'CB' => ['passing' => 6, 'control' => 11, 'physical' => 24, 'defending' => 59],
        'GK' => ['diving' => 24, 'handling' => 22, 'positioning' => 22, 'reflexes' => 22, 'reactions' => 6, 'kicking' => 4],
    ];

    public static function getFieldStatGroups(): array
    {
        return self::FIELD_STAT_GROUPS;
    }

    public static function getGoalkeeperStats(): array
    {
        return self::GOALKEEPER_STATS;
    }

    public static function getRoleWeights(): array
    {
        return self::ROLE_WEIGHTS;
    }

    public static function getPositionCode(string $position): string
    {
        if (preg_match('/\(([A-Z]+(?:\/[A-Z]+)?)\)$/', trim($position), $matches)) {
            return $matches[1];
        }

        return match (trim($position)) {
            'Penyerang' => 'ST',
            'Gelandang' => 'CM',
            'Bek' => 'CB',
            'Kiper' => 'GK',
            default => '',
        };
    }

    public static function isGoalkeeper(string $position): bool
    {
        return self::getPositionCode($position) === 'GK';
    }

    public static function isLegacyGoalkeeperStat(?array $stat, string $position): bool
    {
        if (empty($stat) || !self::isGoalkeeper($position) || (float) ($stat['average'] ?? 0) <= 0) {
            return false;
        }

        foreach (array_diff(array_keys(self::GOALKEEPER_STATS), ['reactions']) as $field) {
            if ((float) ($stat[$field] ?? 0) !== 0.0) {
                return false;
            }
        }

        $reactions = (float) ($stat['reactions'] ?? 0);

        return $reactions === 0.0 || ($reactions > 0 && $reactions === (float) ($stat['control'] ?? 0));
    }

    /**
     * Menentukan tier visual kartu pemain berdasarkan overall rating:
     * - Standard  (0 - 60):  Desain kartu standar
     * - Pro       (61 - 79): Bingkai lekukan aerodinamis, sapphire cyber
     * - Elite     (80 - 89): Bingkai mahkota champion, border emas, animasi aura
     * - Legendary (90 - 100): Bingkai mahkota prismatik, holographic border, multi-aura
     */
    public static function getCardTier(?float $average): array
    {
        $avg = (float) ($average ?? 0);

        if ($avg >= 90) {
            return [
                'key' => 'legendary',
                'class' => 'card-tier-legendary',
                'label' => 'LEGENDARY',
                'min' => 90,
                'max' => 100,
            ];
        }

        if ($avg >= 80) {
            return [
                'key' => 'elite',
                'class' => 'card-tier-elite',
                'label' => 'ELITE',
                'min' => 80,
                'max' => 89,
            ];
        }

        if ($avg >= 61) {
            return [
                'key' => 'pro',
                'class' => 'card-tier-pro',
                'label' => 'PRO',
                'min' => 61,
                'max' => 79,
            ];
        }

        return [
            'key' => 'standard',
            'class' => 'card-tier-standard',
            'label' => 'STANDARD',
            'min' => 0,
            'max' => 60,
        ];
    }

    public static function getInputStatFields(string $position): array
    {
        return self::isGoalkeeper($position)
            ? array_keys(self::GOALKEEPER_STATS)
            : array_column(self::FIELD_STAT_GROUPS, 'field');
    }

    public static function getDisplayColumns(string $position): array
    {
        if (self::isGoalkeeper($position)) {
            $columns = [];
            foreach (self::GOALKEEPER_STATS as $field => $stat) {
                $columns[$stat['code']] = $field;
            }
        } else {
            $columns = array_column(self::FIELD_STAT_GROUPS, 'field', 'label');
        }

        return $columns;
    }

    public static function getRadarSnapshots(array $stats, string $position): array
    {
        $columns = array_diff_key(self::getDisplayColumns($position), ['HEA' => true, 'WF' => true]);
        $snapshots = [];

        foreach ($stats as $stat) {
            $id = (int) ($stat['id'] ?? 0);
            $date = !empty($stat['tanggal']) ? date('d/m/Y', strtotime($stat['tanggal'])) : '';
            $label = (string) ($stat['indikator'] ?? 'Evaluasi');
            if ($date !== '') {
                $label .= ' · ' . $date;
            }

            $statsByField = [];
            foreach ($columns as $field) {
                $statsByField[$field] = (float) ($stat[$field] ?? 0);
            }

            $snapshots[$id] = [
                'label' => $label,
                'indicator' => (string) ($stat['indikator'] ?? 'Evaluasi'),
                'values' => array_map(
                    static fn (string $field): float => (float) ($stat[$field] ?? 0),
                    array_values($columns)
                ),
                'stats' => $statsByField,
                'average' => (int) round((float) ($stat['average'] ?? 0)),
                'unavailable' => self::isLegacyGoalkeeperStat($stat, $position),
            ];
        }

        return $snapshots;
    }

    public static function calculateScores(array $input, string $position): array
    {
        $role = self::getPositionCode($position);
        if (!isset(self::ROLE_WEIGHTS[$role])) {
            throw new \InvalidArgumentException('Posisi pemain tidak memiliki bobot statistik yang valid.');
        }
        $weights = self::ROLE_WEIGHTS[$role];
        $categories = [];

        if ($role === 'GK') {
            foreach (self::GOALKEEPER_STATS as $field => $_stat) {
                $categories[$field] = (float) ($input[$field] ?? 0);
            }
        } else {
            foreach (self::FIELD_STAT_GROUPS as $groupKey => $group) {
                $categories[$groupKey] = (float) ($input[$group['field']] ?? 0);
            }
        }

        $weightedTotal = 0.0;
        $weightTotal = 0;
        foreach ($weights as $key => $weight) {
            $weightedTotal += ($categories[$key] ?? 0) * $weight;
            $weightTotal += $weight;
        }

        $summary = [
            'tendangan' => (int) round($categories['shooting'] ?? 0),
            'passing' => (int) round($categories['passing'] ?? 0),
            'control' => (int) round($categories['control'] ?? 0),
            'agility' => (int) round($categories['agility'] ?? 0),
            'fisik' => (int) round($categories['physical'] ?? 0),
            'defending' => (int) round($categories['defending'] ?? 0),
        ];

        return [
            'categories' => $categories,
            'summary' => $summary,
            'average' => $weightTotal > 0 ? (int) round($weightedTotal / $weightTotal) : 0,
        ];
    }

    public function getDistinctIndicators(): array
    {
        $rows = $this->select('indikator')
            ->distinct()
            ->orderBy('id', 'ASC')
            ->findAll();

        return array_column($rows, 'indikator');
    }

    public function getStatsWithPlayer(?string $indicator = null, ?int $playerId = null)
    {
        $builder = $this->builder();
        $builder->select('player_stats.*, players.nama as player_nama, players.nomor_punggung, players.posisi, players.foto, players.is_captain, players.usia, players.kaki_terkuat, players.bio');
        $builder->join('players', 'players.id = player_stats.player_id');

        if ($indicator) {
            $builder->where('player_stats.indikator', $indicator);
        }

        if ($playerId) {
            $builder->where('player_stats.player_id', $playerId);
        }

        $builder->orderBy('player_stats.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getTopPlayers(string $column = 'average', int $limit = 5, ?string $indicator = null)
    {
        $allowedColumns = ['average', 'tendangan', 'passing', 'control', 'agility', 'fisik', 'defending'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'average';
        }

        $builder = $this->builder();
        $builder->select("player_stats.$column as score, player_stats.average, player_stats.indikator, players.id as player_id, players.nama, players.nomor_punggung, players.posisi, players.foto, players.is_captain");
        $builder->join('players', 'players.id = player_stats.player_id');

        if ($indicator) {
            $builder->where('player_stats.indikator', $indicator);
        }

        if ($column !== 'average') {
            $builder->whereNotIn('players.posisi', ['Kiper (GK)', 'Kiper']);
        }

        $builder->orderBy("player_stats.$column", 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }
}
