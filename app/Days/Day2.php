<?php

namespace App\Days;

use App\Input;

class Day2 extends Day {

	public function solve1(Input $input): int
	{
		return $input->linesAsCollection()
			->filter(static fn (string $line): bool => self::isReportSafe(explode(' ', $line)))
			->count();
	}

	public function solve2(Input $input): int
	{
		return $input->linesAsCollection()
			->filter(static fn (string $line): bool => self::isReportSafe(explode(' ', $line), dampen: true))
			->count();
	}

	protected static function isReportSafe(array $values, bool $dampen = false): bool
	{
		[$minDiff, $maxDiff] = end($values) > reset($values) ? [1, 3] : [-3, -1];

		foreach ($values as $index => $value) {
			if ($index === 0) {
				continue;
			}

			$difference = $value - $values[$index - 1];

			if ($difference < $minDiff || $difference > $maxDiff) {
				if (! $dampen) {
					return false;
				}

				$values1 = $values2 = $values;

				array_splice($values1, $index, 1);
				array_splice($values2, $index - 1, 1);

				return self::isReportSafe($values1) || self::isReportSafe($values2);
			}
		}

		return true;
	}
}
