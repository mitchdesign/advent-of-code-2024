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
}
