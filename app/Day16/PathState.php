<?php

namespace App\Day16;

class PathState {

    public function __construct(
        public string $location,
        public string $direction,
        public int $score,
        public ?array $path = null,
    ) {
    }

}
