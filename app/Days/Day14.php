<?php

namespace App\Days;

use App\Day14\Robot;
use App\Input;
use Illuminate\Support\Collection;

class Day14 extends Day
{
    protected static int $width = 101;
    protected static int $height = 103;
    protected static ?array $emptyImage = null;

    public static function setSize(int $width, int $height): void
    {
        self::$width = $width;
        self::$height = $height;
    }

    public function solve1(Input $input): int
    {
        $robots = self::parseRobots($input);

        $robots->each(function(Robot $robot) : void {
            $robot->move(100);
        });

        return $this->calculateSafetyfactor($robots);
    }

    public function solve2(Input $input): int
    {
        $robots = self::parseRobots($input);

        $count = 0;

        while ($count < 10000) {
            $robots->each(function(Robot $robot) : void {
                $robot->move(1);
            });
            $count++;

            if (self::looksLikeATree($robots)) {
                return $count;
            }
        }

        return 0;
    }

    protected static function parseRobots(Input $input): Collection
    {
        return $input->linesAsCollection()->map(static function(string $line) : Robot {
            preg_match('#p=(?P<x>[0-9\-]+),(?P<y>[0-9\-]+) v=(?P<vx>[0-9\-]+),(?P<vy>[0-9\-]+)#', $line, $matches);

            return new Robot(
                (int) $matches['x'],
                (int) $matches['y'],
                (int) $matches['vx'],
                (int) $matches['vy'],
                self::$width,
                self::$height,
            );
        });
    }

    protected function calculateSafetyfactor(Collection $robots): int
    {
        $current = 1;

        foreach (self::getQuadrants($robots) as $count) {
            $current *= $count;
        }

        return $current;
    }

    protected static function getQuadrants(Collection $robots): Collection
    {
        return $robots->countBy(fn(Robot $robot) : int => $robot->getQuadrant())->forget(0);
    }


    protected function looksLikeATree(Collection $robots) : bool {
        if (!self::$emptyImage) {
            self::$emptyImage = array_fill_keys(
                range(0, self::$height * self::$width),
                ' '
            );
        }

        $image = self::$emptyImage;

        foreach ($robots as $robot) {
            $image[$robot->y * self::$width + $robot->x] = 'X';
        }

        $image = join('', $image);

        // we assume the tree looks like a pattern of one, then three, then five, then seven robots in a triangle shape
        return preg_match('# X .* XXX .* XXXXX .* XXXXXXX #', $image);
    }
}
