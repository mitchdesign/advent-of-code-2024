<?php

namespace Tests;

class Day10Test extends Day {

	function input(?int $puzzle): string
	{
		return '89010123
78121874
87430965
96549874
45678903
32019012
01329801
10456732';
	}

	function answer1(): int
	{
		return 36;
	}

	function answer2(): int
	{
		return 81;
	}
}
