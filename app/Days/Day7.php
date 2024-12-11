<?php

namespace App\Days;

use App\Input;

class Day7 extends Day {

	protected static bool $useThirdOperator = false;

	public function solve1(Input $input): int
	{
		return $input->linesAsCollection()
			->map(static fn (string $eq): int => self::getEquationTotal($eq))
			->sum();
	}

	public function solve2(Input $input): int
	{
		self::$useThirdOperator = true;

		return $this->solve1($input);
	}

	protected static function getEquationTotal(string $equation): int
	{
		$numbers = array_map('intval', preg_split('/:? /', $equation));
		$answer = array_shift($numbers);
		$currentValue = array_shift($numbers);

		return self::isEquationValid($currentValue, $numbers, $answer)
			? $answer
			: 0;
	}

	protected static function isEquationValid(int $currentValue, array $remainingNumbers, int $answer): bool
	{
		if (count($remainingNumbers) === 0) {
			return $currentValue === $answer;
		}

		$nextNumber = array_shift($remainingNumbers);

		return self::isEquationValid($currentValue + $nextNumber, $remainingNumbers, $answer)
			|| self::isEquationValid($currentValue * $nextNumber, $remainingNumbers, $answer)
			|| (self::$useThirdOperator && self::isEquationValid((int) $currentValue . $nextNumber, $remainingNumbers, $answer));
	}
}
