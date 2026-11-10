<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="p-6 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-6">
    
    <!-- Table Header with Actions (Emerald Pitch) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-pitch-800 pb-4">
        <div>
            <h3 class="font-display font-black text-lg text-white">REKAMAN STATISTIK EVALUASI</h3>
            <p class="text-xs text-emerald-200/70 mt-0.5">Seluruh rekaman penilaian performa berkala mingguan atlet</p>
        </div>

        <a href="<?= site_url('admin/stats/create') ?>" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i> Input Nilai Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="p-3 rounded-xl bg-pitch-950 border border-pitch-750/70">
        <form action="<?= site_url('admin/stats') ?>" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            
            <div class="w-full sm:w-56">
                <select name="indikator" onchange="this.form.submit()" class="w-full px-3 py-1.5 bg-pitch-900 border border-pitch-750 rounded-lg text-xs text-slate-200 focus:outline-none focus:border-emerald-400 font-mono">
                    <option value="">Semua Minggu / Indikator</option>
                    <?php foreach ($availableIndicators as $ind): ?>
                        <option value="<?= esc($ind) ?>" <?= ($selectedIndicator === $ind) ? 'selected' : '' ?>><?= esc($ind) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full sm:w-60">
                <select name="player_id" onchange="this.form.submit()" class="w-full px-3 py-1.5 bg-pitch-900 border border-pitch-750 rounded-lg text-xs text-slate-200 focus:outline-none focus:border-emerald-400 font-mono">
                    <option value="">Semua Pemain</option>
                    <?php foreach ($allPlayers as $pl): ?>
                        <option value="<?= $pl['id'] ?>" <?= ($selectedPlayerId == $pl['id']) ? 'selected' : '' ?>>
                            <?= esc($pl['nama']) ?> (<?= esc($pl['posisi']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-pitch-800 hover:bg-pitch-750 text-slate-200 text-xs font-semibold transition-colors">Filter</button>
                <?php if ($selectedIndicator || $selectedPlayerId): ?>
                    <a href="<?= site_url('admin/stats') ?>" class="p-1.5 rounded-lg bg-pitch-850 text-slate-400 hover:text-white text-xs border border-pitch-750 transition-colors" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Stats Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-300 font-mono">
            <thead class="bg-pitch-950 text-emerald-400/80 uppercase font-bold border-b border-pitch-800">
                <tr>
                    <th class="py-3 px-4 font-sans">Atlet</th>
                    <th class="py-3 px-4">Indikator</th>
                    <th class="py-3 px-4">Atribut (0-100)</th>
                    <th class="py-3 px-4 text-center text-emerald-400 font-bold">Average</th>
                    <th class="py-3 px-4 text-right font-sans">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pitch-800">
                <?php if (!empty($stats)): ?>
                    <?php foreach ($stats as $st): ?>
                        <?php $legacyGoalkeeperRow = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($st, $st['posisi']); ?>
                        <tr class="hover:bg-pitch-850/50 transition-colors">
                            <td class="py-3 px-4 font-sans">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded bg-pitch-950 border border-pitch-800 p-0.5 flex-shrink-0">
                                        <img src="<?= base_url($st['foto'] ? 'uploads/players/' . $st['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <span class="font-bold text-white block leading-none">
                                            <?= esc($st['player_nama']) ?>
                                            <?php if ($st['is_captain']): ?>
                                                <span class="text-[9px] px-1 rounded bg-amber-500/20 text-amber-300 font-mono ml-0.5">C</span>
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-[10px] text-emerald-300/70 font-mono block mt-0.5">#<?= esc($st['nomor_punggung']) ?> • <?= esc($st['posisi']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-300"><?= esc($st['indikator']) ?></td>
                            <td class="py-3 px-4 text-[10px] text-slate-400 leading-relaxed">
                                <?php foreach (\App\Models\PlayerStatModel::getDisplayColumns($st['posisi']) as $label => $field): ?>
                                    <span class="inline-block mr-1.5 whitespace-nowrap"><?= esc($label) ?> <strong class="text-slate-200"><?= $legacyGoalkeeperRow ? '—' : number_format((float)($st[$field] ?? 0), 0) ?></strong></span>
                                <?php endforeach; ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded bg-pitch-950 border border-emerald-500/40 text-emerald-400 font-bold tabular-nums">
                                    <?= number_format((float)$st['average'], 0) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-sans">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= site_url('admin/stats/edit/' . $st['id']) ?>" class="p-1.5 rounded bg-pitch-800 text-sky-400 hover:bg-sky-500/20 text-xs transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button onclick="confirmDeleteStat(<?= $st['id'] ?>, '<?= esc($st['player_nama']) ?>', '<?= esc($st['indikator']) ?>')" class="p-1.5 rounded bg-pitch-800 text-red-400 hover:bg-red-500/20 text-xs transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="py-6 text-center text-slate-500 font-sans">Belum ada rekaman statistik.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDeleteStat(id, playerName, indicator) {
    Swal.fire({
        title: 'Hapus Penilaian?',
        text: `Hapus nilai evaluasi "${playerName}" untuk "${indicator}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#2563EB',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        background: '#FFFFFF',
        color: '#0F172A',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= site_url('admin/stats/delete/') ?>' + id;
        }
    });
}
</script>
<?= $this->endSection() ?>
