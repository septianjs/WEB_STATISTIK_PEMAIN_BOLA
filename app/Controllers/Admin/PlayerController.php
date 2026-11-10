<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use App\Models\PlayerStatModel;

class PlayerController extends BaseController
{
    protected PlayerModel $playerModel;
    protected PlayerStatModel $statModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
        $this->statModel   = new PlayerStatModel();
    }

    /**
     * Tampilkan seluruh daftar pemain
     */
    public function index()
    {
        $players = $this->playerModel->getPlayersWithStats();

        $data = [
            'title'   => 'Manajemen Pemain',
            'players' => $players,
            'playersByPosition' => PlayerModel::groupPlayersByPosition($players),
        ];

        return view('admin/players/index', $data);
    }

    /**
     * Tampilkan statistik dan riwayat evaluasi pemain di panel pelatih
     */
    public function stats(int $id)
    {
        $player = $this->playerModel->getPlayerWithAllStats($id);
        if (!$player) {
            return redirect()->to(site_url('admin/players'))->with('error', 'Pemain tidak ditemukan.');
        }

        $latestStat = $player['latest_stat'] ?? [];
        $displayColumns = PlayerStatModel::getDisplayColumns($player['posisi']);
        $radarColumns = array_diff_key($displayColumns, ['HEA' => true, 'WF' => true]);
        $data = [
            'title' => 'Statistik ' . $player['nama'],
            'player' => $player,
            'displayColumns' => $displayColumns,
            'radarData' => [
                'labels' => array_keys($radarColumns),
                'values' => array_map(
                    static fn (string $field): float => (float) ($latestStat[$field] ?? 0),
                    array_values($radarColumns)
                ),
            ],
            'radarSnapshots' => PlayerStatModel::getRadarSnapshots($player['stats'], $player['posisi']),
            'trendLabels' => array_column($player['stats'], 'indikator'),
            'trendAverages' => array_map('floatval', array_column($player['stats'], 'average')),
        ];

        return view('admin/players/stats', $data);
    }

    /**
     * Form tambah pemain baru
     */
    public function create()
    {
        $data = [
            'title'  => 'Tambah Pemain Baru',
            'player' => null,
            'positions' => PlayerModel::getPositionOptions(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('admin/players/form', $data);
    }

    /**
     * Simpan data pemain baru
     */
    public function store()
    {
        $rules = [
            'nama'           => 'required|min_length[2]|max_length[100]',
            'nomor_punggung' => 'permit_empty|is_natural_no_zero|less_than[100]',
            'posisi'         => 'required|in_list[' . implode(',', PlayerModel::getPositionOptions()) . ']',
            'usia'           => 'permit_empty|is_natural_no_zero|less_than[60]',
            'foto'           => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp,image/svg+xml]|max_size[foto,3072]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isCaptain = $this->request->getPost('is_captain') ? 1 : 0;

        // Jika pemain ini diset sebagai kapten, reset kapten lainnya
        if ($isCaptain === 1) {
            $this->playerModel->where('is_captain', 1)->set(['is_captain' => 0])->update();
        }

        $fotoName = null;
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/players', $fotoName);
        }

        $playerData = [
            'nama'           => trim($this->request->getPost('nama')),
            'nomor_punggung' => $this->request->getPost('nomor_punggung') ?: null,
            'posisi'         => $this->request->getPost('posisi'),
            'is_captain'     => $isCaptain,
            'kaki_terkuat'   => $this->request->getPost('kaki_terkuat') ?: 'Kanan',
            'usia'           => $this->request->getPost('usia') ?: null,
            'foto'           => $fotoName,
            'bio'            => trim($this->request->getPost('bio')),
        ];

        $this->playerModel->insert($playerData);

        return redirect()->to(site_url('admin/players'))->with('success', 'Data pemain ' . esc($playerData['nama']) . ' berhasil ditambahkan!');
    }

    /**
     * Form edit pemain
     */
    public function edit(int $id)
    {
        $player = $this->playerModel->find($id);
        if (!$player) {
            return redirect()->to(site_url('admin/players'))->with('error', 'Pemain tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Pemain: ' . esc($player['nama']),
            'player' => $player,
            'positions' => PlayerModel::getPositionOptions(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('admin/players/form', $data);
    }

    /**
     * Perbarui data pemain
     */
    public function update(int $id)
    {
        $player = $this->playerModel->find($id);
        if (!$player) {
            return redirect()->to(site_url('admin/players'))->with('error', 'Pemain tidak ditemukan.');
        }

        $rules = [
            'nama'           => 'required|min_length[2]|max_length[100]',
            'nomor_punggung' => 'permit_empty|is_natural_no_zero|less_than[100]',
            'posisi'         => 'required|in_list[' . implode(',', PlayerModel::getPositionOptions()) . ']',
            'usia'           => 'permit_empty|is_natural_no_zero|less_than[60]',
            'foto'           => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp,image/svg+xml]|max_size[foto,3072]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isCaptain = $this->request->getPost('is_captain') ? 1 : 0;
        if ($isCaptain === 1) {
            $this->playerModel->where('id !=', $id)->where('is_captain', 1)->set(['is_captain' => 0])->update();
        }

        $fotoName = $player['foto'];
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $newFotoName = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/players', $newFotoName);

            // Hapus file lama jika ada dan bukan demo svg bawaan
            if ($player['foto'] && !in_array($player['foto'], ['jose.svg', 'rizky.svg', 'pratama.svg', 'dimas.svg', 'bagas.svg', 'default.svg'])) {
                $oldPath = FCPATH . 'uploads/players/' . $player['foto'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $fotoName = $newFotoName;
        }

        $playerData = [
            'nama'           => trim($this->request->getPost('nama')),
            'nomor_punggung' => $this->request->getPost('nomor_punggung') ?: null,
            'posisi'         => $this->request->getPost('posisi'),
            'is_captain'     => $isCaptain,
            'kaki_terkuat'   => $this->request->getPost('kaki_terkuat') ?: 'Kanan',
            'usia'           => $this->request->getPost('usia') ?: null,
            'foto'           => $fotoName,
            'bio'            => trim($this->request->getPost('bio')),
        ];

        $this->playerModel->update($id, $playerData);

        return redirect()->to(site_url('admin/players'))->with('success', 'Data pemain berhasil diperbarui.');
    }

    /**
     * Hapus pemain
     */
    public function delete(int $id)
    {
        $player = $this->playerModel->find($id);
        if (!$player) {
            return redirect()->to(site_url('admin/players'))->with('error', 'Pemain tidak ditemukan.');
        }

        // Hapus file foto jika bukan file svg default bawaan
        if ($player['foto'] && !in_array($player['foto'], ['jose.svg', 'rizky.svg', 'pratama.svg', 'dimas.svg', 'bagas.svg', 'default.svg'])) {
            $oldPath = FCPATH . 'uploads/players/' . $player['foto'];
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $this->playerModel->delete($id);

        return redirect()->to(site_url('admin/players'))->with('success', 'Pemain ' . esc($player['nama']) . ' berhasil dihapus.');
    }
}
