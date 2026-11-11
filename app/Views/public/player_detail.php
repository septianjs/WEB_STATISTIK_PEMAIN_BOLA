<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php 
    $latest = $player['latest_stat'] ?? [];
    $avg = (float)($latest['average'] ?? 0);
    $fotoSrc = $player['foto'] ? 'uploads/players/' . $player['foto'] : 'uploads/players/default.svg';
    $metricColumns = $displayColumns;
    $hasLegacyGoalkeeperStats = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($latest, $player['posisi']);
?>

<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Top Bar: Navigation & Print Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 no-print">
        <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Skuad
        </a>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-pitch-850 hover:bg-pitch-800 text-slate-300 hover:text-white border border-pitch-750 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-print text-xs text-emerald-400"></i> Cetak / Simpan PDF
            </button>
            <a href="<?= site_url('compare?p1=' . $player['id']) ?>" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-pitch-850 hover:bg-pitch-800 text-emerald-300 border border-emerald-500/30 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-code-compare text-xs"></i> Bandingkan Pemain
            </a>
        </div>
    </div>

    <!-- Main Dossier Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Athlete Identity Sheet (4 cols) -->
        <div class="lg:col-span-4 editorial-panel rounded-2xl p-6 space-y-5 border-l-4 border-l-emerald-400">
            
            <div class="flex items-start justify-between border-b border-pitch-750/80 pb-4">
                <div>
                    <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">No. Punggung</span>
                    <span class="font-mono font-black text-2xl text-emerald-400 tabular-nums">#<?= esc($player['nomor_punggung'] ?? '-') ?></span>
                </div>
                <div class="text-right">
                    <?php if ($player['is_captain']): ?>
                        <span class="px-2 py-0.5 rounded bg-amber-500/20 border border-amber-500/40 text-amber-300 font-mono font-bold text-[10px] uppercase block mb-1">
                            KAPTEN TIM
                        </span>
                    <?php endif; ?>
                    <span class="text-xs font-bold text-slate-300 block"><?= esc($player['posisi']) ?></span>
                </div>
            </div>

            <!-- Avatar Frame -->
            <div class="w-36 h-36 mx-auto rounded-xl bg-pitch-950 border border-pitch-750 p-2 flex items-center justify-center">
                <img src="<?= base_url($fotoSrc) ?>" alt="<?= esc($player['nama']) ?>" class="w-full h-full object-contain">
            </div>

            <!-- Name & Meta -->
            <div class="text-center">
                <h1 class="font-display font-black text-2xl text-white"><?= esc($player['nama']) ?></h1>
                <p class="text-xs text-emerald-200/70 mt-1">
                    <?= esc($player['usia'] ? $player['usia'] . ' Tahun' : 'Usia -') ?> • Kaki: <strong class="text-emerald-300"><?= esc($player['kaki_terkuat'] ?? 'Kanan') ?></strong>
                </p>
            </div>

            <!-- Quick Overall Summary Box -->
            <div class="p-4 rounded-xl bg-pitch-950 border border-pitch-750 text-center">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-wider block">Rata-Rata Overall</span>
                <div id="playerOverallValue" class="font-mono font-black text-3xl text-emerald-400 tabular-nums my-1">
                    <?= $avg > 0 ? number_format($avg, 0) : '0' ?>
                </div>
                <span class="text-[11px] text-emerald-300/70 font-mono">Evaluasi: <span id="playerOverallIndicator"><?= esc($latest['indikator'] ?? 'Belum ada data') ?></span></span>
            </div>

            <?php if (!empty($player['bio'])): ?>
                <div class="p-3.5 rounded-xl bg-pitch-950/80 border border-pitch-750 text-xs text-slate-300 leading-relaxed">
                    <span class="font-bold text-emerald-400 block text-[10px] uppercase font-mono mb-1">Profil & Peran:</span>
                    <?= esc($player['bio']) ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- Right: Radar Matrix & Attribute Breakdown (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="editorial-panel rounded-2xl p-6 sm:p-7">
                <div class="flex items-center justify-between border-b border-pitch-750/80 pb-4 mb-6">
                    <div>
                        <h2 class="font-display font-bold text-lg text-white">DIAGRAM ATRIBUT (RADAR CHART)</h2>
                        <p id="radarPeriodLabel" class="text-xs text-emerald-300/70">Analisis atribut sesuai posisi pemain</p>
                    </div>
                    <?php if (!empty($radarSnapshots)): ?>
                        <label class="text-xs text-slate-400 font-mono">
                            <span class="block mb-1">Pilih minggu</span>
                            <select id="radarPeriodSelect" class="px-2.5 py-1.5 rounded bg-pitch-950 border border-pitch-750 text-emerald-400">
                                <?php foreach ($radarSnapshots as $statId => $snapshot): ?>
                                    <option value="<?= esc($statId) ?>" <?= (int) $statId === (int) array_key_last($radarSnapshots) ? 'selected' : '' ?>>
                                        <?= esc($snapshot['label']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    <?php else: ?>
                        <span class="text-xs font-mono px-2.5 py-1 rounded bg-pitch-950 border border-pitch-750 text-emerald-400">Skala: 0 - 100</span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    
                    <!-- Radar Canvas (7 cols) -->
                    <div class="md:col-span-7 h-72 flex items-center justify-center">
                        <canvas id="playerRadarChart"></canvas>
                    </div>

                    <div class="md:col-span-5 space-y-3 font-mono text-xs">
                        <?php foreach ($metricColumns as $label => $field): ?>
                            <?php $score = (float)($latest[$field] ?? 0); ?>
                            <div>
                                <div class="flex justify-between text-[11px] mb-1 font-semibold">
                                    <span class="text-slate-300"><?= esc($label) ?></span>
                                    <span data-stat-value="<?= esc($field) ?>" class="text-white tabular-nums"><?= $hasLegacyGoalkeeperStats ? '—' : number_format($score, 0) . '/100' ?></span>
                                </div>
                                <div class="w-full bg-pitch-950 rounded h-1.5 overflow-hidden <?= $hasLegacyGoalkeeperStats ? 'hidden' : '' ?>" data-stat-bar="<?= esc($field) ?>">
                                    <div class="bg-emerald-400 h-full" style="width: <?= min(100, max(0, $score)) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <p id="statUnavailableNotice" class="pt-1 text-[10px] text-amber-600 <?= $hasLegacyGoalkeeperStats ? '' : 'hidden' ?>">Stat GK lama belum memiliki rincian.</p>
                    </div>

                </div>

            </div>

            <!-- Latest Evaluation Coach Notes -->
            <?php if (!empty($latest['catatan'])): ?>
                <div class="p-4 rounded-xl bg-pitch-900 border border-pitch-750 text-xs text-slate-300 leading-relaxed">
                    <span class="text-emerald-400 font-bold block font-mono text-[10px] uppercase mb-1">
                        Catatan Evaluasi Pelatih (<?= esc($latest['indikator']) ?>):
                    </span>
                    <?= esc($latest['catatan']) ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <!-- Section 2: Chronological Performance History -->
    <div class="editorial-panel rounded-2xl p-6 sm:p-7 space-y-6">
        
        <div class="flex items-center justify-between border-b border-pitch-750/80 pb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white">RIWAYAT & TREN EVALUASI MINGGUAN</h3>
                <p class="text-xs text-emerald-300/70">Pencatatan berkala setiap indikator sesi latihan dan pertandingan</p>
            </div>
        </div>

        <?php if (!empty($trendLabels)): ?>
            <!-- Line Chart Trend -->
            <div class="h-60 bg-pitch-950 p-4 rounded-xl border border-pitch-750">
                <canvas id="playerTrendChart"></canvas>
            </div>
        <?php endif; ?>

        <!-- Detailed Evaluation Sheet Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300 font-mono">
                <thead class="bg-pitch-950 text-emerald-400/80 uppercase font-bold border-b border-pitch-750">
                    <tr>
                        <th class="py-3 px-4 font-sans">Indikator</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <?php foreach ($displayColumns as $label => $_field): ?>
                            <th class="py-3 px-3 text-center"><?= esc($label) ?></th>
                        <?php endforeach; ?>
                        <th class="py-3 px-4 text-center text-emerald-400 font-bold">Average</th>
                        <th class="py-3 px-4 font-sans">Catatan Pelatih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pitch-800">
                    <?php if (!empty($player['stats'])): ?>
                        <?php foreach ($player['stats'] as $st): ?>
                            <?php $legacyGoalkeeperRow = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($st, $player['posisi']); ?>
                            <tr class="hover:bg-pitch-850/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-white"><?= esc($st['indikator']) ?></td>
                                <td class="py-3 px-4 text-slate-400"><?= $st['tanggal'] ? date('d/m/Y', strtotime($st['tanggal'])) : '-' ?></td>
                                <?php foreach ($displayColumns as $label => $field): ?>
                                    <td class="py-3 px-3 text-center tabular-nums"><?= $legacyGoalkeeperRow ? '—' : number_format((float)($st[$field] ?? 0), 0) ?></td>
                                <?php endforeach; ?>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded bg-pitch-950 border border-emerald-500/40 text-emerald-400 font-bold tabular-nums">
                                        <?= number_format((float)$st['average'], 0) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-400 font-sans italic max-w-xs truncate" title="<?= esc($st['catatan']) ?>">
                                    <?= esc($st['catatan'] ?: '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($displayColumns) + 4 ?>" class="py-6 text-center text-slate-500 font-sans">Belum ada evaluasi tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Radar Chart Setup (Emerald Pitch styling)
    const radarCtx = document.getElementById('playerRadarChart').getContext('2d');
    const radarSnapshots = <?= json_encode($radarSnapshots, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: <?= json_encode($radarData['labels']) ?>,
            datasets: [{
                label: '<?= esc($player['nama']) ?>',
                data: <?= json_encode($radarData['values']) ?>,
                backgroundColor: 'rgba(37, 99, 235, 0.22)',
                borderColor: '#2563EB',
                borderWidth: 2.5,
                pointBackgroundColor: '#60A5FA',
                pointBorderColor: '#FFFFFF',
                pointRadius: 3.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(100, 116, 139, 0.25)' },
                    grid: { color: 'rgba(100, 116, 139, 0.25)' },
                    pointLabels: {
                        color: '#94A3B8',
                        font: { family: 'Plus Jakarta Sans', size: 10, weight: 'bold' }
                    },
                    ticks: {
                        backdropColor: 'transparent',
                        color: '#475569',
                        stepSize: 25,
                        min: 0,
                        max: 100
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    const radarPeriodSelect = document.getElementById('radarPeriodSelect');
    if (radarPeriodSelect) {
        const updateRadar = () => {
            const snapshot = radarSnapshots[radarPeriodSelect.value];
            if (!snapshot) return;
            radarChart.data.datasets[0].data = snapshot.values;
            radarChart.update();
            document.getElementById('playerOverallValue').textContent = snapshot.average;
            document.getElementById('playerOverallIndicator').textContent = snapshot.indicator;
            document.querySelectorAll('[data-stat-value]').forEach(value => {
                const field = value.dataset.statValue;
                value.textContent = snapshot.unavailable ? '—' : `${Math.round(snapshot.stats[field] ?? 0)}/100`;
            });
            document.querySelectorAll('[data-stat-bar]').forEach(bar => {
                const field = bar.dataset.statBar;
                const score = Math.max(0, Math.min(100, snapshot.stats[field] ?? 0));
                bar.classList.toggle('hidden', snapshot.unavailable);
                bar.firstElementChild.style.width = `${score}%`;
            });
            document.getElementById('statUnavailableNotice').classList.toggle('hidden', !snapshot.unavailable);
            document.getElementById('radarPeriodLabel').textContent = snapshot.unavailable
                ? `${snapshot.label} · Rincian stat GK lama tidak tersedia`
                : `${snapshot.label} · Skala nilai 0 - 100`;
        };
        radarPeriodSelect.addEventListener('change', updateRadar);
        updateRadar();
    }

    // 2. Trend Line Chart Setup (Emerald Pitch)
    <?php if (!empty($trendLabels)): ?>
    const trendCtx = document.getElementById('playerTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trendLabels) ?>,
            datasets: [{
                label: 'Average Score',
                data: <?= json_encode($trendAverages) ?>,
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.14)',
                borderWidth: 2,
                tension: 0.2,
                fill: true,
                pointBackgroundColor: '#60A5FA',
                pointBorderColor: '#FFFFFF',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 50,
                    max: 100,
                    grid: { color: 'rgba(100, 116, 139, 0.2)' },
                    ticks: { color: '#64748B', font: { family: 'JetBrains Mono' } }
                },
                x: {
                    grid: { color: 'rgba(100, 116, 139, 0.16)' },
                    ticks: { color: '#64748B', font: { family: 'JetBrains Mono' } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
