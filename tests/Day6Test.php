<?php

namespace Tests;

class Day6Test extends Day {

	function input(?int $puzzle): string
	{
		return '....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...';
	}

	function answer1(): int
	{
		return 41;
	}

	function answer2(): int
	{
		return 6;
	}
}
