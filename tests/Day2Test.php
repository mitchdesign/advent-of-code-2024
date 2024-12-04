<?php

namespace Tests;

class Day2Test extends Day {

	function input(?int $puzzle) : string {
		return '7 6 4 2 1
1 2 7 8 9
9 7 6 2 1
1 3 2 4 5
8 6 4 4 1
1 3 6 7 9';
	}

	function answer1() : int {
		return 2;
	}

	function answer2() : int {
		return 4;
	}
}
