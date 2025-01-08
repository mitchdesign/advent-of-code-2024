<?php

namespace App\Days;

use App\Input;

class Day16 extends Day {

    protected array $map;
    protected string $end;
    protected array $visited = [];

    protected \SplPriorityQueue $queue;

    protected const array DIRECTIONS = [
        ['N', 0, -1],
        ['E', 1, 0],
        ['S', 0, 1],
        ['W', -1, 0],
    ];

    public function solve1(Input $input) : int {
        $this->map = $input->asCoordinateKeyedArray();
        $start = array_search('S', $this->map);
        $this->end = array_search('E', $this->map);

        $this->queue = new \SplPriorityQueue();

        $this->addToQueue($start, 'E', 0);

        while ($this->queue->valid()) {
            [$current, $direction, $score] = $this->queue->extract();

            if ($current === $this->end) {
                return $score;
            }

            foreach ($this->getNextOptions($current, $direction, $score) as $nextWithScore) {
                $this->addToQueue(...$nextWithScore);
            }
        }

        return 0;
    }

    protected function addToQueue($location, $direction, $score) : void {
        $this->markVisited($location, $direction);

        $priority = $this->getPriority($location, $score);
        $this->queue->insert([$location, $direction, $score], $priority);
    }

    protected function getPriority(string $location, int $score) : int {
        [$x, $y] = explode('.', $location);
        [$xEnd, $yEnd] = explode('.', $this->end);

        return -1 * ($score + abs($x - $xEnd) + abs($y - $yEnd));
    }

    protected function getNextOptions(string $location, string $direction, int $score) : array {
        [$x, $y] = explode('.', $location);

        $options = [];

        foreach (self::DIRECTIONS as $directionOption) {
            [$nextDirection, $dx, $dy] = $directionOption;

            $nextLocation = $x + $dx . '.' . $y + $dy;

            if (($this->map[$nextLocation] === '.' || $this->map[$nextLocation] === 'E')
                && !$this->isVisited($nextLocation, $nextDirection)) {
                $options[] = [
                    $nextLocation,
                    $nextDirection,
                    $score + 1 + ($nextDirection === $direction ? 0 : 1000),
                ];
            }
        }

        return $options;
    }

    protected function markVisited($current, $direction) : void {
        $this->visited[$current . $direction] = true;
    }

    protected function isVisited($current, $direction) : bool {
        return isset($this->visited[$current . $direction]);
    }

    public function solve2(Input $input) : int {
        return 0;
    }
}
