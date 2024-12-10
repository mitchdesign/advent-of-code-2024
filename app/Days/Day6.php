<?php

namespace App\Days;

use App\Day6\Guard;
use App\Day6\Map;
use App\Day6\Position;
use App\Day6\VisitedAlready;
use App\Input;
use Illuminate\Support\Collection;

class Day6 extends Day {

	protected Collection $pathPositions;
	protected int $loopsFound = 0;
	protected array $visitedPositions = [];

	public function __construct()
	{
		parent::__construct();
		$this->pathPositions = collect();
	}

	public function solve1(Input $input): int
	{
		[$map, $guard] = self::parseInput($input);

		while ($map->contains($position = $guard->getPosition())) {
			$this->pathPositions->put($position->getHash(), $position);
			$guard->move();
		}

		return $this->pathPositions->count();
	}

	public function solve2(Input $input): int
	{
		self::parseInput($input);
		// run puzzle 1 to get the path
		$this->solve1($input);

		foreach ($this->pathPositions as $blockPosition) {
			[$map, $guard] = self::parseInput($input);
			$map->mark($blockPosition, '#');

			$this->visitedPositions = [];

			try {
				while ($map->contains($position = $guard->getPosition())) {
					$hash = $position->getHash(true);

					if (isset($this->visitedPositions[$hash])) {
						throw new VisitedAlready();
					}

					$this->visitedPositions[$hash] = 1;
					$guard->move();
				}
			}
			catch (VisitedAlready) {
				$this->loopsFound++;
			}
		}

		return $this->loopsFound;
	}

	protected static function parseInput(Input $input): array
	{
		$map = new Map($input->asTwoDimensionalArray());

		/* @var \App\Day6\Position $position */
		$position = $map->findAll('^')->sole();
		$position->setDirection(Position::UP);

		$guard = new Guard($map, $position);

		return [
			$map,
			$guard,
		];
	}
}