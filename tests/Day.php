<?php

namespace Tests;

use App\Input;

abstract class Day extends TestCase
{
	private int $day;
	private string $dayClass;

	public function setUp(): void {
		$this->day = (int) preg_replace('#.*Day#', '', get_class($this));
		$this->dayClass = "App\Days\Day{$this->day}";
	}

	abstract function input(): string;
	abstract function answer1(): int;
	abstract function answer2(): int;

	public function test_solve1(): void
    {
	    $this->assertEquals(
			$this->answer1(),
		    (new $this->dayClass)->solve1(new Input($this->input()))
	    );
    }

	public function test_solve2(): void
	{
		$this->assertEquals(
			$this->answer2(),
			(new $this->dayClass)->solve2(new Input($this->input()))
		);
	}
}
