<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero & Metric Summary Ribbon (Emerald Pitch) -->
<section class="border-b border-pitch-750/80 bg-pitch-900 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-mono font-bold bg-pitch-800 text-emerald-400 border border-pitch-750 tracking-wider uppercase mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Database Tim Sepak Bola
                </span>
                <h1 class="font-display font-black text-2xl sm:text-3xl text-white tracking-tight leading-tight">
                    STATISTIK & EVALUASI PERFORMA PEMAIN
                </h1>
                <p class="text-xs sm:text-sm text-emerald-200/70 mt-1 max-w-2xl">
                    Transparansi penilaian teknik, daya tahan fisik, serta progres performa berkala mingguan seluruh pemain tim.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?= site_url('leaderboard') ?>" class="px-3.5 py-2 rounded-lg bg-pitch-800 hover:bg-pitch-750 text-emerald-300 border border-pitch-700 text-xs font-semibold tracking-wide transition-colors">
                    Lihat Leaderboard &rarr;
                </a>
            </div>
        </div>

        <!-- 4 Key Telemetry Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-pitch-750/80 pt-6">
            
            <div class="metric-card p-3 rounded-2xl bg-pitch-850">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Total Skuad</span>
                <div class="font-display font-black text-2xl text-white mt-1 tabular-nums">
                    <?= esc($statsSummary['totalPlayers']) ?> <span class="text-xs font-normal text-slate-400 font-sans">Pemain</span>
                </div>
            </div>

            <div class="metric-card p-3 rounded-2xl bg-pitch-850">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Rata-Rata Tim</span>
                <div class="font-display font-black text-2xl text-emerald-400 mt-1 tabular-nums">
                    <?= esc($statsSummary['teamAverage']) ?> <span class="text-xs font-normal text-slate-400 font-sans">/ 100</span>
                </div>
            </div>

            <div class="metric-card p-3 rounded-2xl bg-pitch-850">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Rating Tertinggi</span>
                <div class="font-display font-black text-2xl text-emerald-300 mt-1 tabular-nums">
                    <?= esc($statsSummary['highestAverage']) ?> <span class="text-xs font-normal text-slate-400 font-sans">/ 100</span>
                </div>
                <span class="text-[11px] text-emerald-200/80 truncate block mt-0.5"><?= esc($statsSummary['topPlayerName']) ?></span>
            </div>

            <div class="metric-card p-3 rounded-2xl bg-pitch-850">
                <span class="text-[10px] font-mono text-emerald-400/80 uppercase tracking-widest block">Periode Evaluasi</span>
                <div class="font-display font-black text-2xl text-sky-400 mt-1 tabular-nums">
                    <?= count($availableIndicators) ?> <span class="text-xs font-normal text-slate-400 font-sans">Minggu</span>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Main Content Area -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Captain Dossier (Jose - No. 10) -->
    <?php if ($captain): ?>
        <?php $capStat = $captain['latest_stat']; ?>
        <section class="editorial-panel rounded-2xl p-6 sm:p-7 border-l-4 border-l-emerald-400 shadow-lg">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                
                <!-- Left: Profile & Description -->
                <div class="flex items-start gap-5 flex-1">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-pitch-900 border border-pitch-700 p-1 flex-shrink-0 relative">
                        <?php $capFoto = $captain['foto'] ? 'uploads/players/' . $captain['foto'] : 'uploads/players/jose.svg'; ?>
                        <img src="<?= base_url($capFoto) ?>" alt="<?= esc($captain['nama']) ?>" class="w-full h-full object-contain">
                        <span class="absolute -bottom-2 -right-2 px-1.5 py-0.5 rounded bg-amber-500 text-slate-950 font-mono font-black text-[10px]">C</span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-mono font-bold text-[10px] uppercase tracking-wider">
                                KETUA PEMAIN / KAPTEN
                            </span>
                            <span class="text-slate-400 text-xs font-mono">No. #<?= esc($captain['nomor_punggung']) ?></span>
                        </div>
                        <h2 class="font-display font-black text-2xl text-white">
                            <?= esc($captain['nama']) ?>
                            <span class="text-emerald-300/80 text-base font-normal">(<?= esc($captain['posisi']) ?>)</span>
                        </h2>
                        <p class="text-xs text-slate-300 mt-1 max-w-xl leading-relaxed">
                            <?= esc($captain['bio'] ?: 'Ketua pemain (kapten tim). Bertanggung jawab memimpin ritme permainan dan menjadi contoh kedisiplinan bagi seluruh anggota tim.') ?>
                        </p>
                    </div>
                </div>

                <!-- Right: Stats Telemetry Strip -->
                <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-4 border-t lg:border-t-0 lg:border-l border-pitch-750/80 pt-4 lg:pt-0 lg:pl-6">
                    
                    <?php
                        $captainColumns = \App\Models\PlayerStatModel::getDisplayColumns($captain['posisi']);
                        $captainHasLegacyGoalkeeperStats = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($capStat, $captain['posisi']);
                    ?>
                    <div>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 text-center">
                            <?php foreach ($captainColumns as $label => $field): ?>
                                <div class="p-2 rounded-lg bg-pitch-900 border border-pitch-750">
                                    <span class="text-[9px] font-mono text-emerald-400/80 uppercase block"><?= esc($label) ?></span>
                                    <span class="font-mono font-bold text-sm text-white tabular-nums"><?= $captainHasLegacyGoalkeeperStats ? '—' : number_format((float)($capStat[$field] ?? 0), 0) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Overall Score Box -->
                    <div class="p-3 rounded-xl bg-pitch-900 border border-emerald-500/40 text-center flex-shrink-0 min-w-[110px]">
                        <span class="text-[9px] font-mono font-bold text-emerald-400 uppercase block tracking-wider">AVERAGE</span>
                        <span class="font-mono font-black text-2xl text-emerald-400 tabular-nums leading-tight">
                            <?= number_format((float)($capStat['average'] ?? 100), 0) ?>
                        </span>
                        <span class="text-[10px] text-emerald-300/70 font-mono block"><?= esc($capStat['indikator'] ?? 'Minggu 1') ?></span>
                    </div>

                    <a href="<?= site_url('player/' . $captain['id']) ?>" class="px-4 py-3 rounded-xl bg-pitch-800 hover:bg-pitch-750 text-white border border-pitch-700 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                        <span>Detail</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>

                </div>

            </div>
        </section>
    <?php endif; ?>

    <!-- Filter & Toolbar -->
    <div class="p-4 rounded-xl bg-pitch-900 border border-pitch-750">
        <form action="<?= site_url('/') ?>" method="GET" class="flex flex-col md:flex-row items-center gap-3">
            
            <!-- Search -->
            <div class="w-full md:flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-400/60">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="q" value="<?= esc($search ?? '') ?>" placeholder="Cari nama pemain atau nomor punggung..." class="w-full pl-9 pr-4 py-2 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 transition-colors">
            </div>

            <!-- Indicator (Week) -->
            <div class="w-full md:w-56">
                <select name="indikator" onchange="this.form.submit()" class="w-full px-3 py-2 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-emerald-400 transition-colors">
                    <option value="">Semua Minggu (Data Terbaru)</option>
                    <?php foreach ($availableIndicators as $ind): ?>
                        <option value="<?= esc($ind) ?>" <?= ($selectedIndicator === $ind) ? 'selected' : '' ?>>
                            📅 <?= esc($ind) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Position -->
            <div class="w-full md:w-48">
                <select name="posisi" onchange="this.form.submit()" class="w-full px-3 py-2 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-emerald-400 transition-colors">
                    <option value="Semua">Semua Posisi</option>
                    <?php foreach ($positions as $position): ?>
                        <option value="<?= esc($position) ?>" <?= ($selectedPosisi === $position) ? 'selected' : '' ?>>
                            <?= esc($position) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit / Reset -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-4 py-2 rounded-lg bg-pitch-800 hover:bg-pitch-750 border border-pitch-700 text-xs font-semibold text-emerald-300 transition-colors">
                    Filter
                </button>
                <?php if ($search || $selectedIndicator || ($selectedPosisi && $selectedPosisi !== 'Semua')): ?>
                    <a href="<?= site_url('/') ?>" class="p-2 rounded-lg bg-pitch-850 hover:bg-pitch-800 text-slate-400 hover:text-white text-xs border border-pitch-750 transition-colors" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Section Subheading -->
    <div class="flex items-center justify-between border-b border-pitch-750/80 pb-3">
        <div>
            <h2 class="font-display font-bold text-lg text-white tracking-tight">
                DAFTAR SKUAD & MATRIKS NILAI
            </h2>
            <p class="text-xs text-emerald-300/70">
                <?= $selectedIndicator ? 'Statistik periode ' . esc($selectedIndicator) : 'Menampilkan statistik evaluasi terkini' ?>
            </p>
        </div>
        <span class="text-xs text-emerald-400/80 font-mono">Total: <strong class="text-white"><?= count($players) ?></strong> atlet</span>
    </div>

    <!-- Players Roster Grid -->
    <?php if (!empty($players)): ?>
        <div class="space-y-8">
            <?php foreach ($playersByPosition as $positionGroup => $groupPlayers): ?>
                <section aria-labelledby="roster-<?= esc(url_title($positionGroup), 'attr') ?>">
                    <div class="flex items-center justify-between border-b border-pitch-750/80 pb-2 mb-4">
                        <h3 id="roster-<?= esc(url_title($positionGroup), 'attr') ?>" class="font-display font-bold text-base text-white">
                            <?= esc($positionGroup) ?>
                        </h3>
                        <span class="text-[10px] font-mono text-emerald-400/80"><?= count($groupPlayers) ?> pemain</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <?php foreach ($groupPlayers as $p): ?>
                            <?php 
                    $avg = (float)($p['average'] ?? 0);
                    $fotoSrc = $p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg';
                    $dominantFoot = strtolower(trim((string)($p['kaki_terkuat'] ?? 'kanan')));
                    $rightFootDominant = in_array($dominantFoot, ['kanan', 'right'], true);
                ?>
                
                <article class="player-card">
                    <div class="player-card-inner">
                        <div class="player-card-art">
                            <img src="<?= base_url($fotoSrc) ?>" alt="<?= esc($p['nama']) ?>" class="player-card-photo">
                            <div class="player-card-rating">
                                <span class="player-card-overall"><?= $avg > 0 ? number_format($avg, 0) : '--' ?></span>
                                <span class="player-card-position"><?= esc(\App\Models\PlayerStatModel::getPositionCode($p['posisi'])) ?></span>
                            </div>
                            <?php if ($p['is_captain']): ?>
                                <span class="player-card-captain"><i class="fa-solid fa-crown"></i> KAPTEN</span>
                            <?php endif; ?>
                            <div class="player-card-name">
                                <span class="player-card-shirt">#<?= esc($p['nomor_punggung'] ?? '-') ?></span>
                                <h3><?= esc($p['nama']) ?></h3>
                            </div>
                        </div>

                        <div class="player-card-stats">
                            <?php $hasLegacyGoalkeeperStats = \App\Models\PlayerStatModel::isLegacyGoalkeeperStat($p, $p['posisi']); ?>
                            <?php foreach (\App\Models\PlayerStatModel::getDisplayColumns($p['posisi']) as $label => $field): ?>
                                <div class="player-card-stat">
                                    <span><?= $label ?></span>
                                    <strong><?= $hasLegacyGoalkeeperStats ? '—' : number_format((float)($p[$field] ?? 0), 0) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="player-card-footer">
                            <div class="player-card-feet" aria-label="Kaki dominan: <?= $rightFootDominant ? 'kanan' : 'kiri' ?>">
                                <?php foreach (['kiri' => !$rightFootDominant, 'kanan' => $rightFootDominant] as $footSide => $isDominant): ?>
                                    <span class="foot-indicator <?= $isDominant ? 'dominant-foot' : 'muted-foot' ?>" title="Kaki <?= $footSide ?><?= $isDominant ? ' (dominan)' : '' ?>">
                                        <svg class="<?= $footSide === 'kiri' ? '-scale-x-100' : '' ?>" viewBox="0 0 24 32" fill="currentColor" aria-hidden="true">
                                            <ellipse cx="12" cy="3.5" rx="2.1" ry="2.6"/>
                                            <ellipse cx="7.5" cy="5" rx="1.8" ry="2.2"/>
                                            <ellipse cx="16.5" cy="5" rx="1.8" ry="2.2"/>
                                            <ellipse cx="4.5" cy="7.5" rx="1.5" ry="1.9"/>
                                            <ellipse cx="19.5" cy="7.5" rx="1.5" ry="1.9"/>
                                            <path d="M8 8.5c-1.6 2.1-2.2 5.2-2.8 8.2-.7 3.2-2.5 6-2.4 8.9.1 3.1 2.8 4.7 6.1 4.8 4.2.2 9.5-2.2 11.1-5.7 1.2-2.7-.7-5.2-1.8-7.9-1.2-3-1.7-6.1-3.1-8.3-1.6-2.4-5.3-2.5-7.1 0Z"/>
                                        </svg>
                                        <small><?= strtoupper($footSide) ?></small>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <span class="player-card-ball"><i class="fa-solid fa-futbol"></i></span>
                            <a href="<?= site_url('player/' . $p['id']) ?>" class="player-card-link flex items-center gap-1" aria-label="Lihat rapor lengkap <?= esc($p['nama']) ?>">
                                <span>RAPOR</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="editorial-panel rounded-2xl p-10 text-center max-w-md mx-auto">
            <i class="fa-solid fa-user-slash text-2xl text-slate-500 mb-2 block"></i>
            <h3 class="font-display font-bold text-sm text-white">Tidak Ada Data Ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1">Coba atur ulang filter indikator atau kata kunci pencarian Anda.</p>
            <a href="<?= site_url('/') ?>" class="inline-block mt-3 px-3 py-1.5 rounded-lg bg-pitch-800 text-xs font-semibold text-white">Reset Filter</a>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
