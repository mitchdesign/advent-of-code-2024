<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day3 extends Day {

	public function solve1(Input $input) : int {
		preg_match_all('#mul\(\d+,\d+\)#', $input->asString(), $matches);

		return collect($matches[0])
			->map(static function (string $combo): int {
				preg_match_all('#mul\((\d+),(\d+)\)#', $combo, $matches);
				return $matches[1][0] * $matches[2][0];
			})->sum();
	}

	public function solve2(Input $input) : int {
		preg_match_all('#mul\(\d+,\d+\)|do\(\)|don\'t\(\)#', $input->asString(), $matches);

		$take = true;
		$total = 0;

		foreach ($matches[0] as $match) {
			switch ($match) {
				case 'do()':
					$take = true;
					break;
				case "don't()":
					$take = false;
					break;
				default:
					if ($take) {
						preg_match_all('#mul\((\d+),(\d+)\)#', $match, $combomatch);
						$total += $combomatch[1][0] * $combomatch[2][0];
					}
			}
		}

		return $total;
	}
}
