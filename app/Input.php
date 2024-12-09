<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Input {

	private string $input;

	public function __construct(string $input) {
		$this->input = trim($input);
	}

	public static function forDay(int $day): self {
		$string = File::get(base_path("storage/inputs/day{$day}.txt"));

		if (is_null($string)) {
			throw new \Exception('File not found or empty');
		}

		return new self($string);
	}

	public function linesAsArray(): array {
		return explode("\n", $this->input);
	}

	public function linesAsCollection(): Collection {
		return collect($this->linesAsArray());
	}

	public function asString(): string {
		return $this->input;
	}

	public function asTwoDimensionalArray(): array {
		$lines = $this->linesAsArray();
		return array_map(static fn(string $line): array => str_split($line, 1), $lines);
	}

	public function asCoordinateKeyedArray(): array {
		$array = [];

		foreach ($this->linesAsCollection() as $y => $line) {
			for ($x = 0; $x < strlen($line); $x++) {
				$array["{$x}.{$y}"] = substr($line, $x, 1);
			}
		}

		return $array;
	}
}
