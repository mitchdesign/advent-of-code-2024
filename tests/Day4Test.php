<?php

namespace Tests;

class Day4Test extends Day {

	function input(?int $puzzle) : string {
		return 'MMMSXXMASM
MSAMXMSMSA
AMXSXMAAMM
MSAMASMSMX
XMASAMXAMM
XXAMMXXAMA
SMSMSASXSS
SAXAMASAAA
MAMMMXMMMM
MXMXAXMASX';
	}

	function answer1() : int {
		return 18;
	}

	function answer2() : int {
		return 9;
	}
}
