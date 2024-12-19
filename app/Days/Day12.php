<?php

namespace App\Days;

use App\Input;

class Day12 extends Day {

    public function solve1(Input $input): int
	{
        $regionMap = self::getRegionMap($input);
        $fenceCount = self::getFenceCount($regionMap);

        return collect($regionMap)
            ->flatten()
            ->countBy()
            ->map(static fn (int $count, int $region): int => $count * $fenceCount[$region])
            ->sum();
    }

	public function solve2(Input $input): int
	{
        $regionMap = self::getRegionMap($input);
        $sideCount = self::getSideCount($regionMap);

        return collect($regionMap)
            ->flatten()
            ->countBy()
            ->map(static fn (int $count, int $region): int => $count * $sideCount[$region])
            ->sum();
    }

    protected static function getRegionMap(Input $input): array
    {
        $map = $input->asTwoDimensionalArray();

        $regionMap = [];
        $regionCounter = 0;

        foreach ($map as $row => $line) {
            foreach ($line as $col => $letter) {
                $matchesLeft = ($map[$row][$col - 1] ?? null) === $letter;
                $matchesRight = ($map[$row - 1][$col] ?? null) === $letter;

                if (! $matchesLeft && ! $matchesRight) {
                    $regionMap[$row][$col] = ++$regionCounter;
                    continue;
                }

                if ($matchesLeft && $matchesRight) {
                    $regionMap[$row][$col] = $regionMap[$row][$col - 1]; // take left if both work.
                    self::renumberRegion($regionMap, $regionMap[$row - 1][$col], $regionMap[$row][$col - 1]);
                    continue;
                }

                $regionMap[$row][$col] = $matchesLeft
                    ? $regionMap[$row][$col - 1]
                    : $regionMap[$row - 1][$col];
            }
        }

        return $regionMap;
    }

    protected static function renumberRegion(array &$regionMap, int $from, int $to): void
    {
        foreach ($regionMap as &$line) {
            foreach ($line as &$reg) {
                $reg = ($reg === $from) ? $to : $reg;
            }
        }
    }

    protected static function getFenceCount(array $regionMap): array
    {
        $fenceCount = array_fill_keys(
            collect($regionMap)->flatten()->unique()->toArray(),
        0
        );

        $rows = count($regionMap);
        $cols = count($regionMap[0]);

        for ($row = -1; $row < $rows; $row++) {
            for ($col = -1; $col < $cols; $col++) {
                $region1 = $regionMap[$row][$col] ?? 0;
                $region2a = $regionMap[$row][$col + 1] ?? 0;
                $region2b = $regionMap[$row + 1][$col] ?? 0;

                if ($region1 !== $region2a) {
                    $region1 === 0 || $fenceCount[$region1]++;
                    $region2a === 0 || $fenceCount[$region2a]++;
                }

                if ($region1 !== $region2b) {
                    $region1 === 0 || $fenceCount[$region1]++;
                    $region2b=== 0 || $fenceCount[$region2b]++;
                }
            }
        }

        return $fenceCount;
    }


    protected static function getSideCount(array $regionMap): array
    {
        // Each region has the same number of sides as it has corners.
        // If we scan the grid + 1 grid unit border around it, we can look at each 2x2 block.
        // If a block contains an odd number of units for any region, that region has an inner or outer corner.
        // Also if a region has 2 occurrences but they are diagonal, they form 2 corners.
        //
        // ......    ..                       ..
        // .BABC.    .B  outer corner of A    AB  corner of A and B    BA  two corners for A and B
        // .ABBC.                                                      AB
        // .ABCC.    BC  no corner            BC  corner of B and C
        // ......    BC                       CC

        $sideCount = array_fill_keys(
            collect($regionMap)->flatten()->unique()->toArray(),
            0
        );

        $rows = count($regionMap);
        $cols = count($regionMap[0]);

        for ($row = -1; $row < $rows; $row++) {
            for ($col = -1; $col < $cols; $col++) {
                $regions = collect([
                    $regionMap[$row][$col] ?? 0,
                    $regionMap[$row][$col + 1] ?? 0,
                    $regionMap[$row + 1][$col + 1] ?? 0,
                    $regionMap[$row + 1][$col] ?? 0,
                ]);

                foreach ($regions->filter()->countBy() as $region => $count) {
                    switch ($count) {
                        case 1:
                        case 3:
                            $sideCount[$region]++;
                            break;
                        case 2:
                            if (
                                ($regions[0] === $region && $regions[2] === $region)
                                || ($regions[1] === $region && $regions[3] === $region)
                            ) {
                                $sideCount[$region] += 2;
                            }
                            break;
                    }
                }
            }
        }

        return $sideCount;
    }
}
