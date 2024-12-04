<?php

namespace Tests;

class Day3Test extends Day {

	function input(?int $puzzle) : string {
		return match($puzzle) {
			1 => 'xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))',
			2 => "xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))",
		};
	}

	function answer1() : int {
		return 161;
	}

	function answer2() : int {
		return 48;
	}
}
