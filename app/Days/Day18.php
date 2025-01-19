<?php

namespace App\Days;

use App\Input;

class Day18 extends Day {

    protected int $size = 70;
    protected int $skip = 1024;
    protected array $map;
    protected array $end;
    protected \SplPriorityQueue $queue;
    protected array $visited = [];

    public function __construct() {
        parent::__construct();

        if (env('APP_ENV') === 'testing') {
            $this->size = 6;
            $this->skip = 12;
        }
    }

    public function solve1(Input $input) : int {
        return $this->solve($input, $this->skip);
    }

    protected function solve(Input $input, int $size) : int {
        $this->visited = [];
        $this->map = $this->parseMap($input, $size);
        $this->queue = new \SplPriorityQueue();
        $this->end = [$this->size, $this->size];

        $this->addOptionsFrom([0, 0], 0);

        while ($this->queue->valid()) {
            [$next, $nextScore] = $this->queue->extract();

            if ($next === $this->end) {
                return $nextScore;
            }

            $this->addOptionsFrom($next, $nextScore);
        }

        return 0;
    }

    public function solve2(Input $input) : string {
        $bytes = $input->linesAsCollection();

        // value which still leads to good path
        $lower = $this->skip;
        // value which does not lead to good path anymore
        $upper = count($bytes);

        while ($upper - $lower > 1) {
            $try = (int) round(($upper + $lower) / 2, 0);
            $outcome = $this->solve($input, $try);
            if (($outcome = $this->solve($input, $try)) === 0) {
                $upper = $try;
            } else {
                $lower = $try;
            }
        }

        // Once we get to the point where upper and lower only differ by 1,
        // the lower is the one that's ok, and the upper is the one that fails.
        // When it fails when we take e.g. 100 items, that means that item 0..98 are good,
        // and item 99 (the 100th) is the bad one.
        return $bytes[$upper - 1];
    }

    protected function addOptionsFrom($position, $score) : void {
        $this->visited[join('.', $position)] = true;

        foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dx, $dy]) {
            [$x, $y] = [$position[0] + $dx, $position[1] + $dy];
            if ($x < 0 || $y < 0 || $x > $this->size || $y > $this->size) {
                continue;
            }
            if (isset($this->visited["{$x}.{$y}"])) {
                continue;
            }
            if (isset($this->map[$y][$x]) && $this->map[$y][$x] !== '#') {
                $this->queue->insert([[$x, $y], $score + 1], -1 * (($score + 1) + $this->distance([$x, $y], $this->end)));
            }
        }
    }

    protected function distance($a, $b): int
    {
        return abs($a[0] - $b[0]) + abs($a[1] - $b[1]);
    }

    protected function parseMap(Input $input, int $limit) : array {
        $map = array_fill_keys(range(0, $this->size), array_fill_keys(range(0, $this->size), '.'));

        $input->linesAsCollection()->slice(0, $limit)->each(function(string $line) use (&$map) : void {
            [$x, $y] = explode(',', $line);
            $map[$y][$x] = '#';
        });

        return $map;
    }
}
