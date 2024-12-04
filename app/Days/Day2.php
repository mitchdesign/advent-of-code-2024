<?php

namespace App\Days;

use App\Input;

class Day2 extends Day {

	public function solve1(Input $input) : int {
		return $input->linesAsCollection()
			->filter(static function (string $line) {
				$values = explode(' ', $line);
				$values = array_map('intval', $values);
				return self::isReportSafe($values);
			})
			->count();
	}

	public function solve2(Input $input) : int {
		return $input->linesAsCollection()
			->filter(static function (string $line) {
				$values = explode(' ', $line);
				$values = array_map('intval', $values);
				return self::isReportSafe($values, dampen: true);
			})
			->count();
	}

	protected static function isReportSafe(array $values, bool $dampen = false, int $skipIndex = null) : bool {
		if ($skipIndex !== null) {
			unset($values[$skipIndex]);
			$values = array_values($values);
		}

		$last = end($values);
		$first = reset($values);

		$reportGoesUp = $last > $first;

		foreach ($values as $index => $value) {
			if ($index > 0) {
				$minDiff = $reportGoesUp ? 1 : -3;
				$maxDiff = $reportGoesUp ? 3 : -1;

				$difference = $value - $values[$index - 1];

				if ($difference < $minDiff || $difference > $maxDiff) {
					if ($dampen) {
						return self::isReportSafe($values, false, $index) || self::isReportSafe($values, false, $index - 1);
					}
					return false;
				}
			}
		}

		return true;
	}
}
