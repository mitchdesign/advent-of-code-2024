<?php

namespace App\Days;

use App\Day17\Computer;
use App\Day17\DoesNotMatchProgram;
use App\Input;

class Day17 extends Day {

    public function solve1(Input $input) : string {
        $computer = Computer::fromInput($input);

        return $computer->run();
    }

    public function solve2(Input $input) : string {
        $computer = Computer::fromInput($input);
        $program = $computer->getProgram();

        $options = [0];

        for ($count = 0; $count < count($program); $count++) {
            $options = $this->findNextOptions($count, $options, $program, $computer);
        }

        return reset($options);
    }

    protected function findNextOptions(int $count, array $options, array $program, Computer $computer) : array {
        $newOptions = [];

        foreach (range(0, 7) as $next) {
            foreach ($options as $option) {
                $newOptions[] = 8 * $option + $next;
            }
        }

        $expectedOutput = join(',', array_slice($program, -1 * ($count + 1)));

        $newOptions = array_filter(
            $newOptions,
            function(int $option) use ($computer, $expectedOutput) {
                $testComputer = clone($computer);
                $testComputer->setA($option);
                $output = $testComputer->run();
                return $output === $expectedOutput;
            }
        );

        return $newOptions;
    }
}
