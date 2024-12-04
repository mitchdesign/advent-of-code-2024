<?php

namespace App\Days;

use App\Input;

abstract class Day {

	private int $day;

	public function __construct()
	{
		$this->day = (int) preg_replace('#.*Day#', '', get_class($this));
	}

	public function getDay(): int
	{
		return $this->day;
	}

	public abstract function solve1(Input $input): int;

	public abstract function solve2(Input $input): int;
}
