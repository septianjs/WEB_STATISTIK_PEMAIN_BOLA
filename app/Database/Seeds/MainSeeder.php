<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PlayerStatModel;

class MainSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Admin
        $userData = [
            'username'     => 'admin',
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'nama_lengkap' => 'Pelatih Tim',
            'email'        => 'admin@timbola.com',
            'role'         => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        // Check if admin already exists
        $userExists = $this->db->table('users')->where('username', 'admin')->get()->getRow();
        if (!$userExists) {
            $this->db->table('users')->insert($userData);
        }

        // 2. Seed Players
        $players = [
            [
                'id'             => 1,
                'nama'           => 'Jose',
                'nomor_punggung' => 10,
                'posisi'         => 'Penyerang utama (ST)',
                'is_captain'     => 1, // Ketua Pemain
                'kaki_terkuat'   => 'Kanan',
                'usia'           => 22,
                'foto'           => 'jose.svg',
                'bio'            => 'Ketua pemain (kapten tim). Memiliki naluri mencetak gol tajam, kepemimpinan tinggi, dan teknik menyeluruh sempurna.',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'id'             => 2,
                'nama'           => 'Rizky Sanjaya',
                'nomor_punggung' => 8,
                'posisi'         => 'Gelandang tengah (CM)',
                'is_captain'     => 0,
                'kaki_terkuat'   => 'Kanan',
                'usia'           => 23,
                'foto'           => 'rizky.svg',
                'bio'            => 'Playmaker lini tengah dengan visi umpan membelah pertahanan dan kontrol bola yang sangat lengket.',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'id'             => 3,
                'nama'           => 'Pratama Ardi',
                'nomor_punggung' => 5,
                'posisi'         => 'Bek tengah (CB)',
                'is_captain'     => 0,
                'kaki_terkuat'   => 'Kiri',
                'usia'           => 21,
                'foto'           => 'pratama.svg',
                'bio'            => 'Bek tangguh dengan stamina luar biasa, kecepatan menyisir sayap, dan tekel presisi.',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'id'             => 4,
                'nama'           => 'Dimas Anggara',
                'nomor_punggung' => 7,
                'posisi'         => 'Sayap kanan/kiri (RW/LW)',
                'is_captain'     => 0,
                'kaki_terkuat'   => 'Keduanya',
                'usia'           => 20,
                'foto'           => 'dimas.svg',
                'bio'            => 'Winger lincah dengan akselerasi tinggi dan kelincahan mengelabui lawan di area sempit.',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'id'             => 5,
                'nama'           => 'Bagas Kurnia',
                'nomor_punggung' => 1,
                'posisi'         => 'Kiper (GK)',
                'is_captain'     => 0,
                'kaki_terkuat'   => 'Kanan',
                'usia'           => 24,
                'foto'           => 'bagas.svg',
                'bio'            => 'Penjaga gawang dengan refleks kilat, penempatan posisi tenang, dan jangkauan bola udara optimal.',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($players as $p) {
            $pExists = $this->db->table('players')->where('id', $p['id'])->get()->getRow();
            if (!$pExists) {
                $this->db->table('players')->insert($p);
            }
        }

        // 3. Seed Player Stats
        $stats = [
            // Jose - Minggu 1 (Sesuai contoh user: tendangan 100, passing 100, control 100, agility 100, fisik 100 -> average 100)
            [
                'player_id' => 1,
                'indikator' => 'Minggu 1',
                'tanggal'   => date('Y-m-d', strtotime('-14 days')),
                'tendangan' => 100,
                'passing'   => 100,
                'control'   => 100,
                'agility'   => 100,
                'fisik'     => 100,
                'average'   => 100.00,
                'catatan'   => 'Performa sempurna di Minggu 1! Menjalankan seluruh porsi latihan dengan standar tertinggi.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            // Jose - Minggu 2
            [
                'player_id' => 1,
                'indikator' => 'Minggu 2',
                'tanggal'   => date('Y-m-d', strtotime('-7 days')),
                'tendangan' => 98,
                'passing'   => 96,
                'control'   => 99,
                'agility'   => 95,
                'fisik'     => 98,
                'average'   => 97.20,
                'catatan'   => 'Tetap prima dan konsisten, memimpin skema serangan tim.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            // Rizky Sanjaya - Minggu 1 & 2
            [
                'player_id' => 2,
                'indikator' => 'Minggu 1',
                'tanggal'   => date('Y-m-d', strtotime('-14 days')),
                'tendangan' => 84,
                'passing'   => 90,
                'control'   => 88,
                'agility'   => 83,
                'fisik'     => 85,
                'average'   => 86.00,
                'catatan'   => 'Akurasi operan sangat baik, pertahankan konsistensi.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            [
                'player_id' => 2,
                'indikator' => 'Minggu 2',
                'tanggal'   => date('Y-m-d', strtotime('-7 days')),
                'tendangan' => 86,
                'passing'   => 94,
                'control'   => 92,
                'agility'   => 85,
                'fisik'     => 87,
                'average'   => 88.80,
                'catatan'   => 'Peningkatan drastis pada umpan kunci dan visi permainan.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            // Pratama Ardi - Minggu 1 & 2
            [
                'player_id' => 3,
                'indikator' => 'Minggu 1',
                'tanggal'   => date('Y-m-d', strtotime('-14 days')),
                'tendangan' => 76,
                'passing'   => 84,
                'control'   => 80,
                'agility'   => 88,
                'fisik'     => 89,
                'average'   => 83.40,
                'catatan'   => 'Stamina fisik sangat prima sepanjang 90 menit simulasi pertandingan.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            [
                'player_id' => 3,
                'indikator' => 'Minggu 2',
                'tanggal'   => date('Y-m-d', strtotime('-7 days')),
                'tendangan' => 80,
                'passing'   => 86,
                'control'   => 82,
                'agility'   => 91,
                'fisik'     => 92,
                'average'   => 86.20,
                'catatan'   => 'Agility dan recovery lari meningkat pesat.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            // Dimas Anggara - Minggu 1 & 2
            [
                'player_id' => 4,
                'indikator' => 'Minggu 1',
                'tanggal'   => date('Y-m-d', strtotime('-14 days')),
                'tendangan' => 86,
                'passing'   => 80,
                'control'   => 88,
                'agility'   => 94,
                'fisik'     => 82,
                'average'   => 86.00,
                'catatan'   => 'Kelincahan dribel dan tusukan ke kotak penalti sangat efektif.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            [
                'player_id' => 4,
                'indikator' => 'Minggu 2',
                'tanggal'   => date('Y-m-d', strtotime('-7 days')),
                'tendangan' => 89,
                'passing'   => 82,
                'control'   => 90,
                'agility'   => 96,
                'fisik'     => 84,
                'average'   => 88.20,
                'catatan'   => 'Eksekusi tendangan kaki kiri dan kanan semakin seimbang.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            // Bagas Kurnia - Minggu 1 & 2
            [
                'player_id' => 5,
                'indikator' => 'Minggu 1',
                'tanggal'   => date('Y-m-d', strtotime('-14 days')),
                'tendangan' => 72,
                'passing'   => 74,
                'control'   => 79,
                'agility'   => 86,
                'fisik'     => 90,
                'average'   => 80.20,
                'catatan'   => 'Refleks penyelamatan sangat baik, distribusi bola perlu diasah.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            [
                'player_id' => 5,
                'indikator' => 'Minggu 2',
                'tanggal'   => date('Y-m-d', strtotime('-7 days')),
                'tendangan' => 75,
                'passing'   => 78,
                'control'   => 82,
                'agility'   => 88,
                'fisik'     => 92,
                'average'   => 83.00,
                'catatan'   => 'Distribusi tendangan jauh membaik drastis.',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
        ];

        $playerRows = $this->db->table('players')->select('id, posisi')->get()->getResultArray();
        $positionByPlayer = array_column($playerRows, 'posisi', 'id');
        foreach ($stats as &$stat) {
            $position = $positionByPlayer[$stat['player_id']];
            $input = [];

            if (PlayerStatModel::isGoalkeeper($position)) {
                $legacyMean = ($stat['tendangan'] + $stat['passing'] + $stat['control'] + $stat['agility'] + $stat['fisik']) / 5;
                foreach (array_keys(PlayerStatModel::getGoalkeeperStats()) as $field) {
                    $stat[$field] = (int) round($legacyMean);
                    $input[$field] = $stat[$field];
                }
            } else {
                foreach (PlayerStatModel::getFieldStatGroups() as $groupKey => $group) {
                    $legacyField = [
                        'shooting' => 'tendangan',
                        'passing' => 'passing',
                        'control' => 'control',
                        'agility' => 'agility',
                        'physical' => 'fisik',
                        'defending' => 'defending',
                    ][$groupKey];
                    $value = (int) ($stat[$legacyField] ?? 0);
                    $stat[$group['field']] = $value;
                    $input[$group['field']] = $value;
                }
            }

            $scores = PlayerStatModel::calculateScores($input, $position);
            $stat['average'] = $scores['average'];
            $stat['defending'] = $scores['summary']['defending'];
        }
        unset($stat);

        // Delete existing stats to prevent duplicate seeds
        $this->db->table('player_stats')->truncate();
        $this->db->table('player_stats')->insertBatch($stats);
    }
}
