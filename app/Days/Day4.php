<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day4 extends Day {

	public function solve1(Input $input): int
	{
		$lines = $input->linesAsCollection();

		return self::countOccurrences('XMAS', $lines)
			+ self::countOccurrences('XMAS', self::transpose($lines))
			+ self::countOccurrences('XMAS', self::transpose(self::shiftDiagonal($lines, 'left')))
			+ self::countOccurrences('XMAS', self::transpose(self::shiftDiagonal($lines, 'right')));
	}

	public function solve2(Input $input): int
	{
		$letters = $input->asTwoDimensionalArray();

		$height = count($letters);
		$width = count($letters[0]);

		$count = 0;

		for ($row = 1; $row < $height - 1; $row++) {
			for ($col = 1; $col < $width - 1; $col++) {
				if ($letters[$row][$col] === 'A') {
					$diagonal1 = $letters[$row - 1][$col - 1] . 'A' . $letters[$row + 1][$col + 1];
					$diagonal2 = $letters[$row - 1][$col + 1] . 'A' . $letters[$row + 1][$col - 1];

					if (($diagonal1 === 'MAS' || $diagonal1 === 'SAM') && ($diagonal2 === 'MAS' || $diagonal2 === 'SAM')) {
						$count++;
					}
				}
			}
		}

		return $count;
	}

	protected static function shiftDiagonal(Collection $lines, string $direction): Collection
	{
		$total = $lines->count();

		return $lines->map(fn (string $line, int $index): string => $direction === 'left'
			? str_repeat(' ', $total - $index) . $line . str_repeat(' ', $index)
			: str_repeat(' ', $index) . $line . str_repeat(' ', $total - $index)
		);
	}

	protected static function transpose(Collection $lines): Collection
	{
		$length = strlen($lines->first());
		$out = array_fill(0, $length, '');

		foreach ($lines as $line) {
			for ($charIndex = 0; $charIndex < $length; $charIndex++) {
				$out[$charIndex] .= substr($line, $charIndex, 1);
			}
		}

		return collect($out);
	}

	protected static function countOccurrences(string $needle, Collection $lines): int
	{
		$needle = str($needle);
		$haystack = str($lines->join(' '));

		return $haystack->substrCount($needle) + $haystack->substrCount($needle->reverse());
	}
}
