<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDayCommand extends Command
{
    protected $signature = 'make:day {day}';
    protected $description = 'Create files for a new day';

    public function handle()
    {
		$day = $this->argument('day');

		if (! is_numeric($day)) {
			$this->fail('Day is not a number');
		}

		$day = (int) $day;

		$stubs = [
			'day.stub' => "app/Days/Day{$day}.php",
			'input.stub' => "storage/inputs/day{$day}.txt",
			'test.stub' => "tests/Day{$day}Test.php",
		];

		foreach ($stubs as $path) {
			if (File::exists(base_path($path))) {
				$this->fail("File {$path} already exists");
			}
		}

		foreach ($stubs as $stub => $location) {
			$string = File::get(base_path("storage/stubs/{$stub}"));
			$string = str_replace('##DAY##', $day, $string);
			File::put(base_path($location), $string);
		}
    }
}
