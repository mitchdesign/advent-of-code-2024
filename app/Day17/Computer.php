<?php

namespace App\Day17;

use App\Input;

class Computer {

    protected int $programLength = 0;
    protected array $output;
    protected int $pointer;

    public function __construct(
        protected int $a,
        protected int $b,
        protected int $c,
        protected array $program,
    ) {
        $this->programLength = count($program);
    }

    public function run() : string {
        $this->output = [];
        $this->pointer = 0;

        while (!$this->isFinished()) {
            $this->executeInstruction();
        }

        return $this->getOutput();
    }

    public function setA(int $a) : void {
        $this->a = $a;
    }

    public function isFinished() : bool {
        return $this->pointer >= $this->programLength;
    }

    public function getOutput() : string {
        return join(',', $this->output);
    }

    public function getProgram() : array {
        return $this->program;
    }

    public function executeInstruction() : void {
        $ins = $this->program[$this->pointer++];
        $op = $this->program[$this->pointer++];

        // 2,4, b = (0..7) from a
        // 1,2, b = b xor 2
        // 7,5, c = a / ..b
        // 4,7, b = b xor c
        // 1,3, b = b xor 3
        // 5,5, print out (0..7) from b
        // 0,3, a = a / 8
        // return

        // SUMMARY OF PROGRAM: Devide A by 8. Base some values on A. Print out.
        // So for each iteration all other registers depend on A

        match ($ins) {
            0 => $this->a = floor($this->a / pow(2, $this->combo($op))), // adv
            1 => $this->b = $this->b ^ $op, // bxl
            2 => $this->b = $this->combo($op) % 8, // bst
            3 => $this->a === 0 || $this->pointer = $op, //jnz
            4 => $this->b = $this->b ^ $this->c, // bxc
            5 => array_push($this->output, $this->combo($op) % 8), // out
            6 => $this->b = floor($this->a / pow(2, $this->combo($op))), // bdv
            7 => $this->c = floor($this->a / pow(2, $this->combo($op))), // cdv
        };
    }

    protected function combo(int $op) : int {
        return match ($op) {
            0, 1, 2, 3 => $op,
            4 => $this->a,
            5 => $this->b,
            6 => $this->c,
            7 => new \Exception('Reserved operand 7 should not occur'),
        };
    }

    public static function fromInput(Input $input) : self {
        preg_match_all(
            '#Register A: (?P<a>\d+)
Register B: (?P<b>\d+)
Register C: (?P<c>\d+)

Program: (?P<mem>[\d\,]+)#',
            $input->asString(),
            $matches
        );

        $a = (int) $matches['a'][0];
        $b = (int) $matches['b'][0];
        $c = (int) $matches['c'][0];

        $program = str($matches['mem'][0])
            ->explode(',')
            ->map(fn(string $num) : int => (int) $num)
            ->values()
            ->toArray();

        return new self($a, $b, $c, $program);
    }
}
