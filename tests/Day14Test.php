<?php

namespace Tests;

use App\Days\Day14;

class Day14Test extends Day {

    public function setUp(): void
    {
        parent::setUp();

        Day14::setSize(11, 7); // size of example map
    }

	function input(?int $puzzle): string
	{
		return 'p=0,4 v=3,-3
p=6,3 v=-1,-3
p=10,3 v=-1,2
p=2,0 v=2,-1
p=0,0 v=1,3
p=3,0 v=-2,-2
p=7,6 v=-1,-3
p=3,0 v=-1,-2
p=9,3 v=2,3
p=7,3 v=-1,2
p=2,4 v=2,-3
p=9,5 v=-3,-3';
	}

	function answer1(): int
	{
		return 12;
	}

	function answer2(): int
	{
		return 0;
	}
}
