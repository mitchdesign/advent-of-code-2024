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

	public function asTwoDimensionalArray(bool $ints = false): array {
		$lines = $this->linesAsArray();
		return array_map(
            static function(string $line) use ($ints): array {
                $vals = str_split($line, 1);
                if ($ints) {
                    $vals = array_map('intval', $vals);
                }
                return $vals;
            },
            $lines
        );
	}

	public function asCoordinateKeyedArray(bool $ints = false): array {
		$array = [];

		foreach ($this->linesAsCollection() as $y => $line) {
			for ($x = 0; $x < strlen($line); $x++) {
                $str = substr($line, $x, 1);
				$array["{$x}.{$y}"] = $ints ? (int) $str : $str;
			}
		}

		return $array;
	}
}
