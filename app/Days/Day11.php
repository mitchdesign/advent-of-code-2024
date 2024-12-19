<?php

namespace App\Days;

use App\Input;

class Day11 extends Day {

    public function solve1(Input $input): int
	{
        return $this->solve($input, 25);
	}

    public function solve2(Input $input): int
    {
        return $this->solve($input, 75);
    }

	public function solve(Input $input, int $iterations): int
	{
        $stones = [];

        foreach (explode(' ', $input->asString()) as $stone) {
            $stones[$stone] = ($stones[$stone] ?? 0) + 1;
        }

        for ($i = 1; $i <= $iterations; $i++) {
            $newStones = [];
            foreach ($stones as $stone => $count) {
                foreach (self::handleStone($stone) as $handled) {
                    $newStones[$handled] = ($newStones[$handled] ?? 0) + $count;
                }
            }
            $stones = $newStones;
        }

        return array_sum($stones);
	}

    protected static function handleStone(int $stone): array {
        if ($stone === 0) {
            return [1];
        }

        $string = (string) $stone;

        if (strlen($string) % 2 === 0) {
            $halflen = strlen($string) / 2;
            return [(int) substr($string, 0, $halflen), (int) substr($string, -1 * $halflen)];
        }

        return [$stone * 2024];
    }
}
