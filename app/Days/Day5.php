<?php

namespace App\Days;

use App\Input;
use Illuminate\Support\Collection;

class Day5 extends Day {

	public function solve1(Input $input): int
	{
		[$rules, $updates] = self::parseInput($input);

		return $updates->filter(static fn (array $update): bool => self::isUpdateValid($update, $rules))
			->map(static fn (array $update): int => $update[floor(count($update) / 2)]) // map to middle value
			->sum();
	}

	public function solve2(Input $input): int
	{
		[$rules, $updates] = self::parseInput($input);

		return $updates->filter(static fn (array $update): bool => ! self::isUpdateValid($update, $rules))
			->map(static fn (array $update): array => self::getValidOrderOfUpdate($update, $rules))
			->map(static fn (array $update): int => $update[floor(count($update) / 2)]) // map to middle value
			->sum();
	}

	protected static function parseInput(Input $input): array
	{
		$lines = $input->linesAsCollection();

		return [
			self::parseRules($lines->takeUntil(static fn (string $line): bool => $line === '')),
			$lines->reverse()->takeUntil(static fn (string $line): bool => $line === '')
				->map(static fn (string $update): array => explode(',', $update))
		];
	}

	// Normalize the rules into keys "a.b" and "b.a", to be used directly in sorting callback for speed
	protected static function parseRules(Collection $rules): array
	{
		return $rules->mapWithKeys(static function (string $line): array {
			$pages = explode('|', $line);
			return [
				"{$pages[0]}.{$pages[1]}" => -1,
				"{$pages[1]}.{$pages[0]}" => 1,
			];
		})->toArray();
	}

	protected static function isUpdateValid(array $update, array $rules): bool {
		return $update === self::getValidOrderOfUpdate($update, $rules);
	}

	protected static function getValidOrderOfUpdate(array $update, array $rules): array
	{
		// The update is valid when we get the same order when we sort.
		// We sort by applying the rules, and keeping non-found combinations in their original order
		// (This may be needed because sorting algorithms do not guarantee order of "equal" items)
		$order = array_flip($update);

		usort($update, static function(int $a, int $b) use ($rules, $order): int {
			return $rules["{$a}.{$b}"] ?? $order[$a] - $order[$b];
		});

		return $update;
	}
}
