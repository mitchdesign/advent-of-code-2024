<?php

namespace App\Day14;

class Robot {

    public int $centerX;
    public int $centerY;

    public function __construct(
        public int $x,
        public int $y,
        public int $vx,
        public int $vy,
        public int $width,
        public int $height,
    ) {
        $this->centerX = ($this->width - 1) / 2;
        $this->centerY = ($this->height - 1) / 2;
    }

    public function move(int $int = 1): void
    {
        $this->x += $int * $this->vx;
        $this->y += $int * $this->vy;

        $this->normalize();
    }

    private function normalize(): void
    {
        $this->x = self::getInRange($this->x, $this->width);
        $this->y = self::getInRange($this->y, $this->height);
    }

    public function getQuadrant(): int
    {
        return match ([
            $this->x <=> $this->centerX,
            $this->y <=> $this->centerY,
        ]) {
            [-1, -1] => 1,
            [1, -1] => 2,
            [-1, 1] => 3,
            [1, 1] => 4,
            default => 0,
        };
    }

    protected static function getInRange(int $val, int $max): int
    {
        while ($val >= $max) {
            $val -= $max;
        }

        while ($val < 0) {
            $val += $max;
        }

        return $val;
    }
}
