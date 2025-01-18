<?php

namespace Tests;

class Day17Test extends Day {

	function input(?int $puzzle): string
	{
        if ($puzzle === 1) {
            return 'Register A: 729
Register B: 0
Register C: 0

Program: 0,1,5,4,3,0';
        }

        return 'Register A: 2024
Register B: 0
Register C: 0

Program: 0,3,5,4,3,0';

	}

	function answer1(): string
	{
        return '4,6,3,5,6,3,5,2,1,0';
	}

	function answer2(): int
	{
		return 117440;
	}
}
