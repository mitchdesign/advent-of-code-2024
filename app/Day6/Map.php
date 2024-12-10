<?php

namespace App\Day6;

use Illuminate\Support\Collection;

class Map {

	protected int $width;
	protected int $height;

	public function __construct(
		 protected array $mapArray
	)
	{
		$this->width = count($this->mapArray[0]);
		$this->height = count($this->mapArray);
	}

	public function contains(Position $position): bool {
		return $position->getX() >= 0
			&& $position->getX() < $this->width
			&& $position->getY() >= 0
			&& $position->getY() < $this->height;
	}

	public function mark(Position $position, string $string): void {
		$this->mapArray[$position->getY()][$position->getX()] = $string;
	}

	public function count(string $string): int {
		$mapString = collect($this->mapArray)
			->map(static fn (array $row): string => join('', $row))
			->join("\n");
		return str($mapString)->substrCount($string);
	}

	public function findAll(string $string): Collection {
		$found = collect();

		foreach ($this->mapArray as $y => $row) {
			foreach ($row as $x => $mapString) {
				if ($mapString === $string) {
					$found->add(new Position($x, $y));
				}
			}
		}

		return $found;
	}

	public function isBlocked(Position $nextPosition): bool {
		return ($this->mapArray[$nextPosition->getY()][$nextPosition->getX()] ?? '') === '#';
	}

	public function dump(): void {
		dump(collect($this->mapArray)
			->map(fn (array $line) => join('', $line))
			->join("\n"));
	}
}
