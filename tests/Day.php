<?php

namespace Tests;

use App\Input;
use Illuminate\Support\Arr;

abstract class Day extends TestCase
{
	private int $day;
	private string $dayClass;

	public function setUp(): void {
		$this->day = (int) preg_replace('#.*Day#', '', get_class($this));
		$this->dayClass = "App\Days\Day{$this->day}";
	}

	abstract function input(?int $puzzle): string|array;
    abstract function answer1(): mixed;
    abstract function answer2(): mixed;

	public function test_puzzle_1(): void
    {
        if ($this->answer1() === null) {
            $this->markTestSkipped();
        }
        $answers = Arr::wrap($this->answer1());
        $inputs = Arr::wrap($this->input(1));

        foreach ($inputs as $key => $input) {
            $this->assertEquals(
                $answers[$key],
                (new $this->dayClass)->solve1(new Input($input))
            );
        }
    }

	public function test_puzzle_2(): void
	{
        if ($this->answer2() === null) {
            $this->markTestSkipped();
        }
        $answers = Arr::wrap($this->answer2());
        $inputs = Arr::wrap($this->input(2));

        foreach ($inputs as $key => $input) {
            $this->assertEquals(
                $answers[$key],
                (new $this->dayClass)->solve2(new Input($input))
            );
        }
	}
}
