<?php

namespace App\Day6;

class Guard {

	public function __construct(
		protected Map $map,
		protected Position $position
	)
	{
	}

	public function getPosition(): Position
	{
		return $this->position;
	}

	public function setPosition(Position $position): void
	{
		$this->position = $position;
	}

	public function move(): void {
		$nextPosition = $this->position->getNext();

		if ($this->map->isBlocked($nextPosition)) {
			$this->position = $this->position->turn();
		} else {
			$this->position = $nextPosition;
		}
	}
}
