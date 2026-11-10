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
        transition: opacity 0.2s ease;
    }
    .coach-player-card:hover { opacity: 0.96; }
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
        text-shadow: 0 1px 5px rgba(15, 23, 42, 0.45);
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
    .coach-player-card-link {
        color: #1D4ED8;
        font-size: 0.72rem;
        font-weight: 700;
    }
</style>

<div class="p-5 sm:p-6 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-pitch-800 pb-4">
        <div>
            <h3 class="font-display font-black text-lg text-white">MANAJEMEN DATA PEMAIN</h3>
            <p class="text-xs text-emerald-200/70 mt-0.5">Daftar pemain dan ringkasan statistik terbaru</p>
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
                    $footLabel = in_array($foot, ['kanan', 'right'], true) ? 'Kanan' : (in_array($foot, ['keduanya', 'both'], true) ? 'Keduanya' : 'Kiri');
                ?>
                <article class="coach-player-card">
                    <div class="coach-player-card-inner">
                    <div class="coach-player-card-art">
                        <img src="<?= base_url($p['foto'] ? 'uploads/players/' . $p['foto'] : 'uploads/players/default.svg') ?>" alt="<?= esc($p['nama']) ?>" class="coach-player-card-photo">
                        <div class="coach-player-card-rating">
                            <span class="font-display font-black text-3xl leading-none"><?= $average > 0 ? number_format($average, 0) : '--' ?></span>
                            <span class="text-[20px] font-bold uppercase"><?= esc(\App\Models\PlayerStatModel::getPositionCode($p['posisi'])) ?></span>
                        </div>
                        <?php if ($p['is_captain']): ?>
                            <span class="absolute top-[4.3rem] left-4 z-10 rounded-full bg-white/95 px-2 py-1 text-[9px] font-bold text-amber-700">
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
                            <span class="flex-shrink-0 text-xs font-mono font-bold">#<?= esc($p['nomor_punggung'] ?? '-') ?></span>
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
                        <span class="text-[10px] text-slate-500"><?= esc($footLabel) ?> · <?= esc($p['usia'] ? $p['usia'] . ' thn' : 'Usia -') ?></span>
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
