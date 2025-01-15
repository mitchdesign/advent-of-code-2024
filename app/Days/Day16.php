<?php

namespace App\Days;

use App\Day16\PathState;
use App\Input;
use Illuminate\Support\Collection;

class Day16 extends Day {

    protected array $map;
    protected string $start;
    protected string $end;

    protected ?int $goal = null;
    protected Collection $optimalPaths;

    protected array $visited;
    protected \SplPriorityQueue $queue;

    protected const array DIRECTIONS = [
        ['N', 0, -1],
        ['E', 1, 0],
        ['S', 0, 1],
        ['W', -1, 0],
    ];

    public function solve1(Input $input) : int {
        $this->setUpFromInput($input);

        while ($this->queue->valid()) {
            $current = $this->queue->extract();

            if ($current->location === $this->end) {
                return $current->score;
            }

            foreach ($this->getNextOptions($current) as $next) {
                $this->addToQueue($next);
            }
        }

        return 0;
    }

    public function solve2(Input $input) : int {
        $this->setUpFromInput($input);

        while ($this->queue->valid()) {
            $current = $this->queue->extract();

            if ($current->location === $this->end && (is_null($this->goal) || $current->score <= $this->goal)) {
                $this->optimalPaths->push($current->path);
                $this->goal = $current->score;
                continue;
            }

            foreach ($this->getNextOptions($current) as $next) {
                $this->addToQueue($next);
            }
        }

        return $this->optimalPaths->flatten()
            ->map(static fn(string $loc) : string => substr($loc, 0, -1))
            ->uniqueStrict()
            ->count();
    }

    protected function setUpFromInput(Input $input) : void {
        $this->map = $input->asCoordinateKeyedArray();
        $this->start = array_search('S', $this->map);
        $this->end = array_search('E', $this->map);

        $this->visited = [];
        $this->queue = new \SplPriorityQueue();
        $this->optimalPaths = collect();

        $current = new PathState($this->start, 'E', 0, [$this->start . 'E']);
        $this->addToQueue($current);
    }

    protected function addToQueue(PathState $state) : void {
        $this->markVisited($state);

        $priority = $this->getPriority($state->location, $state->score);
        $this->queue->insert($state, $priority);
    }

    protected function getPriority(string $location, int $score) : int {
        [$x, $y] = explode('.', $location);
        [$xEnd, $yEnd] = explode('.', $this->end);

        return -1 * ($score + abs($x - $xEnd) + abs($y - $yEnd));
    }

    protected function getNextOptions(PathState $current) : array {
        [$x, $y] = explode('.', $current->location);

        $options = [];

        foreach (self::DIRECTIONS as [$nextDirection, $dx, $dy]) {
            $nextLocation = ($x + $dx) . '.' . ($y + $dy);
            if ($this->map[$nextLocation] !== '.' && $this->map[$nextLocation] !== 'E') {
                continue;
            }

            $nextScore = $current->score + 1 + ($nextDirection === $current->direction ? 0 : 1000);

            $existingScore = $this->getVisitedScore($nextLocation, $nextDirection);
            if ($existingScore && $existingScore < $nextScore) {
                continue;
            }

            $nextPath = $current->path;
            $nextPath[] = $nextLocation . $nextDirection;

            $options[] = new PathState($nextLocation, $nextDirection, $nextScore, $nextPath);
        }

        return $options;
    }

    protected function markVisited(PathState $state) : void {
        $this->visited[$state->location . $state->direction] = $state->score;
    }

    protected function getVisitedScore($location, $direction) : ?int {
        return $this->visited[$location . $direction] ?? null;
    }

    protected function dumpMap() : void {
        $map = $this->map;

        foreach ($this->optimalPaths as $path) {
            foreach ($path as $pos) {
                $pos = substr($pos, 0, -1);
                $map[$pos] = 'o';
            }
        }

        $print = [];
        foreach ($map as $pos => $value) {
            [$x, $y] = explode('.', $pos);
            $print[$y][$x] = $value;
        }
        foreach ($print as $line) {
            dump(join('', $line));
        }
    }
}
