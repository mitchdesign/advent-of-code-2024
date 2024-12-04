<?php

namespace App\Days;

use App\Input;

class Day3 extends Day {

	public function solve1(Input $input): int
	{
		preg_match_all('#mul\(\d+,\d+\)#', $input->asString(), $matches);

		return collect($matches[0])
			->map(static fn (string $combo): int => self::getProductOfMulExpression($combo))
			->sum();
	}

	public function solve2(Input $input): int
	{
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
						$total += self::getProductOfMulExpression($match);
					}
			}
		}

		return $total;
	}

	protected static function getProductOfMulExpression(string $mul): int
	{
		preg_match_all('#mul\((\d+),(\d+)\)#', $mul, $matches);
		return $matches[1][0] * $matches[2][0];
	}
}
