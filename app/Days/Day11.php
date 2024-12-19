<?php

namespace App\Days;

use App\Input;

class Day11 extends Day {

    public \SplQueue $queue;
    public int $totalCount = 0;

	public function solve1(Input $input): int
	{
        $this->queue = new \SplQueue();

        foreach (explode(' ', $input->asString()) as $stone) {
            $this->queue->enqueue([(int) $stone, 0]);
        }

        $this->runQueue(25);

        return $this->totalCount;
	}

	public function solve2(Input $input): int
	{
        $this->queue = new \SplQueue();

        foreach (explode(' ', $input->asString()) as $stone) {
            $this->queue->push([(int) $stone, 0]);
        }

        $this->runQueue(75);

        return $this->totalCount;
	}

    protected function runQueue(int $iterations): void
    {
        while (! $this->queue->isEmpty()) {
            [$stone, $count] = $this->queue->pop();

            if ($count == $iterations) {
                $this->totalCount++;
            } else {
                $count++;

                foreach (self::handleStone($stone) as $stone) {
                    $this->queue->push([$stone, $count]);
                }
            }
        }
    }

    protected static function handleStone(int $stone): array {
        if ($stone === 0) {
            return [1];
        }

        $string = (string) $stone;

        if (strlen($string) % 2 === 0) {
            $halflen = strlen($string) / 2;
            return [(int) substr($string, 0, $halflen), (int) substr($string, -1 * $halflen)];
        }

        return [$stone * 2024];
    }
}
