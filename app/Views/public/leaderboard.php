<?= $this->extend($layout ?? 'layout/main') ?>

<?= $this->section('content') ?>

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Editorial Header (Emerald Pitch) -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-pitch-750/80 pb-6">
        <div>
            <h1 class="font-display font-black text-2xl sm:text-3xl text-white tracking-tight">
                Papan Peringkat Pemain
            </h1>
            <p class="text-xs sm:text-sm text-emerald-200/70 mt-1 max-w-xl">
                Peringkat pemain terbaik berdasarkan hasil evaluasi performa.
            </p>
        </div>

        <!-- Indicator Filter -->
        <form action="<?= site_url(!empty($isAdminMode) ? 'admin/leaderboard' : 'leaderboard') ?>" method="GET" class="flex items-center gap-2">
            <span class="text-xs text-emerald-400/80 font-mono">Periode:</span>
            <select name="indikator" onchange="this.form.submit()" class="px-3 py-1.5 bg-pitch-900 border border-pitch-750 rounded-lg text-xs text-white focus:outline-none focus:border-emerald-400 font-mono">
                <option value="">Semua Catatan</option>
                <?php foreach ($availableIndicators as $ind): ?>
                    <option value="<?= esc($ind) ?>" <?= ($selectedIndicator === $ind) ? 'selected' : '' ?>><?= esc($ind) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Leaderboard Grids (3 Columns, 7 Categories) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- 1. Overall Average -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-emerald-400">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">RATA-RATA TERTINGGI</h3>
                <span class="text-[10px] font-mono text-emerald-400 font-bold uppercase">Overall</span>
            </div>

            <div class="space-y-2 font-mono">
                <?php if (!empty($topOverall)): ?>
                    <?php foreach ($topOverall as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs <?= $idx === 0 ? 'text-emerald-400' : ($idx === 1 ? 'text-slate-300' : ($idx === 2 ? 'text-amber-500' : 'text-slate-500')) ?>">
                                    <?= $idx + 1 ?>
                                </span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-emerald-300 transition-colors truncate">
                                        <?= esc($p['nama']) ?> <?= $p['is_captain'] ? '<span class="text-[10px] text-amber-400 font-mono">(C)</span>' : '' ?>
                                    </p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-emerald-400 tabular-nums"><?= number_format((float)$p['average'], 0) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Shooting -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-red-500">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP SHO</h3>
                <span class="text-[10px] font-mono text-red-400 font-bold uppercase">Shooting</span>
            </div>

            <div class="space-y-2 font-mono">
                <?php if (!empty($topTendangan)): ?>
                    <?php foreach ($topTendangan as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-red-400 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-red-400 tabular-nums"><?= (int)$p['score'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 3. Passing -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-cyan-500">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP PAS</h3>
                <span class="text-[10px] font-mono text-cyan-400 font-bold uppercase">Passing</span>
            </div>

            <div class="space-y-2 font-mono">
                <?php if (!empty($topPassing)): ?>
                    <?php foreach ($topPassing as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-cyan-400 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-cyan-400 tabular-nums"><?= (int)$p['score'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. Control -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-emerald-400">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP CON</h3>
                <span class="text-[10px] font-mono text-emerald-400 font-bold uppercase">Control</span>
            </div>

            <div class="space-y-2 font-mono">
                <?php if (!empty($topControl)): ?>
                    <?php foreach ($topControl as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-emerald-300 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-emerald-400 tabular-nums"><?= (int)$p['score'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 5. Agility -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-purple-500">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP AGI</h3>
                <span class="text-[10px] font-mono text-purple-400 font-bold uppercase">Agility</span>
            </div>

            <div class="space-y-2 font-mono">
                <?php if (!empty($topAgility)): ?>
                    <?php foreach ($topAgility as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-purple-400 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-purple-400 tabular-nums"><?= (int)$p['score'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 6. Physical -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-emerald-500">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP PHY</h3>
                <span class="text-[10px] font-mono text-emerald-400 font-bold uppercase">Physical</span>
            </div>
            <div class="space-y-2 font-mono">
                <?php if (!empty($topFisik)): ?>
                    <?php foreach ($topFisik as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-emerald-300 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-emerald-400 tabular-nums"><?= (int)$p['score'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 7. Defending -->
        <div class="editorial-panel rounded-2xl p-5 border-t-2 border-t-amber-500">
            <div class="flex items-center justify-between border-b border-pitch-750/70 pb-3 mb-4">
                <h3 class="font-display font-bold text-sm text-white">TOP DEF</h3>
                <span class="text-[10px] font-mono text-amber-400 font-bold uppercase">Defending</span>
            </div>
            <div class="space-y-2 font-mono">
                <?php if (!empty($topDefending)): ?>
                    <?php foreach ($topDefending as $idx => $p): ?>
                        <a href="<?= site_url('player/' . $p['player_id']) ?>" class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 hover:bg-pitch-850 border border-pitch-750/60 transition-colors group">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-5 text-center font-bold text-xs text-slate-500"><?= $idx + 1 ?></span>
                                <div class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 p-0.5 flex-shrink-0">
                                    <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="truncate font-sans">
                                    <p class="font-bold text-xs text-white group-hover:text-amber-300 transition-colors truncate"><?= esc($p['nama']) ?></p>
                                    <span class="text-[10px] text-emerald-300/60"><?= esc($p['posisi']) ?></span>
                                </div>
                            </div>
                            <span class="font-bold text-xs text-amber-400 tabular-nums"><?= number_format((float)$p['score'], 0) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>
