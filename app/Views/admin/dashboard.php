<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<style>
    .dashboard-kpi-card,
    .dashboard-action-card {
        border: 1px solid transparent;
        box-shadow: 0 10px 24px rgba(51, 65, 110, 0.08);
    }
    .dashboard-kpi-card:nth-child(4n + 1),
    .dashboard-action-card:nth-child(3n + 1) {
        background: linear-gradient(135deg, #EAF3FF, #F5F8FF);
        border-color: #C7DDFB;
    }
    .dashboard-kpi-card:nth-child(4n + 2),
    .dashboard-action-card:nth-child(3n + 2) {
        background: linear-gradient(135deg, #E9F8FF, #F2FAFF);
        border-color: #BFE8F8;
    }
    .dashboard-kpi-card:nth-child(4n + 3),
    .dashboard-action-card:nth-child(3n + 3) {
        background: linear-gradient(135deg, #F0EDFF, #F8F6FF);
        border-color: #D8CCFF;
    }
    .dashboard-kpi-card:nth-child(4n + 4) {
        background: linear-gradient(135deg, #FFF4E5, #FFFAF1);
        border-color: #F7D9A8;
    }
</style>

<!-- Workstation KPI Ribbon (Emerald Pitch) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    
    <div class="dashboard-kpi-card p-5 rounded-xl">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Total Pemain</span>
            <i class="fa-solid fa-users text-emerald-400/60 text-sm"></i>
        </div>
        <div class="font-display font-black text-2xl text-white mt-1 tabular-nums"><?= (int)$totalPlayers ?></div>
        <div class="mt-3 pt-2.5 border-t border-pitch-800 text-[11px] text-slate-400 flex items-center justify-between">
            <span>Terdaftar di skuad</span>
            <a href="<?= site_url('admin/players') ?>" class="text-emerald-400 hover:underline">Kelola &rarr;</a>
        </div>
    </div>

    <div class="dashboard-kpi-card p-5 rounded-xl">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Sesi Evaluasi</span>
            <i class="fa-solid fa-clipboard-list text-emerald-400/60 text-sm"></i>
        </div>
        <div class="font-display font-black text-2xl text-sky-400 mt-1 tabular-nums"><?= (int)$totalStats ?></div>
        <div class="mt-3 pt-2.5 border-t border-pitch-800 text-[11px] text-slate-400 flex items-center justify-between">
            <span>Rekaman statistik</span>
            <a href="<?= site_url('admin/stats') ?>" class="text-sky-400 hover:underline">Daftar &rarr;</a>
        </div>
    </div>

    <div class="dashboard-kpi-card p-5 rounded-xl">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Rata-rata Tim</span>
            <i class="fa-solid fa-chart-line text-emerald-400 text-sm"></i>
        </div>
        <div class="font-display font-black text-2xl text-emerald-400 mt-1 tabular-nums"><?= number_format($teamAverage, 0) ?></div>
        <div class="mt-3 pt-2.5 border-t border-pitch-800 text-[11px] text-emerald-300/70">
            <span>Skala maksimum 100</span>
        </div>
    </div>

    <div class="dashboard-kpi-card p-5 rounded-xl">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-mono text-amber-400 uppercase tracking-widest block">Ketua Pemain</span>
            <i class="fa-solid fa-crown text-amber-400 text-sm"></i>
        </div>
        <div class="font-display font-black text-xl text-white mt-1 truncate">
            <?= $captain ? esc($captain['nama']) : 'Belum Ditentukan' ?>
        </div>
        <div class="mt-3 pt-2.5 border-t border-pitch-800 text-[11px] text-slate-400 flex items-center justify-between font-mono">
            <span>Nomor Punggung</span>
            <span class="text-emerald-400 font-bold"><?= $captain ? '#' . $captain['nomor_punggung'] : '-' ?></span>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <a href="<?= site_url('admin/stats/create') ?>" class="dashboard-action-card p-4 rounded-2xl hover:bg-pitch-850 transition-colors flex items-center gap-3.5 group shadow-sm hover:shadow-md">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div>
            <span class="font-display font-bold text-sm text-white group-hover:text-emerald-400 transition-colors block">Input Nilai Statistik</span>
            <span class="text-xs text-emerald-300/60 block mt-0.5">Penilaian mingguan otomatis terhitung</span>
        </div>
    </a>

    <a href="<?= site_url('admin/players/create') ?>" class="dashboard-action-card p-4 rounded-2xl hover:bg-pitch-850 transition-colors flex items-center gap-3.5 group shadow-sm hover:shadow-md">
        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
            <span class="font-display font-bold text-sm text-white group-hover:text-sky-400 transition-colors block">Tambah Pemain Baru</span>
            <span class="text-xs text-sky-300/60 block mt-0.5">Daftarkan atlet ke dalam skuad</span>
        </div>
    </a>

    <a href="<?= site_url('/') ?>" target="_blank" class="dashboard-action-card p-4 rounded-xl hover:bg-pitch-850 hover:border-emerald-500/50 transition-colors flex items-center gap-3.5 group">
        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </div>
        <div>
            <span class="font-display font-bold text-sm text-white group-hover:text-emerald-400 transition-colors block">Tampilan Publik Pemain</span>
            <span class="text-xs text-emerald-300/60 block mt-0.5">Pratinjau antarmuka pemain</span>
        </div>
    </a>
</div>

<!-- Two-Column Tables Feed -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- Recent 5 Evaluations (7 cols) -->
    <div class="lg:col-span-7 p-6 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-4">
        <div class="flex items-center justify-between border-b border-pitch-800 pb-3">
            <h3 class="font-display font-bold text-sm text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-emerald-400 text-xs"></i> 5 Evaluasi Nilai Terkini
            </h3>
            <a href="<?= site_url('admin/stats') ?>" class="text-xs text-emerald-400 hover:underline">Semua Evaluasi &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300 font-mono">
                <thead class="bg-pitch-950 text-emerald-400/80 uppercase font-bold border-b border-pitch-800">
                    <tr>
                        <th class="py-2.5 px-3 font-sans">Pemain</th>
                        <th class="py-2.5 px-3">Indikator</th>
                        <th class="py-2.5 px-3 text-center">Rata-Rata</th>
                        <th class="py-2.5 px-3 text-right font-sans">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pitch-800">
                    <?php if (!empty($recentStats)): ?>
                        <?php foreach ($recentStats as $st): ?>
                            <tr class="hover:bg-pitch-850/50 transition-colors">
                                <td class="py-2.5 px-3 font-sans font-bold text-white"><?= esc($st['player_nama']) ?></td>
                                <td class="py-2.5 px-3 text-slate-400"><?= esc($st['indikator']) ?></td>
                                <td class="py-2.5 px-3 text-center font-bold text-emerald-400 tabular-nums">
                                    <?= number_format((float)$st['average'], 0) ?>
                                </td>
                                <td class="py-2.5 px-3 text-right font-sans">
                                    <a href="<?= site_url('admin/stats/edit/' . $st['id']) ?>" class="text-sky-400 hover:underline text-xs">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="py-4 text-center text-slate-500 font-sans">Belum ada evaluasi nilai.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Squad Snapshot (5 cols) -->
    <div class="lg:col-span-5 p-6 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-4">
        <div class="flex items-center justify-between border-b border-pitch-800 pb-3">
            <h3 class="font-display font-bold text-sm text-white flex items-center gap-2">
                <i class="fa-solid fa-users text-emerald-400 text-xs"></i> Anggota Skuad
            </h3>
            <a href="<?= site_url('admin/players') ?>" class="text-xs text-emerald-400 hover:underline">Semua Atlet &rarr;</a>
        </div>

        <div class="space-y-2.5">
            <?php if (!empty($recentPlayers)): ?>
                <?php foreach ($recentPlayers as $p): ?>
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-pitch-950 border border-pitch-800">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded bg-pitch-900 border border-pitch-750 font-mono font-bold text-xs flex items-center justify-center text-emerald-400">
                                #<?= esc($p['nomor_punggung']) ?>
                            </span>
                            <div>
                                <span class="text-xs font-bold text-white block leading-none">
                                    <?= esc($p['nama']) ?>
                                    <?php if ($p['is_captain']): ?>
                                        <span class="text-[9px] px-1 py-0.2 rounded bg-amber-500/20 text-amber-300 font-mono ml-1">C</span>
                                    <?php endif; ?>
                                </span>
                                <span class="text-[10px] text-emerald-300/70"><?= esc($p['posisi']) ?></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="<?= site_url('admin/stats/create/' . $p['id']) ?>" class="p-1 rounded bg-pitch-800 hover:bg-emerald-500/20 text-slate-400 hover:text-emerald-400 text-xs transition-colors" title="Input Nilai">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                            <a href="<?= site_url('admin/players/edit/' . $p['id']) ?>" class="p-1 rounded bg-pitch-800 hover:bg-pitch-750 text-slate-400 hover:text-white text-xs transition-colors" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
