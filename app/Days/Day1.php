<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day1 extends Day {

	public function solve1(Input $input): int
	{
		$lines = $input->linesAsCollection()
			->map(static fn (string $line) => preg_split('#\s+#', $line));

		$list1 = $lines->pluck(0)->sort()->values();
		$list2 = $lines->pluck(1)->sort()->values();

		$zip = $list1->zip($list2);

		return $zip->map(static fn (Collection $row): int => abs($row[0] - $row[1]))
			->sum();
	}

	public function solve2(Input $input): int
	{
		$lines = $input->linesAsCollection()
			->map(static fn (string $line) => preg_split('#\s+#', $line));

		$list1counts = $lines->pluck(0)->countBy();
		$list2counts = $lines->pluck(1)->countBy();

		return $list1counts->map(static function (int $count, int $number) use ($list2counts): int {
			$count2 = $list2counts->get($number) ?? 0;
			return $count * $count2 * $number;
		})->sum();
	}
}
