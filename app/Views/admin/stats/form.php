<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = !empty($stat);
    $actionUrl = $isEdit ? site_url('admin/stats/update/' . $stat['id']) : site_url('admin/stats/store');
    $selectedPlayer = null;
    foreach ($allPlayers as $candidate) {
        if ((int) $candidate['id'] === (int) old('player_id', $selectedPlayerId)) {
            $selectedPlayer = $candidate;
            break;
        }
    }
?>

<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <a href="<?= site_url('admin/stats') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300/80 hover:text-white">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Rekaman Nilai
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="p-3.5 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-xs space-y-1">
            <?php foreach ($errors as $err): ?><p><i class="fa-solid fa-circle-xmark mr-1"></i> <?= esc($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 p-6 sm:p-7 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-6">
            <div class="border-b border-pitch-800 pb-4">
                <h3 class="font-display font-black text-xl text-white"><?= $isEdit ? 'EDIT EVALUASI STATISTIK' : 'INPUT NILAI EVALUASI ATLET' ?></h3>
                <p class="text-xs text-emerald-200/70 mt-1">Isi nilai stat (0-100). Overall dihitung otomatis berdasarkan bobot posisi.</p>
            </div>

            <form action="<?= $actionUrl ?>" method="POST" id="statForm" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Atlet <span class="text-red-400">*</span></label>
                    <select name="player_id" id="playerSelect" required class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white">
                        <option value="">-- Pilih Nama Pemain --</option>
                        <?php foreach ($allPlayers as $pl): ?>
                            <option value="<?= $pl['id'] ?>"
                                data-role="<?= esc(\App\Models\PlayerStatModel::getPositionCode($pl['posisi'])) ?>"
                                <?= (old('player_id', $selectedPlayerId) == $pl['id']) ? 'selected' : '' ?>>
                                <?= esc($pl['nama']) ?> (<?= esc($pl['posisi']) ?> #<?= esc($pl['nomor_punggung']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Indikator Penilaian <span class="text-red-400">*</span></label>
                        <input type="text" name="indikator" value="<?= old('indikator', $stat['indikator'] ?? 'Minggu 1') ?>" required maxlength="50" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white">
                        <div class="flex flex-wrap gap-1.5 mt-2 font-mono">
                            <?php for ($week = 1; $week <= 4; $week++): ?>
                                <button type="button" class="px-2 py-0.5 rounded bg-pitch-800 text-[10px] text-slate-300" onclick="document.querySelector('[name=indikator]').value='Minggu <?= $week ?>'">Minggu <?= $week ?></button>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Evaluasi</label>
                        <input type="date" name="tanggal" value="<?= old('tanggal', $stat['tanggal'] ?? date('Y-m-d')) ?>" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white">
                    </div>
                </div>

                <div id="outfieldInputs" class="space-y-4">
                    <?php foreach ($fieldStatGroups as $groupKey => $group): ?>
                        <section class="stat-group p-3 rounded-xl bg-pitch-950 border border-pitch-750" data-group="<?= esc($groupKey) ?>" data-label="<?= esc($group['label']) ?>">
                            <?php $value = old($group['field'], $stat[$group['field']] ?? 80); ?>
                            <label class="block text-xs text-slate-300">
                                <span class="flex items-center justify-between mb-2">
                                    <strong class="text-emerald-300"><?= esc($group['label']) ?></strong>
                                    <output data-stat-value class="font-mono font-bold text-white"><?= esc($value) ?></output>
                                </span>
                                <input type="range" name="<?= esc($group['field']) ?>" min="0" max="100" step="1"
                                    value="<?= esc($value) ?>" required aria-label="<?= esc($group['label']) ?>"
                                    class="w-full accent-emerald-400 cursor-pointer">
                            </label>
                        </section>
                    <?php endforeach; ?>
                </div>

                <div id="goalkeeperInputs" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <?php foreach ($goalkeeperStats as $field => $goalkeeperStat): ?>
                        <?php $value = old($field, $stat[$field] ?? 80); ?>
                        <label class="block p-3 rounded-xl bg-pitch-950 border border-pitch-750 text-xs text-slate-300">
                            <span class="flex items-center justify-between mb-2">
                                <strong class="text-emerald-300"><?= esc($goalkeeperStat['code']) ?></strong>
                                <output data-stat-value class="font-mono font-bold text-white"><?= esc($value) ?></output>
                            </span>
                            <input type="range" name="<?= esc($field) ?>" min="0" max="100" step="1"
                                value="<?= esc($value) ?>" required disabled aria-label="<?= esc($goalkeeperStat['code']) ?>"
                                class="w-full accent-emerald-400 cursor-pointer">
                        </label>
                    <?php endforeach; ?>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Evaluasi / Rekomendasi Pelatih</label>
                    <textarea name="catatan" rows="2" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white"><?= old('catatan', $stat['catatan'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-pitch-800">
                    <a href="<?= site_url('admin/stats') ?>" class="px-4 py-2 rounded-lg bg-pitch-800 text-slate-300 text-xs font-semibold">Batal</a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase">
                        <?= $isEdit ? 'Perbarui Nilai' : 'Simpan & Hitung Overall' ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 space-y-5">
            <div class="p-6 rounded-2xl bg-pitch-900 border border-pitch-750 text-center space-y-4">
                <span class="text-[10px] font-mono font-bold text-emerald-400/80 uppercase tracking-widest block">Kalkulasi Otomatis</span>
                <div>
                    <span id="liveAverage" class="font-mono font-black text-5xl text-emerald-400 tabular-nums block">80</span>
                    <span class="text-[10px] font-mono text-emerald-300/70 uppercase">Overall / 100</span>
                </div>
                <div id="liveTierBadge" class="inline-block px-3 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono font-bold text-xs"></div>
                <div class="p-3 rounded-xl bg-pitch-950 border border-pitch-750 text-xs text-slate-400 font-mono text-left">
                    <div class="flex justify-between"><span>Rumus posisi</span><strong id="liveRole" class="text-white"><?= esc($selectedPlayer['posisi'] ?? 'Pilih pemain') ?></strong></div>
                    <div class="flex justify-between mt-1"><span>Jumlah bobot</span><strong id="liveWeight" class="text-emerald-400">100%</strong></div>
                    <p class="mt-2 text-[10px] text-slate-500">Nilai stat × bobot posisi; nilai akhir dibulatkan ke bilangan bulat.</p>
                </div>
            </div>
            <div class="p-6 rounded-2xl bg-pitch-900 border border-pitch-750">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block mb-2 text-center">Pratinjau Stat</span>
                <div class="h-60"><canvas id="liveRadarChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const roleWeights = <?= json_encode($roleWeights, JSON_THROW_ON_ERROR) ?>;
let radarChart;

function updateMode() {
    const option = document.querySelector('#playerSelect option:checked');
    const role = option ? option.dataset.role : '';
    const goalkeeper = role === 'GK';
    document.getElementById('liveRole').textContent = option && option.value ? option.textContent.trim() : 'Pilih pemain';
    document.getElementById('outfieldInputs').classList.toggle('hidden', goalkeeper || !role);
    document.getElementById('goalkeeperInputs').classList.toggle('hidden', !goalkeeper);
    document.querySelectorAll('#outfieldInputs input, #goalkeeperInputs input').forEach(input => {
        input.disabled = goalkeeper ? !input.closest('#goalkeeperInputs') : !input.closest('#outfieldInputs');
    });
    updateCalculation();
}

function updateCalculation() {
    const option = document.querySelector('#playerSelect option:checked');
    const role = option ? option.dataset.role : '';
    const weights = roleWeights[role] || {};
    const goalkeeper = role === 'GK';
    const groups = {};
    const labels = [];
    const values = [];
    document.querySelectorAll(goalkeeper ? '#goalkeeperInputs label' : '#outfieldInputs .stat-group').forEach(group => {
        const input = group.querySelector('input:not(:disabled)');
        const key = goalkeeper ? group.querySelector('input').name : group.dataset.group;
        const score = Number(input?.value) || 0;
        const output = group.querySelector('[data-stat-value]');
        if (output && input) output.value = score;
        groups[key] = score;
        labels.push(goalkeeper ? group.querySelector('strong').textContent : group.dataset.label);
        values.push(score);
    });
    const weightTotal = Object.values(weights).reduce((sum, value) => sum + value, 0);
    const weightedTotal = Object.entries(weights).reduce((sum, [key, weight]) => sum + (groups[key] || 0) * weight, 0);
    const average = weightTotal ? Math.round(weightedTotal / weightTotal) : 0;
    document.getElementById('liveAverage').textContent = average;
    document.getElementById('liveWeight').textContent = `${weightTotal}%`;

    const badge = document.getElementById('liveTierBadge');
    badge.textContent = average >= 95 ? 'S TIER - ELITE' : average >= 85 ? 'A TIER - EXCELLENT' : average >= 75 ? 'B TIER - GOOD' : average >= 60 ? 'C TIER - AVERAGE' : 'D TIER - NEEDS IMPROVEMENT';

    if (radarChart) {
        radarChart.data.labels = labels;
        radarChart.data.datasets[0].data = values;
        radarChart.update();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('playerSelect').addEventListener('change', updateMode);
    document.querySelectorAll('#statForm input[type=range]').forEach(input => input.addEventListener('input', updateCalculation));
    const context = document.getElementById('liveRadarChart').getContext('2d');
    radarChart = new Chart(context, {
        type: 'radar',
        data: { labels: [], datasets: [{ data: [], backgroundColor: 'rgba(37, 99, 235, 0.22)', borderColor: '#2563EB', borderWidth: 2, pointBackgroundColor: '#60A5FA' }] },
        options: { responsive: true, maintainAspectRatio: false, scales: { r: { min: 0, max: 100, ticks: { stepSize: 20, backdropColor: 'transparent', color: '#64748B' }, pointLabels: { color: '#94A3B8' } } }, plugins: { legend: { display: false } } }
    });
    updateMode();
});
</script>
<?= $this->endSection() ?>
