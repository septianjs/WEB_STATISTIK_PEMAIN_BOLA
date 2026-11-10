<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<?php
$latest = $player['latest_stat'] ?? null;
$metricColumns = $displayColumns;
$hasLegacyGoalkeeperStats = $latest && \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($latest, $player['posisi']);
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="font-display font-black text-lg text-white">STATISTIK PEMAIN</h3>
            <p class="text-xs text-slate-400 mt-1">Performa dan riwayat evaluasi <?= esc($player['nama']) ?></p>
        </div>
        <a href="<?= site_url('admin/players') ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-pitch-800 text-slate-300 hover:text-white text-xs font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Pemain
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="admin-panel rounded-2xl p-6 space-y-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-pitch-950 border border-pitch-750 p-1.5 shrink-0">
                    <img src="<?= base_url($player['foto'] ? 'uploads/players/' . $player['foto'] : 'uploads/players/default.svg') ?>" alt="<?= esc($player['nama']) ?>" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="font-display font-bold text-lg text-white"><?= esc($player['nama']) ?></h2>
                    <p class="text-xs text-slate-400">#<?= esc($player['nomor_punggung'] ?? '-') ?> · <?= esc($player['posisi']) ?></p>
                    <?php if ($player['is_captain']): ?>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 text-[10px] font-bold">KETUA PEMAIN</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-pitch-950 border border-pitch-750 text-center">
                <span class="text-[10px] font-mono text-emerald-400 uppercase tracking-wider">Rata-rata Overall</span>
                <div id="playerOverallValue" class="font-display font-black text-4xl text-emerald-400 mt-1">
                    <?= $latest ? number_format((float) $latest['average'], 0) : '-' ?>
                </div>
                <span id="playerOverallIndicator" class="text-xs text-slate-400"><?= esc($latest['indikator'] ?? 'Belum ada evaluasi') ?></span>
            </div>

            <?php if ($latest): ?>
                <div class="space-y-3">
                    <?php foreach ($metricColumns as $label => $field): ?>
                        <?php $score = (float) ($latest[$field] ?? 0); ?>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-slate-300"><?= esc($label) ?></span>
                                <span data-stat-value="<?= esc($field) ?>" class="font-mono text-white"><?= $hasLegacyGoalkeeperStats ? '—' : number_format($score, 0) . '/100' ?></span>
                            </div>
                            <div class="h-1.5 rounded bg-pitch-800 overflow-hidden <?= $hasLegacyGoalkeeperStats ? 'hidden' : '' ?>" data-stat-bar="<?= esc($field) ?>">
                                <div class="h-full bg-emerald-500 rounded" style="width: <?= min(100, max(0, $score)) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="statUnavailableNotice" class="text-center text-[10px] text-amber-600 <?= $hasLegacyGoalkeeperStats ? '' : 'hidden' ?>">Stat GK lama belum memiliki rincian.</p>
                <?php if (!empty($latest['catatan'])): ?>
                    <div class="p-3 rounded-xl bg-pitch-950 border border-pitch-750 text-xs text-slate-300">
                        <span class="block text-[10px] text-emerald-400 font-bold uppercase mb-1">Catatan Pelatih</span>
                        <?= esc($latest['catatan']) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>

        <section class="admin-panel rounded-2xl p-6 lg:col-span-2">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <div>
                    <h3 class="font-display font-bold text-base text-white">Diagram Atribut</h3>
                    <p id="radarPeriodLabel" class="text-xs text-slate-400 mt-1">Skala nilai 0 sampai 100</p>
                </div>
                <?php if (!empty($radarSnapshots)): ?>
                    <label class="text-xs text-slate-500">
                        <span class="block mb-1">Pilih minggu evaluasi</span>
                        <select id="radarPeriodSelect" class="px-3 py-2 rounded-lg bg-pitch-950 border border-pitch-750 text-xs text-white">
                            <?php foreach ($radarSnapshots as $statId => $snapshot): ?>
                                <option value="<?= esc($statId) ?>" <?= (int) $statId === (int) array_key_last($radarSnapshots) ? 'selected' : '' ?>>
                                    <?= esc($snapshot['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <?php endif; ?>
            </div>
            <?php if ($latest): ?>
                <div class="h-80"><canvas id="playerRadarChart"></canvas></div>
            <?php else: ?>
                <div class="h-80 flex items-center justify-center text-sm text-slate-500">Belum ada data statistik untuk ditampilkan.</div>
            <?php endif; ?>
        </section>
    </div>

    <section class="admin-panel rounded-2xl p-6 space-y-5">
        <div>
            <h3 class="font-display font-bold text-base text-white">Riwayat & Tren Evaluasi</h3>
            <p class="text-xs text-slate-400 mt-1">Rekaman statistik pemain dari setiap periode evaluasi</p>
        </div>

        <?php if (!empty($trendLabels)): ?>
            <div class="h-64 p-3 rounded-xl bg-pitch-950 border border-pitch-750">
                <canvas id="playerTrendChart"></canvas>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300 font-mono">
                <thead class="bg-pitch-950 text-emerald-400 uppercase border-b border-pitch-800">
                    <tr>
                        <th class="py-3 px-3">Indikator</th>
                        <th class="py-3 px-3">Tanggal</th>
                        <?php foreach ($displayColumns as $label => $_field): ?>
                            <th class="py-3 px-2 text-center"><?= esc($label) ?></th>
                        <?php endforeach; ?>
                        <th class="py-3 px-3 text-center">Rata-rata</th>
                        <th class="py-3 px-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pitch-800">
                    <?php foreach ($player['stats'] as $stat): ?>
                        <?php $legacyGoalkeeperRow = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($stat, $player['posisi']); ?>
                        <tr>
                            <td class="py-3 px-3 font-bold text-white"><?= esc($stat['indikator']) ?></td>
                            <td class="py-3 px-3"><?= $stat['tanggal'] ? date('d/m/Y', strtotime($stat['tanggal'])) : '-' ?></td>
                            <?php foreach ($displayColumns as $label => $field): ?>
                                <td class="py-3 px-2 text-center"><?= $legacyGoalkeeperRow ? '—' : number_format((float)($stat[$field] ?? 0), 0) ?></td>
                            <?php endforeach; ?>
                            <td class="py-3 px-3 text-center font-bold text-emerald-400"><?= number_format((float) $stat['average'], 0) ?></td>
                            <td class="py-3 px-3 text-slate-400"><?= esc($stat['catatan'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($player['stats'])): ?>
                        <tr><td colspan="<?= count($displayColumns) + 4 ?>" class="py-6 text-center text-slate-500">Belum ada evaluasi tersimpan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($latest): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const radarCanvas = document.getElementById('playerRadarChart');
const radarSnapshots = <?= json_encode($radarSnapshots, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
const radarChart = new Chart(radarCanvas, {
    type: 'radar',
    data: {
        labels: <?= json_encode($radarData['labels']) ?>,
        datasets: [{
            data: <?= json_encode($radarData['values']) ?>,
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.18)',
            borderWidth: 2,
            pointBackgroundColor: '#2563EB',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                min: 0,
                max: 100,
                ticks: { stepSize: 20, backdropColor: 'transparent', color: '#64748B' },
                grid: { color: 'rgba(100, 116, 139, 0.25)' },
                angleLines: { color: 'rgba(100, 116, 139, 0.25)' },
                pointLabels: { color: '#475569' }
            }
        },
        plugins: { legend: { display: false } }
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
            : `${snapshot.label} · Skala nilai 0 sampai 100`;
    };
    radarPeriodSelect.addEventListener('change', updateRadar);
    updateRadar();
}
<?php if (!empty($trendLabels)): ?>
new Chart(document.getElementById('playerTrendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($trendLabels) ?>,
        datasets: [{
            label: 'Rata-rata',
            data: <?= json_encode($trendAverages) ?>,
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.14)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { min: 0, max: 100, grid: { color: 'rgba(100, 116, 139, 0.2)' }, ticks: { color: '#64748B' } },
            x: { grid: { color: 'rgba(100, 116, 139, 0.16)' }, ticks: { color: '#64748B' } }
        },
        plugins: { legend: { display: false } }
    }
});
<?php endif; ?>
</script>
<?php endif; ?>
<?= $this->endSection() ?>
