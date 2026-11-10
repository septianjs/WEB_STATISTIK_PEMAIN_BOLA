<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use App\Models\PlayerStatModel;

class StatController extends BaseController
{
    protected PlayerModel $playerModel;
    protected PlayerStatModel $statModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
        $this->statModel = new PlayerStatModel();
    }

    public function index()
    {
        $indicator = $this->request->getGet('indikator');
        $playerId = (int) $this->request->getGet('player_id') ?: null;

        return view('admin/stats/index', [
            'title' => 'Kelola Statistik Pemain',
            'stats' => $this->statModel->getStatsWithPlayer($indicator, $playerId),
            'availableIndicators' => $this->statModel->getDistinctIndicators(),
            'allPlayers' => $this->playerModel->orderBy('nama', 'ASC')->findAll(),
            'selectedIndicator' => $indicator,
            'selectedPlayerId' => $playerId,
        ]);
    }

    public function create(?int $selectedPlayerId = null)
    {
        $data = $this->formData(null, $selectedPlayerId ?: (int) $this->request->getGet('player_id'));

        return view('admin/stats/form', $data);
    }

    public function store()
    {
        $playerId = (int) $this->request->getPost('player_id');
        $player = $playerId ? $this->playerModel->find($playerId) : null;
        if (!$player) {
            return redirect()->back()->withInput()->with('errors', ['player_id' => 'Pemain tidak ditemukan.']);
        }
        if (!isset(PlayerStatModel::getRoleWeights()[PlayerStatModel::getPositionCode($player['posisi'])])) {
            return redirect()->back()->withInput()->with('errors', ['player_id' => 'Posisi pemain belum memiliki rumus Overall. Perbarui posisi pemain terlebih dahulu.']);
        }
        $rules = $this->validationRules($player['posisi'] ?? '');

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $statData = $this->buildStatData($player);
        if (!$this->statModel->insert($statData)) {
            return redirect()->back()->withInput()->with('error', 'Statistik gagal disimpan. Periksa kembali data dan koneksi database.');
        }

        return redirect()->to(site_url('admin/stats'))->with(
            'success',
            "Statistik {$player['nama']} untuk {$statData['indikator']} berhasil disimpan! Overall: {$statData['average']}"
        );
    }

    public function edit(int $id)
    {
        $stat = $this->statModel->find($id);
        if (!$stat) {
            return redirect()->to(site_url('admin/stats'))->with('error', 'Data statistik tidak ditemukan.');
        }

        return view('admin/stats/form', $this->formData($stat, (int) $stat['player_id'], 'Edit Statistik Pemain'));
    }

    public function update(int $id)
    {
        $stat = $this->statModel->find($id);
        if (!$stat) {
            return redirect()->to(site_url('admin/stats'))->with('error', 'Data statistik tidak ditemukan.');
        }

        $playerId = (int) $this->request->getPost('player_id');
        $player = $playerId ? $this->playerModel->find($playerId) : null;
        if (!$player) {
            return redirect()->back()->withInput()->with('errors', ['player_id' => 'Pemain tidak ditemukan.']);
        }
        if (!isset(PlayerStatModel::getRoleWeights()[PlayerStatModel::getPositionCode($player['posisi'])])) {
            return redirect()->back()->withInput()->with('errors', ['player_id' => 'Posisi pemain belum memiliki rumus Overall. Perbarui posisi pemain terlebih dahulu.']);
        }
        if (!$this->validate($this->validationRules($player['posisi'] ?? ''))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $statData = $this->buildStatData($player);
        if (!$this->statModel->update($id, $statData)) {
            return redirect()->back()->withInput()->with('error', 'Statistik gagal diperbarui. Periksa kembali data dan koneksi database.');
        }

        return redirect()->to(site_url('admin/stats'))->with(
            'success',
            "Statistik berhasil diperbarui! Overall baru: {$statData['average']}"
        );
    }

    public function delete(int $id)
    {
        $stat = $this->statModel->find($id);
        if (!$stat) {
            return redirect()->to(site_url('admin/stats'))->with('error', 'Data statistik tidak ditemukan.');
        }

        if (!$this->statModel->delete($id)) {
            return redirect()->to(site_url('admin/stats'))->with('error', 'Data statistik gagal dihapus.');
        }

        return redirect()->to(site_url('admin/stats'))->with('success', 'Data penilaian statistik berhasil dihapus.');
    }

    private function formData(?array $stat, int $selectedPlayerId, string $title = 'Input Nilai Statistik Pemain'): array
    {
        return [
            'title' => $title,
            'stat' => $stat,
            'allPlayers' => $this->playerModel->orderBy('is_captain', 'DESC')->orderBy('nama', 'ASC')->findAll(),
            'selectedPlayerId' => $selectedPlayerId,
            'distinctIndicators' => $this->statModel->getDistinctIndicators(),
            'fieldStatGroups' => PlayerStatModel::getFieldStatGroups(),
            'goalkeeperStats' => PlayerStatModel::getGoalkeeperStats(),
            'roleWeights' => PlayerStatModel::getRoleWeights(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ];
    }

    private function validationRules(string $position): array
    {
        $rules = [
            'player_id' => 'required|is_not_unique[players.id]',
            'indikator' => 'required|min_length[2]|max_length[50]',
            'tanggal' => 'permit_empty|valid_date',
        ];

        foreach (PlayerStatModel::getInputStatFields($position) as $field) {
            $rules[$field] = 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]';
        }

        return $rules;
    }

    private function buildStatData(array $player): array
    {
        $position = $player['posisi'];
        $input = [];
        foreach (PlayerStatModel::getInputStatFields($position) as $field) {
            $input[$field] = (int) $this->request->getPost($field);
        }

        $scores = PlayerStatModel::calculateScores($input, $position);
        $statData = [
            'player_id' => (int) $this->request->getPost('player_id'),
            'indikator' => trim((string) $this->request->getPost('indikator')),
            'tanggal' => $this->request->getPost('tanggal') ?: date('Y-m-d'),
            'tendangan' => $scores['summary']['tendangan'],
            'passing' => $scores['summary']['passing'],
            'control' => $scores['summary']['control'],
            'agility' => $scores['summary']['agility'],
            'fisik' => $scores['summary']['fisik'],
            'defending' => $scores['summary']['defending'],
            'average' => $scores['average'],
            'catatan' => trim((string) $this->request->getPost('catatan')),
        ];

        foreach (PlayerStatModel::getInputStatFields($position) as $field) {
            $statData[$field] = $input[$field];
        }

        return $statData;
    }
}
