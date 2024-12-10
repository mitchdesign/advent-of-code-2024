<?php

namespace App\Day6;

use Illuminate\Support\Collection;

class Position {

	const int UP = 1;
	const int RIGHT = 2;
	const int DOWN = 3;
	const int LEFT = 4;

	public function __construct(
		protected int $x,
		protected int $y,
		protected ?int $direction = null
	)
	{
	}

	public function setDirection(int $direction): self
	{
		$this->direction = $direction;
		return $this;
	}

	public function getX(): int
	{
		return $this->x;
	}

	public function getY(): int
	{
		return $this->y;
	}

	public function getNext(): self
	{
		[$dx, $dy] = match ($this->direction) {
			self::UP => [0, -1],
			self::RIGHT => [1, 0],
			self::DOWN => [0, 1],
			self::LEFT => [-1, 0],
		};

		return new self($this->x + $dx, $this->y + $dy, $this->direction);
	}

	public function turn(): self
	{
		return new self($this->x, $this->y, match ($this->direction) {
			self::UP => self::RIGHT,
			self::RIGHT => self::DOWN,
			self::DOWN => self::LEFT,
			self::LEFT => self::UP,
		});
	}

	public function getHash(bool $includeDirection = false): string
	{
		return "{$this->x}.{$this->y}" . ($includeDirection ? ".{$this->direction}" : '');
	}

	public function getNeighbours(): Collection
	{
		return collect([
			clone ($this)->setDirection(Position::UP)->getNext(),
			clone ($this)->setDirection(Position::RIGHT)->getNext(),
			clone ($this)->setDirection(Position::DOWN)->getNext(),
			clone ($this)->setDirection(Position::LEFT)->getNext(),
		]);
	}
}