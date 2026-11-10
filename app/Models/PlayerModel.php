<?php

namespace App\Models;

use CodeIgniter\Model;

class PlayerModel extends Model
{
    protected $table            = 'players';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'nomor_punggung',
        'posisi',
        'is_captain',
        'kaki_terkuat',
        'usia',
        'foto',
        'bio',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama'   => 'required|min_length[2]|max_length[100]',
        'posisi' => 'required',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama pemain wajib diisi.',
            'min_length' => 'Nama minimal 2 karakter.',
        ],
        'posisi' => [
            'required' => 'Posisi pemain wajib dipilih.',
        ],
    ];

    public static function getPositionOptions(): array
    {
        return [
            'Penyerang utama (ST)',
            'Penyerang tengah (CF)',
            'Sayap kanan/kiri (RW/LW)',
            'Gelandang serang (CAM)',
            'Gelandang kanan/kiri (RM/LM)',
            'Gelandang tengah (CM)',
            'Gelandang bertahan (CDM)',
            'Bek sayap (RWB/LWB)',
            'Bek kanan/kiri (RB/LB)',
            'Bek tengah (CB)',
            'Kiper (GK)',
        ];
    }

    public static function getPositionGroup(string $position): string
    {
        return match (PlayerStatModel::getPositionCode($position)) {
            'ST', 'CF', 'RW/LW' => 'Penyerang',
            'CAM', 'RM/LM', 'CM', 'CDM' => 'Gelandang',
            'RWB/LWB', 'RB/LB', 'CB' => 'Bek',
            'GK' => 'Kiper',
            default => 'Lainnya',
        };
    }

    public static function groupPlayersByPosition(array $players): array
    {
        $groups = ['Penyerang' => [], 'Gelandang' => [], 'Bek' => [], 'Kiper' => [], 'Lainnya' => []];
        foreach ($players as $player) {
            $group = self::getPositionGroup((string) ($player['posisi'] ?? ''));
            $groups[$group][] = $player;
        }

        return array_filter($groups, static fn (array $playersInGroup): bool => $playersInGroup !== []);
    }

    /**
     * Dapatkan pemain dengan statistik terbaru atau berdasarkan indikator tertentu
     */
    public function getPlayersWithStats(?string $indicator = null, ?string $posisi = null, ?string $search = null)
    {
        $builder = $this->builder();
        $builder->select('players.*, 
            ps.id as stat_id,
            ps.indikator,
            ps.tanggal as tanggal_penilaian,
            ps.tendangan,
            ps.passing,
            ps.control,
            ps.agility,
            ps.fisik,
            ps.defending,
            ps.diving,
            ps.handling,
            ps.positioning,
            ps.reflexes,
            ps.reactions,
            ps.kicking,
            ps.heading,
            ps.weak,
            ps.average,
            ps.catatan as catatan_stat');

        $indicatorCondition = $indicator
            ? ' WHERE indikator = ' . $this->db->escape($indicator)
            : '';
        $subQuery = '(SELECT player_id, MAX(id) AS max_id FROM player_stats'
            . $indicatorCondition
            . ' GROUP BY player_id) latest_stats';

        $builder->join($subQuery, 'latest_stats.player_id = players.id', 'left');
        $builder->join('player_stats ps', 'ps.id = latest_stats.max_id', 'left');

        if ($posisi && $posisi !== 'Semua') {
            $builder->where('players.posisi', $posisi);
        }

        if ($search) {
            $builder->groupStart()
                ->like('players.nama', $search)
                ->orLike('players.nomor_punggung', $search)
                ->groupEnd();
        }

        // Urutkan kapten terlebih dahulu, lalu nilai average tertinggi
        $builder->orderBy('players.is_captain', 'DESC');
        $builder->orderBy('ps.average', 'DESC');
        $builder->orderBy('players.nomor_punggung', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Dapatkan data detail pemain beserta seluruh riwayat statistiknya
     */
    public function getPlayerWithAllStats(int $id)
    {
        $player = $this->find($id);
        if (!$player) {
            return null;
        }

        $statModel = new PlayerStatModel();
        $stats = $statModel->where('player_id', $id)
                           ->orderBy('created_at', 'ASC')
                           ->findAll();

        $player['stats'] = $stats;
        $player['latest_stat'] = !empty($stats) ? end($stats) : null;

        return $player;
    }

    /**
     * Dapatkan data Ketua Pemain (Kapten)
     */
    public function getCaptain()
    {
        return $this->where('is_captain', 1)->first();
    }
}
