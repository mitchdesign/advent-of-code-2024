<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day8 extends Day {

	protected static bool $withHarmonics = false;
	protected static int $gridSize;

	public function solve1(Input $input) : int {
		// get map bounds to exclude items outside map
		self::$gridSize = count($input->linesAsArray());

		$frequencies = collect($input->asCoordinateKeyedArray())
			->filter(static fn(string $char) => $char !== '.')
			->mapToGroups(static fn(string $char, string $location) => [$char => $location]);

		return $frequencies->map(static fn(Collection $nodes) => self::getAntinodes($nodes))
			->flatten()
			->filter(static fn(string $node) : bool => self::isInBounds($node))
			->uniqueStrict() // SO annoying! 4.30 == 4.3 and that killed just a few too many nodes
			->count();
	}

	public function solve2(Input $input) : int {
		self::$withHarmonics = true;

		return $this->solve1($input);
	}

	protected static function isInBounds(string $node) : bool {
		[$x, $y] = explode('.', $node);

		return $x >= 0 && $x < self::$gridSize && $y >= 0 && $y < self::$gridSize;
	}

	protected static function getAntinodes(Collection $nodes) : Collection {
		$antinodes = [];

		while ($node1 = $nodes->pop()) {
			foreach ($nodes as $node2) {
				$antinodes = array_merge($antinodes, self::getAntinodesForCombination($node1, $node2));
			}
		}

		return collect($antinodes);
	}

	protected static function getAntinodesForCombination(string $node1, string $node2) : array {
		[$x1, $y1] = explode('.', $node1);
		[$x2, $y2] = explode('.', $node2);

		$dx = $x2 - $x1;
		$dy = $y2 - $y1;

		if (!self::$withHarmonics) {
			return [
				$x1 - $dx . '.' . $y1 - $dy,
				$x2 + $dx . '.' . $y2 + $dy,
			];
		}

		$repeat = max(
			ceil(self::$gridSize / abs($dx)),
			ceil(self::$gridSize / abs($dy)),
		);

		$antinodes = [];

		for ($i = -1 * $repeat; $i <= $repeat; $i++) {
			$x = (int) $x1 + $i * $dx;
			$y = (int) $y1 + $i * $dy;
			$antinodes[] = "{$x}.{$y}";
		}

		return $antinodes;
	}
}