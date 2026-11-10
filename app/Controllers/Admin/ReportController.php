<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use App\Models\PlayerStatModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends BaseController
{
    public function download()
    {
        $stats = (new PlayerStatModel())->getStatsWithPlayer();
        $playersById = [];
        foreach ((new PlayerModel())->orderBy('nama', 'ASC')->findAll() as $player) {
            $playersById[(int) $player['id']] = [
                'player' => $player,
                'stats' => [],
            ];
        }

        foreach ($stats as $stat) {
            $playerId = (int) $stat['player_id'];
            if (!isset($playersById[$playerId])) {
                continue;
            }

            $playersById[$playerId]['stats'][] = $stat;
        }

        $players = array_values($playersById);
        foreach ($players as &$reportPlayer) {
            usort($reportPlayer['stats'], static function (array $a, array $b): int {
                return [$a['tanggal'] ?? '', $a['created_at'] ?? '', (int) $a['id']]
                    <=> [$b['tanggal'] ?? '', $b['created_at'] ?? '', (int) $b['id']];
            });
            $reportPlayer['latest'] = $reportPlayer['stats'] === [] ? null : end($reportPlayer['stats']);
            $reportPlayer['columns'] = PlayerStatModel::getDisplayColumns($reportPlayer['player']['posisi']);
            $reportPlayer['hasLegacyGoalkeeperStats'] = PlayerStatModel::isLegacyGoalkeeperStat(
                $reportPlayer['latest'] ?? [],
                $reportPlayer['player']['posisi']
            );
            $reportPlayer['photo'] = $this->getPhotoData($reportPlayer['player']['foto']);
            $reportPlayer['radarChart'] = $reportPlayer['latest'] && !$reportPlayer['hasLegacyGoalkeeperStats']
                ? $this->createRadarChart($reportPlayer['columns'], $reportPlayer['latest'])
                : null;
            $reportPlayer['trendChart'] = $reportPlayer['stats'] !== []
                ? $this->createTrendChart($reportPlayer['stats'])
                : null;
        }
        unset($reportPlayer);

        usort($players, static fn (array $a, array $b): int => strnatcasecmp(
            (string) $a['player']['nama'],
            (string) $b['player']['nama']
        ));

        $html = view('admin/reports/pdf', [
            'players' => $players,
            'generatedAt' => date('d-m-Y H:i'),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $this->response->download('laporan-statistik-pemain.pdf', $pdf->output(), true);
    }

    private function getPhotoData(?string $filename): ?array
    {
        if (!$filename) {
            return null;
        }

        $uploadDirectory = realpath(FCPATH . 'uploads/players');
        $photoPath = realpath(FCPATH . 'uploads/players/' . basename($filename));
        if ($uploadDirectory === false || $photoPath === false || !str_starts_with($photoPath, $uploadDirectory . DIRECTORY_SEPARATOR)) {
            return null;
        }

        $mimeType = mime_content_type($photoPath);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/svg+xml'], true)) {
            return null;
        }

        $contents = file_get_contents($photoPath);
        if ($contents === false) {
            return null;
        }

        $imageSize = @getimagesize($photoPath);
        $width = $imageSize[0] ?? 1;
        $height = $imageSize[1] ?? 1;

        if ($mimeType === 'image/svg+xml'
            && preg_match('/viewBox=["\']\s*[-\d.]+\s+[-\d.]+\s+([\d.]+)\s+([\d.]+)/i', $contents, $matches) === 1
        ) {
            $width = (float) $matches[1];
            $height = (float) $matches[2];
        }

        $scale = min(112 / max(1, $width), 112 / max(1, $height));

        return [
            'src' => 'data:' . $mimeType . ';base64,' . base64_encode($contents),
            'width' => round($width * $scale, 2),
            'height' => round($height * $scale, 2),
        ];
    }

    private function createRadarChart(array $columns, array $stat): string
    {
        $width = 700;
        $height = 540;
        $centerX = 350;
        $centerY = 270;
        $radius = 175;
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $grid = imagecolorallocate($image, 215, 225, 241);
        $blue = imagecolorallocate($image, 49, 91, 255);
        $fill = imagecolorallocatealpha($image, 49, 91, 255, 88);
        $navy = imagecolorallocate($image, 30, 48, 82);
        imagefill($image, 0, 0, $white);
        imageantialias($image, true);

        $count = count($columns);
        $points = [];
        $labels = [];
        $index = 0;
        foreach ($columns as $label => $field) {
            $angle = (2 * M_PI * $index / max(1, $count)) - (M_PI / 2);
            $score = max(0, min(100, (float) ($stat[$field] ?? 0)));
            $points[] = [
                (int) round($centerX + cos($angle) * $radius * $score / 100),
                (int) round($centerY + sin($angle) * $radius * $score / 100),
            ];
            $labels[] = [
                (string) $label,
                (int) round($centerX + cos($angle) * ($radius + 35)),
                (int) round($centerY + sin($angle) * ($radius + 26)),
                cos($angle),
                cos($angle) < -0.2 ? 'right' : (cos($angle) > 0.2 ? 'left' : 'center'),
                (int) round($score),
            ];
            $index++;
        }

        for ($ring = 1; $ring <= 4; $ring++) {
            $ringPoints = [];
            for ($index = 0; $index < $count; $index++) {
                $angle = (2 * M_PI * $index / max(1, $count)) - (M_PI / 2);
                $ringPoints[] = (int) round($centerX + cos($angle) * $radius * $ring / 4);
                $ringPoints[] = (int) round($centerY + sin($angle) * $radius * $ring / 4);
            }
            imagepolygon($image, $ringPoints, $grid);
        }

        for ($index = 0; $index < $count; $index++) {
            $angle = (2 * M_PI * $index / max(1, $count)) - (M_PI / 2);
            imageline(
                $image,
                $centerX,
                $centerY,
                (int) round($centerX + cos($angle) * $radius),
                (int) round($centerY + sin($angle) * $radius),
                $grid
            );
        }

        if (count($points) >= 3) {
            $polygon = [];
            foreach ($points as [$x, $y]) {
                $polygon[] = $x;
                $polygon[] = $y;
            }
            imagefilledpolygon($image, $polygon, $fill);
            imagesetthickness($image, 5);
            for ($i = 0; $i < count($points); $i++) {
                [$x1, $y1] = $points[$i];
                [$x2, $y2] = $points[($i + 1) % count($points)];
                imageline($image, $x1, $y1, $x2, $y2, $blue);
            }
            imagesetthickness($image, 1);
        }

        foreach ($points as [$x, $y]) {
            imagefilledellipse($image, $x, $y, 16, 16, $blue);
            imageellipse($image, $x, $y, 16, 16, $white);
        }

        foreach ($labels as [$label, $x, $y, $direction, $alignment, $score]) {
            $text = $label . ' ' . $score;
            $textWidth = imagefontwidth(5) * strlen($text);
            $textX = match ($alignment) {
                'right' => $x - $textWidth,
                'center' => $x - (int) ($textWidth / 2),
                default => $x,
            };
            imagestring($image, 5, max(2, min($width - $textWidth - 2, $textX)), max(2, min($height - 18, $y - 7)), $text, $navy);
        }

        return $this->encodeChart($image);
    }

    private function createTrendChart(array $history): string
    {
        $width = 1000;
        $height = 410;
        $left = 75;
        $right = 950;
        $top = 35;
        $bottom = 335;
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $grid = imagecolorallocate($image, 224, 232, 244);
        $blue = imagecolorallocate($image, 49, 91, 255);
        $fill = imagecolorallocatealpha($image, 49, 91, 255, 104);
        $navy = imagecolorallocate($image, 30, 48, 82);
        $muted = imagecolorallocate($image, 104, 120, 148);
        imagefill($image, 0, 0, $white);
        imageantialias($image, true);

        for ($tick = 0; $tick <= 100; $tick += 20) {
            $y = (int) round($bottom - ($tick / 100) * ($bottom - $top));
            imageline($image, $left, $y, $right, $y, $grid);
            imagestring($image, 3, 35, $y - 7, (string) $tick, $muted);
        }

        $points = [];
        $count = count($history);
        foreach ($history as $index => $stat) {
            $x = $count === 1 ? (int) (($left + $right) / 2) : (int) round($left + $index * (($right - $left) / ($count - 1)));
            $score = max(0, min(100, (float) $stat['average']));
            $y = (int) round($bottom - ($score / 100) * ($bottom - $top));
            $points[] = [$x, $y, (int) round($score)];
        }

        if (count($points) >= 2) {
            $area = [$points[0][0], $bottom];
            foreach ($points as [$x, $y]) {
                $area[] = $x;
                $area[] = $y;
            }
            $last = $points[count($points) - 1];
            $area[] = $last[0];
            $area[] = $bottom;
            imagefilledpolygon($image, $area, $fill);
        }

        if (count($points) >= 2) {
            imagesetthickness($image, 5);
            for ($i = 0; $i < count($points) - 1; $i++) {
                imageline($image, $points[$i][0], $points[$i][1], $points[$i + 1][0], $points[$i + 1][1], $blue);
            }
            imagesetthickness($image, 1);
        }

        foreach ($points as $index => [$x, $y, $score]) {
            $isLatest = $index === count($points) - 1;
            $pointSize = $isLatest ? 22 : 16;
            imagefilledellipse($image, $x, $y, $pointSize, $pointSize, $isLatest ? $blue : $white);
            imageellipse($image, $x, $y, $pointSize, $pointSize, $blue);
            $label = (string) $score;
            imagestring($image, 5, $x - (int) (imagefontwidth(5) * strlen($label) / 2), max(3, $y - 28), $label, $blue);
        }

        return $this->encodeChart($image);
    }

    private function encodeChart(\GdImage $image): string
    {
        ob_start();
        imagepng($image);
        imagedestroy($image);
        $contents = ob_get_clean();

        if ($contents === false) {
            throw new \RuntimeException('Gagal membuat diagram laporan statistik.');
        }

        return 'data:image/png;base64,' . base64_encode($contents);
    }
}
