<?php

namespace App\Day9;

class Block {

    protected array $blocksInSpace = [];

    public function __construct(
        public int $blockNumber,
        public int $size,
        public int $space)
    {
    }

    public function moveIntoBlock(Block $moveToBlock): void
    {
        $clone = clone($this);
        $clone->blocksInSpace = [];
        $clone->space = 0;

        $this->blockNumber = 0;

        $moveToBlock->addBlockInSpace($clone);
    }

    public function addBlockInSpace(Block $block): void
    {
        $this->blocksInSpace[] = $block;
        $this->space -= $block->size;
    }

    public function toSectors(): array
    {
        $sectors = array_fill(0, $this->size, $this->blockNumber);

        foreach ($this->blocksInSpace as $block) {
            $sectors = array_merge($sectors, $block->toSectors());
        }

        return array_merge($sectors, array_fill(0, $this->space, 0));
    }
}
