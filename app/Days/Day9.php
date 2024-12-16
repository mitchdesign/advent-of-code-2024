<?php

namespace App\Days;

use App\Day9\Block;
use App\Input;
use Illuminate\Support\Collection;

class Day9 extends Day {

	public function solve1(Input $input): int
	{
        $disk = self::parseInputPerChar($input);

        while ($freePos = array_search(null, $disk, true)) {
            $disk[$freePos] = array_pop($disk);
        }

        return self::getChecksum($disk);
    }

	public function solve2(Input $input): int
	{
        $disk = self::parseInputPerBlock($input);

        for ($i = $disk->count() - 1; $i >= 0; $i--) {
            /** @var Block $block */
            $block = $disk->get($i);
            $size = $block->size;

            /** @var Block $moveToBlock */
            $moveToBlock = $disk->first(static fn (Block $b, int $j): bool => $j < $i && $b->space >= $size);

            if ($moveToBlock) {
                $block->moveIntoBlock($moveToBlock);
            }
        }

        $diskSectors = $disk->map(static fn (Block $b): array => $b->toSectors())
            ->flatten()
            ->values()
            ->toArray();

        return self::getChecksum($diskSectors);
    }

    protected static function parseInputPerChar(Input $input): array
    {
        $input = $input->asString();
        $disk = [];
        $block = -1;

        for ($i = 0; $i < strlen($input); $i++) {
            if ($i % 2 === 0) {
                $block++;
                $isBlock = true;
            } else {
                $isBlock = false;
            }
            $length = (int) substr($input, $i, 1);
            for ($j = 1; $j <= $length; $j++) {
                $disk[] = $isBlock ? $block : null;
            }
        }

        return $disk;
    }

    protected static function parseInputPerBlock(Input $input): Collection
    {
        $input = collect($input->asTwoDimensionalArray()[0]);
        $disk = collect();

        // make sure count is even because we need every second one for the space after a block
        if ($input->count() % 2 === 1) {
            $input->push(0);
        }

        $blockCount = 0;
        while ($blockInput = $input->shift(2)) {
            $disk->put($blockCount, new Block($blockCount, $blockInput[0], $blockInput[1]));
            $blockCount++;
        }

        return $disk;
    }

    public static function getChecksum(array $disk): int
    {
        $total = 0;

        foreach ($disk as $index => $value) {
            $total += $index * $value;
        }

        return $total;
    }
}
