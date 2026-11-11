<?php

namespace App\Controllers;

use App\Models\PlayerModel;
use App\Models\PlayerStatModel;

class Home extends BaseController
{
    protected PlayerModel $playerModel;
    protected PlayerStatModel $statModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
        $this->statModel   = new PlayerStatModel();
    }

    /**
     * Halaman Utama Publik: Tempat para pemain melihat statistik mereka
     */
    public function index()
    {
        $indicator = $this->request->getGet('indikator');
        $posisi    = $this->request->getGet('posisi');
        $search    = $this->request->getGet('q');

        // Ambil semua daftar indikator minggu yang tersedia
        $availableIndicators = $this->statModel->getDistinctIndicators();

        // Ambil daftar pemain dengan statistik
        $players = $this->playerModel->getPlayersWithStats($indicator, $posisi, $search);

        // Ambil data Kapten / Ketua Pemain
        $captain = $this->playerModel->getCaptain();
        $captainWithStats = null;
        if ($captain) {
            $captainWithStats = $this->playerModel->getPlayerWithAllStats((int)$captain['id']);
        }

        // Hitung ringkasan tim
        $totalPlayers = count($players);
        $totalAverage = 0;
        $highestAverage = 0;
        $topPlayerName = '-';

        foreach ($players as $p) {
            $avg = (float)($p['average'] ?? 0);
            $totalAverage += $avg;
            if ($avg > $highestAverage) {
                $highestAverage = $avg;
                $topPlayerName = $p['nama'];
            }
        }
        $teamAverage = $totalPlayers > 0 ? round($totalAverage / $totalPlayers, 1) : 0;

        $data = [
            'title'               => 'Statistik Pemain Bola - Beranda Tim',
            'players'             => $players,
            'playersByPosition'   => PlayerModel::groupPlayersByPosition($players),
            'captain'             => $captainWithStats,
            'availableIndicators' => $availableIndicators,
            'selectedIndicator'   => $indicator,
            'selectedPosisi'      => $posisi,
            'positions'           => PlayerModel::getPositionOptions(),
            'search'              => $search,
            'statsSummary'        => [
                'totalPlayers'   => $totalPlayers,
                'teamAverage'    => $teamAverage,
                'highestAverage' => $highestAverage,
                'topPlayerName'  => $topPlayerName,
            ],
        ];

        return view('public/home', $data);
    }

    /**
     * Halaman Detail Statistik Pemain
     */
    public function player(int $id)
    {
        $player = $this->playerModel->getPlayerWithAllStats($id);

        if (!$player) {
            return redirect()->to(site_url('/'))->with('error', 'Pemain tidak ditemukan.');
        }

        // Siapkan data untuk Chart.js (Radar Chart & Progres Line Chart)
        $latestStat = $player['latest_stat'];
        $displayColumns = PlayerStatModel::getDisplayColumns($player['posisi']);
        $radarColumns = array_diff_key($displayColumns, ['HEA' => true, 'WF' => true]);
        $radarData = [
            'labels' => array_keys($radarColumns),
            'values' => array_map(
                static fn (string $field): float => (float) ($latestStat[$field] ?? 0),
                array_values($radarColumns)
            ),
        ];

        // Line chart progression per indikator / minggu
        $trendLabels = [];
        $trendAverages = [];
        foreach ($player['stats'] as $st) {
            $trendLabels[] = $st['indikator'];
            $trendAverages[] = (float)$st['average'];
        }

        $data = [
            'title'         => 'Statistik ' . esc($player['nama']) . ' #' . esc($player['nomor_punggung']),
            'player'        => $player,
            'radarData'     => $radarData,
            'radarSnapshots' => PlayerStatModel::getRadarSnapshots($player['stats'], $player['posisi']),
            'displayColumns' => $displayColumns,
            'trendLabels'   => $trendLabels,
            'trendAverages' => $trendAverages,
        ];

        return view('public/player_detail', $data);
    }

    /**
     * Halaman Leaderboard / Peringkat Atribut
     */
    public function leaderboard(?string $mode = null)
    {
        $indicator = $this->request->getGet('indikator');
        $availableIndicators = $this->statModel->getDistinctIndicators();

        $topOverall   = $this->statModel->getTopPlayers('average', 5, $indicator);
        $topTendangan = $this->statModel->getTopPlayers('tendangan', 5, $indicator);
        $topPassing   = $this->statModel->getTopPlayers('passing', 5, $indicator);
        $topControl   = $this->statModel->getTopPlayers('control', 5, $indicator);
        $topAgility   = $this->statModel->getTopPlayers('agility', 5, $indicator);
        $topFisik     = $this->statModel->getTopPlayers('fisik', 5, $indicator);
        $topDefending = $this->statModel->getTopPlayers('defending', 5, $indicator);

        $isAdminMode = $mode === 'admin' || strpos(current_url(), 'admin/leaderboard') !== false;

        $data = [
            'title'               => 'Papan Peringkat Pemain',
            'layout'              => $isAdminMode ? 'layout/admin' : 'layout/main',
            'isAdminMode'         => $isAdminMode,
            'availableIndicators' => $availableIndicators,
            'selectedIndicator'   => $indicator,
            'topOverall'          => $topOverall,
            'topTendangan'        => $topTendangan,
            'topPassing'          => $topPassing,
            'topControl'          => $topControl,
            'topAgility'          => $topAgility,
            'topFisik'            => $topFisik,
            'topDefending'        => $topDefending,
        ];

        return view('public/leaderboard', $data);
    }

    /**
     * Halaman Perbandingan Statistik Pemain (Head-to-Head)
     */
    public function compare(?string $mode = null)
    {
        $allPlayers = $this->playerModel->orderBy('nama', 'ASC')->findAll();

        $p1_id = (int)$this->request->getGet('p1');
        $p2_id = (int)$this->request->getGet('p2');

        // Default jika belum dipilih: bandingkan pemain pertama dan kedua
        if (!$p1_id && !empty($allPlayers)) {
            $p1_id = (int)$allPlayers[0]['id'];
        }
        if (!$p2_id && count($allPlayers) > 1) {
            $p2_id = (int)$allPlayers[1]['id'];
        }

        $player1 = $p1_id ? $this->playerModel->getPlayerWithAllStats($p1_id) : null;
        $player2 = $p2_id ? $this->playerModel->getPlayerWithAllStats($p2_id) : null;

        $isAdminMode = $mode === 'admin' || strpos(current_url(), 'admin/compare') !== false;

        $data = [
            'title'       => 'Bandingkan Dua Pemain',
            'layout'      => $isAdminMode ? 'layout/admin' : 'layout/main',
            'isAdminMode' => $isAdminMode,
            'allPlayers'  => $allPlayers,
            'player1'     => $player1,
            'player2'     => $player2,
            'p1_id'       => $p1_id,
            'p2_id'       => $p2_id,
        ];

        return view('public/compare', $data);
    }
}
