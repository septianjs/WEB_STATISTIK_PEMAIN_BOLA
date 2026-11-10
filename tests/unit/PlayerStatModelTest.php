<?php

namespace Tests\Unit;

use App\Models\PlayerStatModel;
use App\Models\PlayerModel;
use PHPUnit\Framework\TestCase;

class PlayerStatModelTest extends TestCase
{
    public function testPlayersAreGroupedIntoAttackingMidfieldDefensiveAndGoalkeeperLines(): void
    {
        self::assertSame('Penyerang', PlayerModel::getPositionGroup('Sayap kanan/kiri (RW/LW)'));
        self::assertSame('Gelandang', PlayerModel::getPositionGroup('Gelandang bertahan (CDM)'));
        self::assertSame('Bek', PlayerModel::getPositionGroup('Bek sayap (RWB/LWB)'));
        self::assertSame('Kiper', PlayerModel::getPositionGroup('Kiper (GK)'));

        $grouped = PlayerModel::groupPlayersByPosition([
            ['nama' => 'Bek', 'posisi' => 'Bek tengah (CB)'],
            ['nama' => 'Striker', 'posisi' => 'Penyerang utama (ST)'],
            ['nama' => 'Keeper', 'posisi' => 'Kiper (GK)'],
        ]);
        self::assertSame(['Penyerang', 'Bek', 'Kiper'], array_keys($grouped));
        self::assertSame('Striker', $grouped['Penyerang'][0]['nama']);
    }

    public function testOutfieldStatsAreWeightedByPositionAndRoundedToInteger(): void
    {
        $input = [
            'tendangan' => 97,
            'passing' => 80,
            'control' => 80,
            'agility' => 92,
            'fisik' => 78,
            'defending' => 50,
        ];

        $scores = PlayerStatModel::calculateScores($input, 'Penyerang utama (ST)');

        self::assertSame(97.0, $scores['categories']['shooting']);
        self::assertSame(91, $scores['average']);
    }

    public function testNinetyNinePercentPositionWeightsAreNormalized(): void
    {
        $input = [
            'tendangan' => 100,
            'passing' => 80,
            'control' => 60,
            'agility' => 40,
            'fisik' => 20,
            'defending' => 10,
        ];

        $scores = PlayerStatModel::calculateScores($input, 'Sayap kanan/kiri (RW/LW)');

        self::assertSame(99, array_sum(PlayerStatModel::getRoleWeights()['RW/LW']));
        self::assertSame(77, $scores['average']);

        $cdmScores = PlayerStatModel::calculateScores($input, 'Gelandang bertahan (CDM)');
        self::assertSame(99, array_sum(PlayerStatModel::getRoleWeights()['CDM']));
        self::assertSame(43, $cdmScores['average']);
    }

    public function testInputFieldsContainOnlyRequestedStats(): void
    {
        self::assertSame(
            ['tendangan', 'passing', 'control', 'agility', 'fisik', 'defending'],
            PlayerStatModel::getInputStatFields('Penyerang utama (ST)')
        );
        self::assertSame(
            ['diving', 'handling', 'positioning', 'reflexes', 'reactions', 'kicking'],
            PlayerStatModel::getInputStatFields('Kiper (GK)')
        );
    }

    public function testRadarSnapshotsIncludeEachEvaluationAndMarkLegacyGoalkeeperRows(): void
    {
        $snapshots = PlayerStatModel::getRadarSnapshots([
            [
                'id' => 12,
                'indikator' => 'Minggu 1',
                'tanggal' => '2026-11-01',
                'diving' => 80,
                'handling' => 70,
                'positioning' => 60,
                'reflexes' => 50,
                'reactions' => 40,
                'kicking' => 30,
                'average' => 65,
            ],
        ], 'Kiper (GK)');

        self::assertSame('Minggu 1 · 01/11/2026', $snapshots[12]['label']);
        self::assertSame('Minggu 1', $snapshots[12]['indicator']);
        self::assertSame([80.0, 70.0, 60.0, 50.0, 40.0, 30.0], $snapshots[12]['values']);
        self::assertSame([
            'diving' => 80.0,
            'handling' => 70.0,
            'positioning' => 60.0,
            'reflexes' => 50.0,
            'reactions' => 40.0,
            'kicking' => 30.0,
        ], $snapshots[12]['stats']);
        self::assertSame(65, $snapshots[12]['average']);
        self::assertFalse($snapshots[12]['unavailable']);

        $legacy = PlayerStatModel::getRadarSnapshots([[
            'id' => 13,
            'indikator' => 'Minggu 2',
            'average' => 80,
            'control' => 74,
            'reactions' => 74,
        ]], 'Kiper (GK)');
        self::assertTrue($legacy[13]['unavailable']);
    }

    public function testGoalkeeperUsesOnlyItsSixStatsAndIgnoresHeadingAndWeakFoot(): void
    {
        $input = [
            'diving' => 100,
            'handling' => 90,
            'positioning' => 80,
            'reflexes' => 70,
            'reactions' => 60,
            'kicking' => 50,
            'heading' => 100,
            'weak' => 100,
        ];
        $scores = PlayerStatModel::calculateScores($input, 'Kiper (GK)');

        self::assertSame(82, $scores['average']);
        $input['heading'] = 0;
        $input['weak'] = 0;
        self::assertSame($scores['average'], PlayerStatModel::calculateScores($input, 'Kiper (GK)')['average']);
    }

    public function testEveryConfiguredPositionProducesAValidWeightedScore(): void
    {
        $input = [
            'tendangan' => 100,
            'passing' => 100,
            'control' => 100,
            'agility' => 100,
            'fisik' => 100,
            'defending' => 100,
        ];
        foreach (array_keys(PlayerStatModel::getGoalkeeperStats()) as $field) {
            $input[$field] = 100;
        }

        foreach (PlayerModel::getPositionOptions() as $position) {
            self::assertSame(100, PlayerStatModel::calculateScores($input, $position)['average'], $position);
        }
    }

    public function testLegacyGoalkeeperRowsAreDistinguishedFromNewZeroScores(): void
    {
        self::assertTrue(PlayerStatModel::isLegacyGoalkeeperStat([
            'average' => 80.2,
            'diving' => 0,
            'handling' => 0,
            'positioning' => 0,
            'reflexes' => 0,
            'reactions' => 74,
            'kicking' => 0,
            'control' => 74,
        ], 'Kiper (GK)'));
        self::assertFalse(PlayerStatModel::isLegacyGoalkeeperStat([
            'average' => 0,
            'diving' => 0,
            'handling' => 0,
            'positioning' => 0,
            'reflexes' => 0,
            'reactions' => 0,
            'kicking' => 0,
        ], 'Kiper (GK)'));
        self::assertFalse(PlayerStatModel::isLegacyGoalkeeperStat([
            'average' => 4.4,
            'diving' => 0,
            'handling' => 0,
            'positioning' => 0,
            'reflexes' => 0,
            'reactions' => 74,
            'kicking' => 0,
            'control' => 0,
        ], 'Kiper (GK)'));
    }
}
