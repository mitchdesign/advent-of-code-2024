<?php

namespace App\Days;

use App\Day17\DoesNotMatchProgram;
use App\Input;

class Day17 extends Day {

    /*
     * Program:
     * B = A % 8
     * B = B XOR 2
     * C = A / 2 ^ B
     * B = B XOR C
     * B = B XOR 3
     * PRINT B % 8
     * A = A / 8
     * LOOP


    */

    protected int $a;
    protected int $b;
    protected int $c;

    protected array $mem;
    protected int $pointer = 0;

    protected string $out = '';
    protected ?string $matchProgram = null;

    public function solve1(Input $input) : string {
        $this->parseInput($input);

        return $this->solve();
    }

    public function solve2(Input $input) : string {
        $this->parseInput($input);
        [$b, $c] = [$this->b, $this->c];

        $this->matchProgram = join(',', $this->mem);

        $a = 0;
        do {
            // reset to program start state, except setting a
            [$this->a, $this->b, $this->c, $this->pointer] = [++$a, $b, $c, 0];
            try {
                $out = $this->solve();
            } catch (DoesNotMatchProgram) {
//                if (str_starts_with($this->out, '2,4,')) {dump ('miss ' . $a . ' : ' . $this->out); }
                continue;
            }
        } while (($out ?? null) !== $this->matchProgram);

        return $a;
    }

    protected function solve() : string {
        $this->out = '';

        while (!is_null($cmd = $this->mem[$this->pointer++] ?? null)) {
            $op = $this->mem[$this->pointer++];

            match ($cmd) {
                0 => $this->adv($op),
                1 => $this->bxl($op),
                2 => $this->bst($op),
                3 => $this->jnz($op),
                4 => $this->bxc($op),
                5 => $this->out($op),
                6 => $this->bdv($op),
                7 => $this->cdv($op),
            };
        }

        return $this->out;
    }

    /** A = floor (A / 2 ^ combo) -- shift A right 'combo' places */
    protected function adv(int $op) : void {
        $this->a = floor($this->a / pow(2, $this->combo($op)));
    }

    /** B = B XOR op */
    protected function bxl(int $op) : void {
        $this->b = $this->b ^ $op;
    }

    /** B = combo % 8 */
    protected function bst(int $op) : void {
        $this->b = $this->combo($op) % 8;
    }

    /** go back to start */
    protected function jnz(int $op) : void {
        if ($this->a !== 0) {
            $this->pointer = $op;
        }
    }

    /** B = B XOR C */
    protected function bxc(int $op) : void {
        $this->b = $this->b ^ $this->c;
    }

    /** print combo % 8 */
    protected function out(int $op) : void {
        $add = $this->combo($op) % 8;
        $this->out = $this->out === '' ? "{$add}" : "{$this->out},{$add}";

        if ($this->matchProgram && ! str_starts_with($this->matchProgram, $this->out)) {
            throw new DoesNotMatchProgram();
        }
    }

    /** B = floor (A / 2 ^ combo) -- B = A shifted right 'combo' places */
    protected function bdv(int $op) : void {
        $this->b = floor($this->a / pow(2, $this->combo($op)));
    }

    /** C = floor (A / 2 ^ combo) -- C = A shifted right 'combo' places */
    protected function cdv(int $op) : void {
        $this->c = floor($this->a / pow(2, $this->combo($op)));
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

    protected function parseInput(Input $input) : void {
        preg_match_all(
            '#Register A: (?P<a>\d+)
Register B: (?P<b>\d+)
Register C: (?P<c>\d+)

Program: (?P<mem>[\d\,]+)#',
            $input->asString(),
            $matches
        );

        $this->a = (int) $matches['a'][0];
        $this->b = (int) $matches['b'][0];
        $this->c = (int) $matches['c'][0];

        $this->mem = str($matches['mem'][0])
            ->explode(',')
            ->map(fn(string $num) : int => (int) $num)
            ->values()
            ->toArray();

        $this->pointer = 0;
        $this->out = '';
    }
}
