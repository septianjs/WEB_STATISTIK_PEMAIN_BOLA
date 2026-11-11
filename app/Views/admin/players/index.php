<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<style>
    .coach-player-card {
        width: 100%;
        max-width: 230px;
        margin-inline: auto;
        padding: 3px;
        background: linear-gradient(145deg, #FDE68A, #F8FAFC 25%, #818CF8 62%, #F472B6);
        clip-path: polygon(0 0, 38% 0, 44% 1.5%, 50% 0, 56% 1.5%, 62% 0, 100% 0, 100% 94%, 50% 100%, 0 94%);
        transition: transform 0.25s ease, filter 0.25s ease, opacity 0.2s ease;
    }
    .coach-player-card:hover { opacity: 0.98; transform: translateY(-2px); }
    .coach-player-card:nth-child(4n + 2) {
        background: linear-gradient(145deg, #67E8F9, #F8FAFC 28%, #60A5FA 65%, #A78BFA);
    }
    .coach-player-card:nth-child(4n + 3) {
        background: linear-gradient(145deg, #C4B5FD, #F8FAFC 28%, #F472B6 68%, #FBBF24);
    }
    .coach-player-card:nth-child(4n + 4) {
        background: linear-gradient(145deg, #FCD34D, #F8FAFC 28%, #FB7185 68%, #C084FC);
    }
    .coach-player-card-inner {
        overflow: hidden;
        background: #FFFFFF;
        clip-path: polygon(0 0, 38% 0, 44% 1.5%, 50% 0, 56% 1.5%, 62% 0, 100% 0, 100% 94%, 50% 100%, 0 94%);
    }

    /* --- TIER 2: PRO (61 - 79) - Border Tebal, Lekukan Aerodinamis, Sapphire Cyber --- */
    .coach-player-card.card-tier-pro {
        padding: 6px;
        background: linear-gradient(135deg, #38BDF8 0%, #E0F2FE 20%, #0284C7 45%, #0369A1 70%, #BAE6FD 85%, #0284C7 100%) !important;
        clip-path: polygon(
            14px 0,
            calc(50% - 24px) 0,
            calc(50% - 16px) 5px,
            calc(50% + 16px) 5px,
            calc(50% + 24px) 0,
            calc(100% - 14px) 0,
            100% 14px,
            100% calc(46% - 16px),
            calc(100% - 10px) 46%,
            100% calc(46% + 16px),
            100% calc(100% - 28px),
            calc(100% - 20px) calc(100% - 10px),
            50% 100%,
            20px calc(100% - 10px),
            0 calc(100% - 28px),
            0 calc(46% + 16px),
            10px 46%,
            0 calc(46% - 16px),
            0 14px
        ) !important;
        filter: drop-shadow(0 4px 14px rgba(2, 132, 199, 0.45));
    }
    .coach-player-card.card-tier-pro:hover {
        transform: translateY(-4px);
        filter: drop-shadow(0 8px 24px rgba(2, 132, 199, 0.65));
    }
    .coach-player-card.card-tier-pro .coach-player-card-inner {
        clip-path: polygon(
            14px 0,
            calc(50% - 24px) 0,
            calc(50% - 16px) 5px,
            calc(50% + 16px) 5px,
            calc(50% + 24px) 0,
            calc(100% - 14px) 0,
            100% 14px,
            100% calc(46% - 16px),
            calc(100% - 10px) 46%,
            100% calc(46% + 16px),
            100% calc(100% - 28px),
            calc(100% - 20px) calc(100% - 10px),
            50% 100%,
            20px calc(100% - 10px),
            0 calc(100% - 28px),
            0 calc(46% + 16px),
            10px 46%,
            0 calc(46% - 16px),
            0 14px
        ) !important;
    }
    .coach-player-card.card-tier-pro .coach-player-card-art {
        background: linear-gradient(155deg, #082F49 0%, #0369A1 50%, #0284C7 100%) !important;
    }
    .coach-player-card.card-tier-pro .coach-player-card-stats {
        background: #F0F9FF !important;
    }
    .coach-player-card.card-tier-pro .coach-player-card-footer {
        background: #E0F2FE !important;
        border-top: 1px solid #BAE6FD;
    }

    /* --- TIER 3: ELITE (80 - 100) - Bingkai Mahkota Apex Champion, Emas Mewah, Animasi Aura Tipis --- */
    @keyframes coachEliteAuraPulse {
        0%, 100% {
            filter: drop-shadow(0 0 10px rgba(250, 204, 21, 0.6)) drop-shadow(0 0 24px rgba(16, 185, 129, 0.35));
        }
        50% {
            filter: drop-shadow(0 0 18px rgba(251, 191, 36, 0.9)) drop-shadow(0 0 38px rgba(16, 185, 129, 0.6));
        }
    }
    @keyframes coachEliteShimmerSweep {
        0% { transform: translateX(-100%) rotate(25deg); }
        100% { transform: translateX(200%) rotate(25deg); }
    }

    .coach-player-card.card-tier-elite {
        padding: 7px;
        background: linear-gradient(135deg, #FDE047 0%, #D97706 18%, #FFFBEB 38%, #F59E0B 60%, #78350F 80%, #FBBF24 100%) !important;
        clip-path: polygon(
            18px 0,
            calc(50% - 32px) 0,
            calc(50% - 20px) 7px,
            50% 0,
            calc(50% + 20px) 7px,
            calc(50% + 32px) 0,
            calc(100% - 18px) 0,
            100% 18px,
            100% 28%,
            calc(100% - 7px) 33%,
            100% 38%,
            100% 64%,
            calc(100% - 11px) 70%,
            100% 76%,
            100% calc(100% - 32px),
            calc(100% - 18px) calc(100% - 18px),
            50% 100%,
            18px calc(100% - 18px),
            0 calc(100% - 32px),
            0 76%,
            11px 70%,
            0 64%,
            0 38%,
            7px 33%,
            0 28%,
            0 18px
        ) !important;
        animation: coachEliteAuraPulse 3.5s ease-in-out infinite;
    }
    .coach-player-card.card-tier-elite:hover {
        transform: translateY(-5px) scale(1.02);
        filter: drop-shadow(0 0 24px rgba(251, 191, 36, 0.95)) drop-shadow(0 0 45px rgba(16, 185, 129, 0.8)) !important;
    }
    .coach-player-card.card-tier-elite .coach-player-card-inner {
        clip-path: polygon(
            18px 0,
            calc(50% - 32px) 0,
            calc(50% - 20px) 7px,
            50% 0,
            calc(50% + 20px) 7px,
            calc(50% + 32px) 0,
            calc(100% - 18px) 0,
            100% 18px,
            100% 28%,
            calc(100% - 7px) 33%,
            100% 38%,
            100% 64%,
            calc(100% - 11px) 70%,
            100% 76%,
            100% calc(100% - 32px),
            calc(100% - 18px) calc(100% - 18px),
            50% 100%,
            18px calc(100% - 18px),
            0 calc(100% - 32px),
            0 76%,
            11px 70%,
            0 64%,
            0 38%,
            7px 33%,
            0 28%,
            0 18px
        ) !important;
    }
    .coach-player-card.card-tier-elite .coach-player-card-art {
        background: linear-gradient(155deg, #0A0F0D 0%, #064E3B 48%, #047857 80%, #B45309 100%) !important;
        position: relative;
    }
    .coach-player-card.card-tier-elite .coach-player-card-art::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(105deg, transparent 40%, rgba(254, 240, 138, 0.22) 50%, transparent 60%);
        animation: coachEliteShimmerSweep 5s ease-in-out infinite;
        pointer-events: none;
    }
    .coach-player-card.card-tier-elite .coach-player-card-stats {
        background: #FEFCE8 !important;
        color: #713F12 !important;
    }
    .coach-player-card.card-tier-elite .coach-player-card-stat span {
        color: #854D0E !important;
    }
    .coach-player-card.card-tier-elite .coach-player-card-stat strong {
        color: #451A03 !important;
    }
    .coach-player-card.card-tier-elite .coach-player-card-footer {
        background: #FEF9C3 !important;
        border-top: 1px solid #FDE047;
    }
    .coach-player-card.card-tier-elite .coach-player-card-rating {
        color: #FEF08A !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.95), 0 0 14px rgba(234, 179, 8, 0.8) !important;
    }

    /* --- TIER 4: LEGENDARY (90 - 100) - Bingkai Mahkota Prismatik, Holographic Border, Multi-Aura --- */
    @keyframes coachLegendaryHoloBorder {
        0%   { background-position: 0% 50%;   filter: drop-shadow(0 0 14px rgba(255, 215, 0, 0.75)) drop-shadow(0 0 30px rgba(16, 185, 129, 0.45)); }
        25%  { background-position: 50% 100%; filter: drop-shadow(0 0 18px rgba(192, 132, 252, 0.75)) drop-shadow(0 0 36px rgba(56, 189, 248, 0.55)); }
        50%  { background-position: 100% 0%;  filter: drop-shadow(0 0 20px rgba(251, 113, 133, 0.75)) drop-shadow(0 0 38px rgba(250, 204, 21, 0.55)); }
        75%  { background-position: 50% 0%;   filter: drop-shadow(0 0 18px rgba(56, 189, 248, 0.75)) drop-shadow(0 0 34px rgba(16, 185, 129, 0.55)); }
        100% { background-position: 0% 50%;   filter: drop-shadow(0 0 14px rgba(255, 215, 0, 0.75)) drop-shadow(0 0 30px rgba(16, 185, 129, 0.45)); }
    }
    @keyframes coachLegendaryShimmer {
        0%   { transform: translateX(-150%) rotate(18deg); opacity: 0; }
        15%  { opacity: 1; }
        85%  { opacity: 1; }
        100% { transform: translateX(250%) rotate(18deg); opacity: 0; }
    }
    @keyframes coachLegendaryInnerGlow {
        0%, 100% { box-shadow: inset 0 0 20px rgba(255, 215, 0, 0.08), inset 0 0 40px rgba(192, 132, 252, 0.05); }
        50%      { box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.14), inset 0 0 60px rgba(56, 189, 248, 0.08); }
    }

    .coach-player-card.card-tier-legendary {
        padding: 8px;
        background: linear-gradient(270deg,
            #FFD700, #FF6B6B, #C084FC, #38BDF8, #10B981, #FBBF24, #FFD700
        ) !important;
        background-size: 400% 400% !important;
        clip-path: polygon(
            0 14px, 6% 0, 12% 9px, 28% 0, 38% 6px, 50% 0, 62% 6px, 72% 0, 88% 9px, 94% 0, 100% 14px,
            100% 26%, calc(100% - 5px) 30%, 100% 34%,
            100% 54%, calc(100% - 8px) 59%, 100% 64%,
            100% 78%, calc(100% - 5px) 82%, 100% 86%,
            100% calc(100% - 28px), calc(100% - 22px) calc(100% - 10px), 50% 100%, 22px calc(100% - 10px), 0 calc(100% - 28px),
            0 86%, 5px 82%, 0 78%,
            0 64%, 8px 59%, 0 54%,
            0 34%, 5px 30%, 0 26%
        ) !important;
        animation: coachLegendaryHoloBorder 4s ease infinite;
    }
    .coach-player-card.card-tier-legendary:hover {
        transform: translateY(-6px) scale(1.03);
        filter: drop-shadow(0 0 28px rgba(255, 215, 0, 0.95)) drop-shadow(0 0 50px rgba(192, 132, 252, 0.7)) drop-shadow(0 0 70px rgba(16, 185, 129, 0.5)) !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-inner {
        background: #030712 !important;
        clip-path: polygon(
            0 14px, 6% 0, 12% 9px, 28% 0, 38% 6px, 50% 0, 62% 6px, 72% 0, 88% 9px, 94% 0, 100% 14px,
            100% 26%, calc(100% - 5px) 30%, 100% 34%,
            100% 54%, calc(100% - 8px) 59%, 100% 64%,
            100% 78%, calc(100% - 5px) 82%, 100% 86%,
            100% calc(100% - 28px), calc(100% - 22px) calc(100% - 10px), 50% 100%, 22px calc(100% - 10px), 0 calc(100% - 28px),
            0 86%, 5px 82%, 0 78%,
            0 64%, 8px 59%, 0 54%,
            0 34%, 5px 30%, 0 26%
        ) !important;
        animation: coachLegendaryInnerGlow 4s ease-in-out infinite;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-art {
        background: linear-gradient(155deg, #030712 0%, #1A0A2E 28%, #0C1A30 50%, #064E3B 78%, #422006 100%) !important;
        position: relative;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-art::after {
        content: '';
        position: absolute;
        top: -60%; left: -60%;
        width: 220%; height: 220%;
        background: linear-gradient(105deg, transparent 30%, rgba(255, 215, 0, 0.18) 42%, rgba(192, 132, 252, 0.12) 48%, rgba(56, 189, 248, 0.15) 52%, rgba(255, 215, 0, 0.10) 58%, transparent 70%);
        animation: coachLegendaryShimmer 3.5s ease-in-out infinite;
        pointer-events: none;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-stats {
        background: linear-gradient(135deg, #0C0A09, #1C1917) !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-stat span {
        color: #A8A29E !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-stat strong {
        color: #FDE68A !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-footer {
        background: linear-gradient(135deg, #0C0A09, #1C1917) !important;
        border-top: 1px solid rgba(255, 215, 0, 0.2);
    }
    .coach-player-card.card-tier-legendary .coach-player-card-link {
        color: #FDE68A !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-rating {
        color: #FFFFFF !important;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.9), 0 0 20px rgba(192, 132, 252, 0.6), 0 2px 8px rgba(0, 0, 0, 0.95) !important;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-name {
        background: rgba(3, 7, 18, 0.92) !important;
        border-top: 1px solid rgba(255, 215, 0, 0.15);
    }
    .coach-player-card-art {
        position: relative;
        height: 13rem;
        overflow: hidden;
        background: linear-gradient(145deg, #FB7185 0%, #F472B6 40%, #818CF8 100%);
    }
    .coach-player-card:nth-child(4n + 2) .coach-player-card-art {
        background: linear-gradient(145deg, #22D3EE, #60A5FA 55%, #A78BFA);
    }
    .coach-player-card:nth-child(4n + 3) .coach-player-card-art {
        background: linear-gradient(145deg, #A78BFA, #F472B6 60%, #FB7185);
    }
    .coach-player-card:nth-child(4n + 4) .coach-player-card-art {
        background: linear-gradient(145deg, #FBBF24, #FB7185 58%, #C084FC);
    }
    .coach-player-card-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 18%;
    }
    .coach-player-card-rating {
        position: absolute;
        top: 1.1rem;
        left: 1rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        color: #FFFFFF;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.7));
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9), 0 1px 3px rgba(15, 23, 42, 0.95);
    }
    .coach-player-options {
        position: absolute;
        top: 0.8rem;
        right: 0.8rem;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .coach-player-options a,
    .coach-player-options button {
        display: flex;
        width: 1.8rem;
        height: 1.8rem;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 0.55rem;
        background: rgba(15, 23, 42, 0.82);
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .coach-player-options a:hover,
    .coach-player-options button:hover {
        transform: translateY(-1px);
        background: rgba(15, 23, 42, 0.96);
    }
    .coach-player-card-name {
        position: absolute;
        right: 0.8rem;
        left: 0.8rem;
        bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.65rem;
        color: #FFFFFF;
        background: rgba(15, 23, 42, 0.84);
    }
    .coach-player-card-name h4 {
        overflow: hidden;
        color: #FFFFFF;
        font: 800 0.98rem/1.1 "Space Grotesk", sans-serif;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coach-player-card-number {
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.9);
    }
    .coach-player-card-stats {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 0.1rem;
        padding: 0.45rem 0.25rem;
        background: #FDF2F8;
        color: #1E293B;
    }
    .coach-player-card-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        line-height: 1.15;
    }
    .coach-player-card-stat span {
        color: #475569;
        font: 700 0.55rem/1.2 "JetBrains Mono", monospace;
    }
    .coach-player-card-stat strong {
        color: #0F172A;
        font: 800 0.78rem/1.2 "JetBrains Mono", monospace;
    }
    .coach-player-card-footer {
        min-height: 2.7rem;
        padding: 0.2rem 0.7rem 0.65rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
    }
    .coach-player-card-feet {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .coach-player-card .foot-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        font: 700 0.48rem/1 "JetBrains Mono", monospace;
    }
    .coach-player-card .foot-indicator svg {
        width: 0.7rem;
        height: 0.9rem;
    }
    .coach-player-card .dominant-foot {
        color: #0284C7;
    }
    .coach-player-card .muted-foot {
        color: #94A3B8;
        opacity: 0.4;
    }
    .coach-player-card.card-tier-legendary .dominant-foot {
        color: #FDE68A !important;
    }
    .coach-player-card.card-tier-legendary .muted-foot {
        color: #78716C !important;
        opacity: 0.4;
    }
    .coach-player-card-ball {
        color: #64748B;
        font-size: 0.9rem;
    }
    .coach-player-card.card-tier-legendary .coach-player-card-ball {
        color: #A8A29E !important;
    }
    .coach-player-card-link {
        color: #1D4ED8;
        font-size: 0.72rem;
        font-weight: 700;
    }
</style>

<div class="p-5 sm:p-6 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-pitch-800 pb-4">
        <div>
            <h3 class="font-display font-black text-lg text-white">Data Pemain</h3>
        </div>
        <a href="<?= site_url('admin/players/create') ?>" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Pemain Baru
        </a>
    </div>

    <?php if (!empty($players)): ?>
        <div class="space-y-8">
            <?php foreach ($playersByPosition as $positionGroup => $groupPlayers): ?>
                <section aria-labelledby="coach-roster-<?= esc(url_title($positionGroup), 'attr') ?>">
                    <div class="flex items-center justify-between border-b border-pitch-800 pb-2 mb-4">
                        <h4 id="coach-roster-<?= esc(url_title($positionGroup), 'attr') ?>" class="font-display font-bold text-base text-white">
                            <?= esc($positionGroup) ?>
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-400"><?= count($groupPlayers) ?> pemain</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        <?php foreach ($groupPlayers as $p): ?>
                            <?php
                    $average = (float) ($p['average'] ?? 0);
                    $foot = strtolower(trim((string) ($p['kaki_terkuat'] ?? 'kanan')));
                    $isBoth = in_array($foot, ['keduanya', 'both'], true);
                    $rightFootDominant = in_array($foot, ['kanan', 'right'], true) || $isBoth;
                    $leftFootDominant = in_array($foot, ['kiri', 'left'], true) || $isBoth;
                    $cardTier = \App\Models\PlayerStatModel::getCardTier($average);
                ?>
                <article class="coach-player-card <?= esc($cardTier['class']) ?>">
                    <div class="coach-player-card-inner">
                    <div class="coach-player-card-art">
                        <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" alt="<?= esc($p['nama']) ?>" class="coach-player-card-photo">
                        <div class="coach-player-card-rating">
                            <span class="font-display font-black text-3xl leading-none"><?= $average > 0 ? number_format($average, 0) : '--' ?></span>
                            <span class="text-[20px] font-bold uppercase"><?= esc(\App\Models\PlayerStatModel::getPositionCode($p['posisi'])) ?></span>
                        </div>
                        <?php if ($p['is_captain']): ?>
                            <span class="absolute top-[4.3rem] left-4 z-10 rounded-full bg-white/95 px-2 py-1 text-[9px] font-bold text-amber-700 shadow-sm">
                                <i class="fa-solid fa-crown"></i> KAPTEN
                            </span>
                        <?php endif; ?>

                        <div class="coach-player-options" aria-label="Opsi <?= esc($p['nama']) ?>">
                            <a href="<?= site_url('admin/stats/create/' . $p['id']) ?>" class="text-emerald-600" title="Input nilai" aria-label="Input nilai untuk <?= esc($p['nama']) ?>">
                                <i class="fa-solid fa-chart-line"></i>
                            </a>
                            <a href="<?= site_url('admin/players/edit/' . $p['id']) ?>" class="text-slate-600" title="Edit pemain" aria-label="Edit <?= esc($p['nama']) ?>">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button" onclick="confirmDelete(<?= (int) $p['id'] ?>, <?= esc(json_encode($p['nama']), 'attr') ?>)" class="text-red-600" title="Hapus pemain" aria-label="Hapus <?= esc($p['nama']) ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>

                        <div class="coach-player-card-name">
                            <span class="coach-player-card-number flex-shrink-0 text-xs font-mono font-bold">#<?= esc($p['nomor_punggung'] ?? '-') ?></span>
                            <h4 class="font-display font-black text-lg truncate"><?= esc($p['nama']) ?></h4>
                        </div>
                    </div>

                    <div class="coach-player-card-stats">
                        <?php foreach (\App\Models\PlayerStatModel::getDisplayColumns($p['posisi']) as $label => $field): ?>
                            <div class="coach-player-card-stat">
                                <span><?= $label ?></span>
                                <strong><?= number_format((float) ($p[$field] ?? 0), 0) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="coach-player-card-footer">
                        <div class="coach-player-card-feet" aria-label="Kaki dominan: <?= $rightFootDominant && $leftFootDominant ? 'keduanya' : ($rightFootDominant ? 'kanan' : 'kiri') ?>">
                            <?php foreach (['kiri' => $leftFootDominant, 'kanan' => $rightFootDominant] as $footSide => $isDominant): ?>
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
                        <span class="coach-player-card-ball"><i class="fa-solid fa-futbol"></i></span>
                        <a href="<?= site_url('admin/players/' . $p['id'] . '/stats') ?>" class="coach-player-card-link inline-flex items-center gap-1" aria-label="Lihat rapor <?= esc($p['nama']) ?>">
                            RAPOR <i class="fa-solid fa-arrow-right text-[10px]"></i>
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
        <div class="rounded-xl border border-pitch-750 bg-pitch-950 p-10 text-center">
            <i class="fa-solid fa-user-slash text-2xl text-slate-500 mb-2"></i>
            <p class="text-sm text-slate-400">Belum ada data pemain.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Pemain?',
        text: `Hapus data "${name}" beserta seluruh riwayat penilaian statistiknya?`,
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
            window.location.href = '<?= site_url('admin/players/delete/') ?>' + id;
        }
    });
}
</script>
<?= $this->endSection() ?>
