<?php

namespace App\Days;

use App\Input;

class Day13 extends Day {

	public function solve1(Input $input): int
	{
        $machines = self::parseMachines($input);

        return collect($machines)->map(static fn (array $machine): int => self::solve($machine))
            ->sum();
	}

	public function solve2(Input $input): int
	{
        $machines = self::parseMachines($input);

        return collect($machines)->map(static function (array $machine): int
        {
            $machine['px'] += 10000000000000;
            $machine['py'] += 10000000000000;

            return self::solve($machine);
        })->sum();
	}

    protected static function solve(array $m): int
    {
        /*                          To get to the prize P at (px,py),
         |               P          we first use button A a number of times to get to point X at (x,y).
         |              .           Then we use button B a number of times.
         |             . < b
         |            .             With a = dy(a) / dx(a) and b = dy(b) / dx (b),
         |       ....x              we know that the middle point, y = ax
         |   .... < a               and also y = py - b (px - x)
         +... ------------------
                                    This translates to:       py - (b.px)
                                                         x = -------------
                                                                 a - b
        */

        $a = $m['ay'] / $m['ax'];
        $b = $m['by'] / $m['bx'];

        $x = ($m['py'] - ($m['px'] * $b)) / ($a - $b);

        $numA = round($x / $m['ax']);
        $numB = round(($m['px'] - $x) / $m['bx']);

        if ($numA * $m['ax'] + $numB * $m['bx'] == $m['px']
            && $numA * $m['ay'] + $numB * $m['by'] == $m['py']) {
            return (int) (3 * $numA + $numB);
        }

        return 0;
    }

    protected static function parseMachines(Input $input): array
    {
        preg_match_all(
            '#Button A: X\+(?P<ax>\d+), Y\+(?P<ay>\d+)
Button B: X\+(?P<bx>\d+), Y\+(?P<by>\d+)
Prize: X=(?P<px>\d+), Y=(?P<py>\d+)#',
            $input->asString(),
            $matches
        );

        $machines = [];

        foreach ($matches[0] as $i => $_) {
            $machines[] = [
                'ax' => $matches['ax'][$i],
                'ay' => $matches['ay'][$i],
                'bx' => $matches['bx'][$i],
                'by' => $matches['by'][$i],
                'px' => $matches['px'][$i],
                'py' => $matches['py'][$i],
            ];
        }

        return $machines;
    }
}
