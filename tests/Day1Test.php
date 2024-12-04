<?php

namespace Tests;

class Day1Test extends Day {

	function input(?int $puzzle) : string {
		return '3   4
4   3
2   5
1   3
3   9
3   3';
	}

	function answer1() : int {
		return 11;
	}

	function answer2() : int {
		return 31;
	}
}
