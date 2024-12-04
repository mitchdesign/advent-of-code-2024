<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day1 extends Day {

	public function solve1(Input $input) : int {
		$lines = $input->linesAsCollection()
			->map(static fn (string $line) => preg_split('#\s+#', $line));

		$list1 = $lines->pluck(0)->sort()->values();
		$list2 = $lines->pluck(1)->sort()->values();

		$zip = $list1->zip($list2);

		return $zip->map(static fn (Collection $row) => abs($row[0] - $row[1]))->sum();
	}

	public function solve2(Input $input) : int {
		$lines = $input->linesAsCollection()
			->map(static fn (string $line) => preg_split('#\s+#', $line));

		$list1 = $lines->pluck(0)->values();
		$list2 = $lines->pluck(1)->values();

		$list1counts = $this->getCounts($list1);
		$list2counts = $this->getCounts($list2);

		return $list1counts->map(static function ($count, $number) use ($list2counts) {
			$count2 = $list2counts->get($number) ?? 0;
			return $count * $count2 * $number;
		})->sum();
	}

	private function getCounts(Collection $list): Collection {
		$out = [];

		foreach ($list as $number) {
			$current = $out[$number] ?? 0;
			$out[$number] = $current + 1;
		}

		return collect($out);
	}
}
