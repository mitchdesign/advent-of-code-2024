<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day4 extends Day {

	public function solve1(Input $input): int {
		$lines = $input->linesAsCollection();

		$verticalLines = self::transpose($lines);

		$diagonalLines1 = $lines;
		$diagonalLines1 = self::transpose(
			self::shiftDiagonal($diagonalLines1, 'left')
		);

		$diagonalLines2 = $lines;
		$diagonalLines2 = self::transpose(
			self::shiftDiagonal($diagonalLines2, 'right')
		);

		return self::countOccurrences('XMAS', $lines)
			+ self::countOccurrences('XMAS', $verticalLines)
			+ self::countOccurrences('XMAS', $diagonalLines1)
			+ self::countOccurrences('XMAS', $diagonalLines2);
	}

	public function solve2(Input $input): int {
		$letters = $input->asTwoDimensionalArray();

		$height = count($letters);
		$width = count($letters[0]);

		$count = 0;

		for ($row = 1; $row < $height - 1; $row++) {
			for ($col = 1; $col < $width - 1; $col++) {
				if ($letters[$row][$col] === 'A') {
					$diagonal1 = $letters[$row - 1][$col - 1] . $letters[$row + 1][$col + 1];
					$diagonal2 = $letters[$row - 1][$col + 1] . $letters[$row + 1][$col - 1];
					if (($diagonal1 === 'MS' || $diagonal1 === 'SM') && ($diagonal2 === 'MS' || $diagonal2 === 'SM')) { $count++; }
				}
			}
		}

		return $count;
	}

	protected static function shiftDiagonal(Collection $lines, string $direction): Collection {
		$total = $lines->count();

		$lines = $lines->map(fn (string $line, int $index): string => $direction === 'left'
			? str_repeat(' ', $total - $index) . $line . str_repeat(' ', $index)
			: str_repeat(' ', $index) . $line . str_repeat(' ', $total - $index)
		);

		return $lines;
	}

	protected static function transpose(Collection $lines): Collection {
		$length = strlen($lines->first());
		$out = array_fill(0, $length, '');

		foreach ($lines as $line) {
			for ($charIndex = 0; $charIndex < $length; $charIndex++) {
				$out[$charIndex] .= substr($line, $charIndex, 1);
			}
		}

		return collect($out);
	}

	protected static function countOccurrences(string $needle, Collection $lines): int {
		$needle = str($needle);
		$haystack = str($lines->join(' '));

		return $haystack->substrCount($needle) + $haystack->substrCount($needle->reverse());
	}
}
