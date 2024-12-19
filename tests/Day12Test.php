<?php

namespace Tests;

class Day12Test extends Day {

	function input(?int $puzzle): string
	{
		return 'RRRRIICCFF
RRRRIICCCF
VVRRRCCFFF
VVRCCCJFFF
VVVVCJJCFE
VVIVCCJJEE
VVIIICJJEE
MIIIIIJJEE
MIIISIJEEE
MMMISSJEEE';
	}

	function answer1(): int
	{
		return 1930;
	}

	function answer2(): int
	{
		return 1206;
	}
}
