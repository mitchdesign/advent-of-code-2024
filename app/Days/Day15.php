<?php

namespace App\Days;

use App\Day15\CannotPush;
use App\Input;

class Day15 extends Day {

    protected const array UP = [0, -1];
    protected const array DOWN = [0, 1];
    protected const array LEFT = [-1, 0];
    protected const array RIGHT = [1, 0];

    protected array $map;
    protected int $x;
    protected int $y;

    public function solve1(Input $input) : int {
        [$map, $steps] = str($input->asString())->split("#\n\n#");
        $this->map = (new Input($map))->asTwoDimensionalArray();

        $steps = str_split(str_replace("\n", '', trim($steps)), 1);

        [$this->x, $this->y] = self::getStart($this->map);
        $this->set([$this->x, $this->y], '.');

        foreach ($steps as $step) {
            $this->step1($step);
        }

        return $this->getSum();
    }

    protected function step1(string $step) : void {
        // look until we find a non moveable obstacle
        for ($dist = 1; true; $dist++) {
            $vector = $this->getVector($step, $dist);

            $found = $this->get($vector);

            if ($found === '#') {
                return;
            }

            if ($dist === 1) {
                $first = $vector;
            }

            if ($found === '.') {
                // if free space is right next to start ($dist === 1) then we just move
                if ($dist === 1) {
                    $this->move($vector);
                    return;
                }

                // not right next, then we move to the first and put a new O at the end position
                $this->set($vector, 'O');
                $this->set($first, '.');
                $this->move($first);
                return;
            }
        }
    }

    public function solve2(Input $input) : int {
        [$map, $steps] = str($input->asString())->split("#\n\n#");

        $map = self::doubleMap($map);
        $this->map = (new Input($map))->asTwoDimensionalArray();

        $steps = str_split(str_replace("\n", '', trim($steps)), 1);

        [$this->x, $this->y] = self::getStart($this->map);
        $this->set([$this->x, $this->y], '.');

        foreach ($steps as $step) {
            $this->step2($step);
        }

        return $this->getSum();
    }

    protected function step2(string $step) : void {
        // keep the original array, because we'll make changes to boxes as they move.
        // and we might find out later that moving is not possible.
        $mapBefore = $this->map;

        try {
            $this->push($step);
        } catch (CannotPush) {
            $this->map = $mapBefore;
        }
    }

    protected function push(string $step) {
        $vector = $this->getVector($step);
        $found = $this->get($vector);

        [$x, $y] = $vector;

        if ($found === '#') {
            throw new CannotPush();
        }

        if ($found === '.') {
            $this->move([$x, $y]);
            return;
        }

        match ($step) {
            '<' => $this->pushBoxLeft($x - 1, $y),
            '>' => $this->pushBoxRight($x, $y),
            '^' => $this->pushBoxUp($found === '[' ? $x : $x - 1, $y),
            'v' => $this->pushBoxDown($found === '[' ? $x : $x - 1, $y),
        };

        $this->move([$x, $y]);
    }

    protected static function doubleMap(string $map) : string {
        $out = '';

        foreach (explode("\n", $map) as $line) {
            foreach (str_split(trim($line), 1) as $char) {
                $out .= match ($char) {
                    '#' => '##',
                    'O' => '[]',
                    '.' => '..',
                    '@' => '@.',
                };
            }
            $out .= "\n";
        }

        return trim($out);
    }

    protected static function getStart(array $map) : array {
        foreach ($map as $y => $line) {
            foreach ($line as $x => $value) {
                if ($value === '@') {
                    return [$x, $y];
                }
            }
        }
    }

    protected function getSum() : int {
        $sum = 0;

        foreach ($this->map as $y => $line) {
            foreach ($line as $x => $value) {
                if ($value === 'O' || $value === '[') {
                    $sum += 100 * $y + $x;
                }
            }
        }

        return $sum;
    }

    protected function getVector(string $step, int $dist = 1) : array {
        [$dx, $dy] = match ($step) {
            '^' => self::UP,
            'v' => self::DOWN,
            '<' => self::LEFT,
            '>' => self::RIGHT,
        };

        return [
            $this->x + $dist * $dx,
            $this->y + $dist * $dy,
        ];
    }

    protected function set(array $position, string $value) : void {
        [$x, $y] = $position;
        $this->map[$y][$x] = $value;
    }

    protected function move(array $position) : void {
        [$this->x, $this->y] = $position;
    }

    protected function get(array $position) : string {
        [$x, $y] = $position;

        return $this->map[$y][$x];
    }

    protected function pushBoxLeft(int $x, int $y) : void {
        $found = $this->get([$x - 1, $y]);

        if ($found === '#') {
            throw new CannotPush();
        }

        if ($found !== '.') {
            $this->pushBoxLeft($x - 2, $y);
        }

        $this->clearBox($x, $y);
        $this->setBox($x - 1, $y);
    }

    protected function pushBoxRight(int $x, int $y) : void {
        $found = $this->get([$x + 2, $y]);

        if ($found === '#') {
            throw new CannotPush();
        }

        if ($found !== '.') {
            $this->pushBoxRight($x + 2, $y);
        }

        $this->clearBox($x, $y);
        $this->setBox($x + 1, $y);
    }

    protected function pushBoxUp(int $x, int $y) : void {
        $found1 = $this->get([$x, $y - 1]);
        $found2 = $this->get([$x + 1, $y - 1]);

        if ($found1 === '#' || $found2 === '#') {
            throw new CannotPush();
        }

        if ($found1 === '[') {
            $this->pushBoxUp($x, $y - 1);
        } else {
            if ($found1 === ']') {
                $this->pushBoxUp($x - 1, $y - 1);
            }
            if ($found2 === '[') {
                $this->pushBoxUp($x + 1, $y - 1);
            }
        }

        $this->clearBox($x, $y);
        $this->setBox($x, $y - 1);
    }

    protected function pushBoxDown(int $x, int $y) : void {
        $found1 = $this->get([$x, $y + 1]);
        $found2 = $this->get([$x + 1, $y + 1]);

        if ($found1 === '#' || $found2 === '#') {
            throw new CannotPush();
        }

        if ($found1 === '[') {
            $this->pushBoxDown($x, $y + 1);
        } else {
            if ($found1 === ']') {
                $this->pushBoxDown($x - 1, $y + 1);
            }
            if ($found2 === '[') {
                $this->pushBoxDown($x + 1, $y + 1);
            }
        }

        $this->clearBox($x, $y);
        $this->setBox($x, $y + 1);
    }

    protected function clearBox(int $x, int $y) : void {
        $this->set([$x, $y], '.');
        $this->set([$x + 1, $y], '.');
    }

    protected function setBox(int $x, int $y) : void {
        $this->set([$x, $y], '[');
        $this->set([$x + 1, $y], ']');
    }
}
