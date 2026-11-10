<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use App\Models\PlayerStatModel;

class DashboardController extends BaseController
{
    protected PlayerModel $playerModel;
    protected PlayerStatModel $statModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
        $this->statModel   = new PlayerStatModel();
    }

    public function index()
    {
        $totalPlayers = $this->playerModel->countAllResults();
        $totalStats   = $this->statModel->countAllResults();

        // Rata-rata tim keseluruhan
        $allStats = $this->statModel->selectAvg('average', 'avg_score')->first();
        $teamAverage = $allStats['avg_score'] ? round((float)$allStats['avg_score'], 1) : 0;

        // Data kapten
        $captain = $this->playerModel->getCaptain();

        // 5 Evaluasi terbaru
        $recentStats = $this->statModel->getStatsWithPlayer();
        $recentStats = array_slice($recentStats, 0, 5);

        // 5 Pemain terdaftar terbaru
        $recentPlayers = $this->playerModel->orderBy('created_at', 'DESC')->findAll(5);

        $data = [
            'title'         => 'Dashboard Pelatih - Statistik Pemain Bola',
            'totalPlayers'  => $totalPlayers,
            'totalStats'    => $totalStats,
            'teamAverage'   => $teamAverage,
            'captain'       => $captain,
            'recentStats'   => $recentStats,
            'recentPlayers' => $recentPlayers,
        ];

        return view('admin/dashboard', $data);
    }
}
