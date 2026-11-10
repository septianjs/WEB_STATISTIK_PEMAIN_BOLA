<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 portrait; margin: 24px 28px; }
        body { font-family: DejaVu Sans, sans-serif; color: #17233f; font-size: 9px; background: #f1f4f9; }
        .player-page { page-break-after: always; }
        .player-page:last-child { page-break-after: auto; }
        .header { width: 100%; height: 126px; border-collapse: separate; border-spacing: 0; margin-bottom: 12px; background: #101d38; }
        .header td { vertical-align: middle; }
        .photo-cell { width: 125px; padding: 12px 0 12px 14px; }
        .photo { display: block; max-width: 103px; max-height: 108px; }
        .identity { padding: 10px 12px; color: #fff; }
        h1 { margin: 0 0 7px; color: #fff; font-size: 21px; }
        .identity p { margin: 4px 0; color: #d7dfef; line-height: 1.4; font-size: 8px; }
        .meta { padding: 3px 7px; background: #293752; color: #f4f7fc; }
        .overall-cell { width: 112px; padding-right: 13px; }
        .overall-ring { width: 88px; height: 88px; border: 6px solid #16cba9; background: #101d38; text-align: center; }
        .overall-ring td { vertical-align: middle; }
        .overall-score { color: #fff; font-size: 26px; font-weight: bold; line-height: 1.05; }
        .overall-title { color: #b6c4dc; font-size: 7px; font-weight: bold; letter-spacing: 1px; }
        .overall-week { color: #d7dfef; font-size: 8px; }
        .panels { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin: 0 -10px 11px; }
        .panels > tbody > tr > td { width: 50%; padding: 0; vertical-align: top; }
        .panel { height: 258px; padding: 12px 13px; background: #fff; border: 1px solid #e0e6f0; }
        h2 { margin: 0 0 5px; font-size: 11px; color: #1d2b49; }
        .caption { margin: 0 0 8px; color: #71809b; font-size: 8px; }
        .radar-image { display: block; width: 100%; height: 205px; }
        .stat-grid { width: 100%; height: 215px; border-collapse: separate; border-spacing: 6px; margin: -4px; }
        .stat-grid td { width: 50%; height: 62px; padding: 7px; background: #f7f9fc; border: 1px solid #e2e8f2; vertical-align: middle; }
        .stat-head { color: #64748b; font-size: 8px; font-weight: bold; }
        .stat-score { float: right; color: #17233f; font-size: 15px; font-weight: bold; }
        .stat-max { color: #73809a; font-size: 8px; font-weight: normal; }
        .stat-track { height: 6px; margin-top: 7px; background: #e2e8f0; }
        .stat-bar { height: 6px; background: #375dff; }
        .stat-bar.defending { background: #0ca678; }
        .trend-panel { padding: 12px 14px 10px; background: #fff; border: 1px solid #e0e6f0; }
        .trend-image { display: block; width: 100%; height: 176px; }
        .trend-labels { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 2px; }
        .trend-labels td { width: 25%; padding: 3px 2px; text-align: center; vertical-align: top; }
        .trend-labels strong { display: block; color: #263552; font-size: 7px; }
        .trend-labels span { display: block; margin-top: 2px; color: #71809b; font-size: 7px; }
        .note { margin-top: 10px; padding: 7px 9px; border-left: 3px solid #375dff; background: #fff; color: #475569; line-height: 1.4; }
        .note strong { color: #1e3a8a; }
        .empty { color: #64748b; text-align: center; padding: 28px 0; }
        .generated { margin: 8px 2px 0; color: #8793a7; font-size: 7px; text-align: right; }
    </style>
</head>
<body>
<?php if ($players === []): ?>
    <p class="empty">Belum ada data pemain.</p>
<?php endif; ?>

<?php foreach ($players as $report): ?>
    <?php
        $player = $report['player'];
        $latest = $report['latest'];
        $columns = $report['columns'];
        $history = $report['stats'];
    ?>
    <section class="player-page">
        <table class="header">
            <tr>
                <td class="photo-cell">
                    <?php if ($report['photo']): ?>
                        <img class="photo" src="<?= esc($report['photo']['src']) ?>" width="<?= esc($report['photo']['width']) ?>" height="<?= esc($report['photo']['height']) ?>" alt="">
                    <?php endif; ?>
                </td>
                <td class="identity">
                    <h1><?= esc($player['nama']) ?></h1>
                    <p>
                        <span class="meta">No. <?= esc($player['nomor_punggung'] ?? '-') ?></span>
                        <span class="meta"><?= esc($player['posisi']) ?></span>
                        <span class="meta"><?= $player['usia'] ? esc($player['usia']) . ' tahun' : 'Usia -' ?></span>
                    </p>
                    <p><span class="meta">Kaki dominan: <?= esc($player['kaki_terkuat'] ?? '-') ?></span><?= $player['is_captain'] ? ' <span class="meta">Kapten</span>' : '' ?></p>
                    <?php if (!empty($player['bio'])): ?><p><?= esc($player['bio']) ?></p><?php endif; ?>
                </td>
                <td class="overall-cell">
                    <table class="overall-ring"><tr><td>
                        <div class="overall-score"><?= $latest ? esc((int) round((float) $latest['average'])) : '-' ?></div>
                        <div class="overall-title">OVERALL</div>
                        <div class="overall-week"><?= $latest ? esc($latest['indikator']) : 'Belum dievaluasi' ?></div>
                    </td></tr></table>
                </td>
            </tr>
        </table>

        <?php if ($latest): ?>
            <table class="panels">
                <tr>
                    <td>
                        <div class="panel">
                            <h2>Diagram Atribut · <?= esc($latest['indikator']) ?></h2>
                            <p class="caption">Perbandingan nilai stat pada evaluasi terbaru</p>
                            <?php if ($report['hasLegacyGoalkeeperStats']): ?>
                                <p class="empty">Rincian stat kiper pada evaluasi lama tidak tersedia.</p>
                            <?php elseif ($report['radarChart']): ?>
                                <img class="radar-image" src="<?= esc($report['radarChart']) ?>" alt="Diagram radar atribut pemain">
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="panel">
                            <h2>Diagram Rincian Stat · <?= esc($latest['indikator']) ?></h2>
                            <?php if ($report['hasLegacyGoalkeeperStats']): ?>
                                <p class="empty">Rincian stat untuk evaluasi kiper lama belum tersedia.</p>
                            <?php else: ?>
                                <table class="stat-grid">
                                    <?php foreach (array_chunk($columns, 2, true) as $row): ?>
                                        <tr>
                                            <?php foreach ($row as $label => $field): ?>
                                                <?php $score = max(0, min(100, (float) ($latest[$field] ?? 0))); ?>
                                                <td>
                                                    <span class="stat-head"><?= esc($label) ?></span>
                                                    <span class="stat-score"><?= esc((int) round($score)) ?><span class="stat-max">/100</span></span>
                                                    <div class="stat-track"><div class="stat-bar <?= $label === 'DEF' ? 'defending' : '' ?>" style="width: <?= $score ?>%"></div></div>
                                                </td>
                                            <?php endforeach; ?>
                                            <?php if (count($row) < 2): ?><td></td><?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>

            <section class="trend-panel">
                <h2>Tren Overall · Seluruh Evaluasi</h2>
                <?php if ($history === []): ?>
                    <p class="empty">Belum ada riwayat evaluasi.</p>
                <?php elseif ($report['trendChart']): ?>
                    <img class="trend-image" src="<?= esc($report['trendChart']) ?>" alt="Grafik tren Overall">
                    <?php foreach (array_chunk($history, 4) as $row): ?>
                        <table class="trend-labels">
                            <tr>
                                <?php foreach ($row as $stat): ?>
                                    <td>
                                        <strong><?= esc($stat['indikator']) ?> · <?= esc((int) round((float) $stat['average'])) ?></strong>
                                        <span><?= !empty($stat['tanggal']) ? esc(date('d/m/Y', strtotime($stat['tanggal']))) : '' ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php for ($i = count($row); $i < 4; $i++): ?><td></td><?php endfor; ?>
                            </tr>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

            <?php if (!empty($latest['catatan'])): ?>
                <div class="note"><strong>Catatan Pelatih (<?= esc($latest['indikator']) ?>):</strong> <?= esc($latest['catatan']) ?></div>
            <?php endif; ?>
        <?php else: ?>
            <div class="panel empty">Pemain ini belum memiliki data evaluasi statistik.</div>
        <?php endif; ?>

        <p class="generated">Laporan dibuat <?= esc($generatedAt) ?></p>
    </section>
<?php endforeach; ?>
</body>
</html>
