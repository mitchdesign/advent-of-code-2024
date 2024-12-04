<?php

namespace App\Console\Commands;

use App\Input;
use Illuminate\Console\Command;

class RunDayCommand extends Command
{
    protected $signature = 'run:day {day} {puzzle?}';

    protected $description = 'Run a day\'s puzzles';

    public function handle()
    {
		$start = microtime(true);

		$day = $this->argument('day');

		$puzzle = $this->argument('puzzle');

		$puzzles = $puzzle === null
			? collect([1, 2])
			: collect([(int) $puzzle]);

		$className = "App\Days\Day{$day}";
		if (!class_exists($className)) {
			$this->fail("Day {$day} doesn't exist");
		}

		$day = new $className();

		$input = Input::forDay($day->getDay());

		if ($puzzles->contains(1)) {
			$this->line("Day {$day->getDay()}, puzzle 1: " . $day->solve1($input));
		}

		if ($puzzles->contains(2)) {
			$this->line("Day {$day->getDay()}, puzzle 2: " . $day->solve2($input));
		}

		$end = microtime(true);

		$this->line('Finished in ' . round($end - $start, 3) . ' seconds');
    }
}
