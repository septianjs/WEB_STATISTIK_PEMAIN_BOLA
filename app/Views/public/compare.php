<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Editorial Header (Emerald Pitch) -->
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-widest block mb-1">Analisis Komparatif Atlet</span>
        <h1 class="font-display font-black text-2xl sm:text-3xl text-white tracking-tight">
            KOMPARASI STATISTIK DUA PEMAIN
        </h1>
        <p class="text-xs sm:text-sm text-emerald-200/70 mt-1">
            Bandingkan distribusi atribut teknik dan kebugaran dua atlet secara langsung.
        </p>

        <!-- Selector Form -->
        <form action="<?= site_url('compare') ?>" method="GET" class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
            
            <div class="w-full sm:w-60 text-left">
                <label class="block text-[10px] font-mono font-bold text-emerald-400 uppercase mb-1">Pemain A (Emerald)</label>
                <select name="p1" onchange="this.form.submit()" class="w-full px-3 py-2 bg-pitch-900 border border-emerald-500/40 rounded-lg text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                    <?php foreach ($allPlayers as $pl): ?>
                        <option value="<?= $pl['id'] ?>" <?= ($p1_id == $pl['id']) ? 'selected' : '' ?>>
                            <?= esc($pl['nama']) ?> (<?= esc($pl['posisi']) ?>) <?= $pl['is_captain'] ? '👑' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-7 h-7 rounded-full bg-pitch-850 border border-pitch-750 flex items-center justify-center text-[10px] font-mono font-black text-emerald-400/80 mt-4 sm:mt-3">
                VS
            </div>

            <div class="w-full sm:w-60 text-left">
                <label class="block text-[10px] font-mono font-bold text-sky-400 uppercase mb-1">Pemain B (Sky)</label>
                <select name="p2" onchange="this.form.submit()" class="w-full px-3 py-2 bg-pitch-900 border border-sky-500/40 rounded-lg text-xs text-white focus:outline-none focus:border-sky-400 font-mono">
                    <?php foreach ($allPlayers as $pl): ?>
                        <option value="<?= $pl['id'] ?>" <?= ($p2_id == $pl['id']) ? 'selected' : '' ?>>
                            <?= esc($pl['nama']) ?> (<?= esc($pl['posisi']) ?>) <?= $pl['is_captain'] ? '👑' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </form>
    </div>

    <?php if ($player1 && $player2): ?>
        <?php 
            $s1 = $player1['latest_stat'] ?? [];
            $s2 = $player2['latest_stat'] ?? [];
            $avg1 = (float)($s1['average'] ?? 0);
            $avg2 = (float)($s2['average'] ?? 0);
            $columns1 = \App\Models\PlayerStatModel::getDisplayColumns($player1['posisi']);
            $columns2 = \App\Models\PlayerStatModel::getDisplayColumns($player2['posisi']);
            $metricLabels = array_values(array_unique(array_merge(
                array_keys($columns1),
                array_keys($columns2)
            )));
            $compareLabels = $metricLabels;
            $legacyGoalkeeper1 = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($s1, $player1['posisi']);
            $legacyGoalkeeper2 = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($s2, $player2['posisi']);
            $metricValues1 = [];
            $metricValues2 = [];
            foreach ($columns1 as $label => $field) {
                if (!$legacyGoalkeeper1) {
                    $metricValues1[$label] = (float)($s1[$field] ?? 0);
                }
            }
            foreach ($columns2 as $label => $field) {
                if (!$legacyGoalkeeper2) {
                    $metricValues2[$label] = (float)($s2[$field] ?? 0);
                }
            }
        ?>

        <!-- Dual Dossier & Radar Center -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            
            <!-- Player 1 Sheet (3 cols) -->
            <div class="lg:col-span-3 editorial-panel rounded-2xl p-5 text-center border-l-4 border-l-emerald-400 space-y-3">
                <div class="flex justify-between items-start text-left">
                    <div>
                        <span class="text-[9px] font-mono text-emerald-400/80 block">NO. PUNGGUNG</span>
                        <span class="font-mono font-bold text-sm text-slate-300">#<?= esc($player1['nomor_punggung']) ?></span>
                    </div>
                    <?php if ($player1['is_captain']): ?>
                        <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono text-[9px] font-bold">KAPTEN</span>
                    <?php endif; ?>
                </div>

                <div class="w-24 h-24 mx-auto rounded-xl bg-pitch-950 border border-pitch-750 p-1.5">
                    <img src="<?= base_url($player1['foto'] ? 'uploads/players/' . $player1['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                </div>

                <div>
                    <h3 class="font-display font-bold text-base text-white"><?= esc($player1['nama']) ?></h3>
                    <span class="text-xs text-emerald-300/80"><?= esc($player1['posisi']) ?></span>
                </div>

                <div class="p-2.5 rounded-lg bg-pitch-950 border border-pitch-750">
                    <span class="text-[9px] font-mono text-emerald-400/80 block">AVERAGE</span>
                    <span class="font-mono font-black text-2xl text-emerald-400 tabular-nums">
                        <?= number_format($avg1, 0) ?>
                    </span>
                </div>
            </div>

            <!-- Overlaid Radar Matrix (6 cols) -->
            <div class="lg:col-span-6 editorial-panel rounded-2xl p-5">
                <div class="text-center mb-2 font-mono text-xs font-bold">
                    <span style="color:#1E3A8A"><?= esc($player1['nama']) ?></span>
                    <span class="text-slate-500 mx-2">vs</span>
                    <span style="color:#1E3A8A"><?= esc($player2['nama']) ?></span>
                </div>
                <div class="h-72">
                    <canvas id="compareRadarChart"></canvas>
                </div>
            </div>

            <!-- Player 2 Sheet (3 cols) -->
            <div class="lg:col-span-3 editorial-panel rounded-2xl p-5 text-center border-r-4 border-r-sky-400 space-y-3">
                <div class="flex justify-between items-start text-left">
                    <div>
                        <span class="text-[9px] font-mono text-sky-400/80 block">NO. PUNGGUNG</span>
                        <span class="font-mono font-bold text-sm text-slate-300">#<?= esc($player2['nomor_punggung']) ?></span>
                    </div>
                    <?php if ($player2['is_captain']): ?>
                        <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono text-[9px] font-bold">KAPTEN</span>
                    <?php endif; ?>
                </div>

                <div class="w-24 h-24 mx-auto rounded-xl bg-pitch-950 border border-pitch-750 p-1.5">
                    <img src="<?= base_url($player2['foto'] ? 'uploads/players/' . $player2['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                </div>

                <div>
                    <h3 class="font-display font-bold text-base text-white"><?= esc($player2['nama']) ?></h3>
                    <span class="text-xs text-sky-300/80"><?= esc($player2['posisi']) ?></span>
                </div>

                <div class="p-2.5 rounded-lg bg-pitch-950 border border-pitch-750">
                    <span class="text-[9px] font-mono text-sky-400/80 block">AVERAGE</span>
                    <span class="font-mono font-black text-2xl text-sky-400 tabular-nums">
                        <?= number_format($avg2, 0) ?>
                    </span>
                </div>
            </div>

        </div>

        <!-- Metric Differential Table -->
        <div class="editorial-panel rounded-2xl p-6 sm:p-7 space-y-4">
            <h3 class="font-display font-bold text-sm text-white border-b border-pitch-750/70 pb-3">
                MATRIKS PERBANDINGAN NILAI ATRIBUT
            </h3>

            <?php $attributes = array_merge($compareLabels, ['OVERALL']); ?>

            <div class="space-y-3 font-mono">
                <?php foreach ($attributes as $label): ?>
                    <?php 
                        $isAvg = $label === 'OVERALL';
                        $val1 = $isAvg ? $avg1 : ($metricValues1[$label] ?? null);
                        $val2 = $isAvg ? $avg2 : ($metricValues2[$label] ?? null);
                        $win1 = $val1 !== null && $val2 !== null && $val1 > $val2;
                        $win2 = $val1 !== null && $val2 !== null && $val2 > $val1;
                    ?>
                    <div class="p-3 rounded-xl <?= $isAvg ? 'bg-pitch-900 border border-emerald-500/40' : 'bg-pitch-950 border border-pitch-750' ?>">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="<?= $win1 ? 'inline-flex items-center gap-1 rounded-full px-2 py-1 font-black shadow-sm' : 'text-slate-400' ?> tabular-nums"
                                <?= $win1 ? 'title="Nilai tertinggi pada atribut ini" style="color:#166534;background:#DCFCE7;box-shadow:0 0 0 1px #86EFAC"' : '' ?>>
                                <?= $val1 === null ? '—' : number_format($val1, 0) ?>
                                <?php if ($win1): ?><i class="fa-solid fa-circle-up text-base" aria-label="Nilai lebih tinggi"></i><?php endif; ?>
                            </span>

                            <span class="<?= $isAvg ? 'text-white font-sans' : 'text-slate-400 font-sans text-[11px]' ?>">
                                <?= $isAvg ? 'OVERALL' : esc($label) ?>
                            </span>

                            <span class="<?= $win2 ? 'inline-flex items-center gap-1 rounded-full px-2 py-1 font-black shadow-sm' : 'text-slate-400' ?> tabular-nums"
                                <?= $win2 ? 'title="Nilai tertinggi pada atribut ini" style="color:#166534;background:#DCFCE7;box-shadow:0 0 0 1px #86EFAC"' : '' ?>>
                                <?= $val2 === null ? '—' : number_format($val2, 0) ?>
                                <?php if ($win2): ?><i class="fa-solid fa-circle-up text-base" aria-label="Nilai lebih tinggi"></i><?php endif; ?>
                            </span>
                        </div>

                        <!-- Side by side bar -->
                        <div class="grid grid-cols-2 gap-1.5">
                            <div class="w-full bg-pitch-900 rounded h-1.5 flex justify-end overflow-hidden">
                                <div class="h-full rounded" style="width: <?= min(100, max(0, (float)($val1 ?? 0))) ?>%;background:#EF4444"></div>
                            </div>
                            <div class="w-full bg-pitch-900 rounded h-1.5 overflow-hidden">
                                <div class="h-full rounded" style="width: <?= min(100, max(0, (float)($val2 ?? 0))) ?>%;background:#EAB308"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($player1 && $player2): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('compareRadarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: <?= json_encode($metricLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
            datasets: [
                {
                    label: <?= json_encode($player1['nama'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
                    data: <?= json_encode(array_map(static fn (string $label) => $metricValues1[$label] ?? null, $metricLabels)) ?>,
                    backgroundColor: 'rgba(239, 68, 68, 0.22)',
                    borderColor: '#EF4444',
                    borderWidth: 2,
                    pointBackgroundColor: '#F87171',
                    pointRadius: 3
                },
                {
                    label: <?= json_encode($player2['nama'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
                    data: <?= json_encode(array_map(static fn (string $label) => $metricValues2[$label] ?? null, $metricLabels)) ?>,
                    backgroundColor: 'rgba(234, 179, 8, 0.25)',
                    borderColor: '#EAB308',
                    borderWidth: 2,
                    pointBackgroundColor: '#FACC15',
                    pointRadius: 3
                }
            ]
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
                legend: {
                    color: '#1E3A8A',
                    labels: { color: '#1E3A8A', font: { family: 'JetBrains Mono', size: 11, weight: 'bold' } }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
