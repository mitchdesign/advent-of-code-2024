<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day10 extends Day {

    protected array $map;
    protected Collection $foundPaths;

	public function solve1(Input $input): int
	{
        $this->solve($input);

        return $this->foundPaths->map(static fn (array $validPath): string => reset($validPath) . '.' . end($validPath))
            ->unique()
            ->count();
	}

	public function solve2(Input $input): int
	{
        $this->solve($input);

        return $this->foundPaths->count();
	}

    protected function solve(Input $input): void
    {
        $this->foundPaths = collect();

        $this->map = $input->asTwoDimensionalArray(ints: true);

        $trailheads = collect($input->asCoordinateKeyedArray(ints: true))
            ->filter(static fn (int $val) => $val === 0)
            ->keys();

        foreach ($trailheads as $trailhead) {
            $this->findPaths(0, ...explode('.', $trailhead));
        }
    }

    protected function findPaths(int $val, int $x, int $y, array $path = []): void
    {
        if (($this->map[$y][$x] ?? null) !== $val) {
            return;
        }

        $path[] = "$x.$y";

        if ($val === 9) {
            $this->foundPaths->push($path);
            return;
        }

        $this->findPaths($val + 1, $x - 1, $y, $path);
        $this->findPaths($val + 1, $x + 1, $y, $path);
        $this->findPaths($val + 1, $x, $y - 1, $path);
        $this->findPaths($val + 1, $x, $y + 1, $path);
    }
}
