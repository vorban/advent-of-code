<?php

namespace App\Solutions\Year_2023;

class RangePieces
{
    public function __construct(
        public ?Range $head,
        public ?Range $body,
        public ?Range $tail,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('%s %s %s', $this->head ?? '{}', $this->body ?? '{}', $this->tail ?? '{}');
    }
}

class Range
{
    public function __construct(
        public int $start,
        public int $count,
        public int $dest = 0,
    ) {
    }

    public function first(): int
    {
        return $this->start;
    }

    public function last(): int
    {
        return $this->start + $this->count - 1;
    }

    public function overlaps(self $other): bool
    {
        return $this->first() <= $other->last() && $other->first() <= $this->last();
    }

    public function cut(self $knife): RangePieces
    {
        $head = null;
        $body = clone $this;
        $tail = null;

        if ($this->first() < $knife->first()) {
            $head = new self($this->first(), $knife->first() - $this->first());
            $body->start = $knife->first();
            $body->count -= $head->count;
        }
        if ($knife->last() < $this->last()) {
            $tail = new self($knife->last() + 1, $this->last() - $knife->last());
            $body->count -= $this->last() - $knife->last();
        }

        return new RangePieces($head, $body, $tail);
    }

    public function toDestination(self $range): self
    {
        $diff = abs($range->first() - $this->first());

        return new self($range->dest + $diff, $this->count);
    }

    public function __toString()
    {
        return sprintf('{%d-%d}', $this->first(), $this->last());
    }

    public static function fromString(string $str): self
    {
        $parts = explode(' ', $str);

        return new self(
            intval($parts[1]),
            intval($parts[2]),
            intval($parts[0]),
        );
    }
}

class Map
{
    public function __construct(
        public string $from,
        public string $to,
        public array $ranges,
    ) {
    }

    public static function fromString(string $str): self
    {
        $lines = array_filter(explode("\n", $str), fn ($line) => $line != '');
        $parts = explode('-', explode(' ', $lines[0])[0]);

        return new self(
            $parts[0],
            $parts[2],
            array_map(fn ($line) => Range::fromString($line), array_slice($lines, 1)),
        );
    }
}

function solve(array $seeds, array $maps): int
{
    $min = PHP_INT_MAX;

    foreach ($seeds as $seed) {
        $unprocessed = [$seed];
        $processed = [];

        // echo sprintf("##### Seed %s #####\n", $seed);

        foreach ($maps as $map) {
            // echo sprintf("===== Map %s -> %s =====\n", $map->from, $map->to);
            foreach ($map->ranges as $range) {
                // echo sprintf("----- Range %s -----\n", $range);
                for ($i = 0; $i < count($unprocessed); $i++) {
                    // echo sprintf("..... (%d) %s .....\n", $i, $unprocessed[$i]);
                    if ($range->overlaps($unprocessed[$i])) {
                        $pieces = $unprocessed[$i]->cut($range);

                        if ($pieces->head) {
                            $unprocessed[] = $pieces->head;
                        }

                        if ($pieces->tail) {
                            $unprocessed[] = $pieces->tail;
                        }

                        // echo sprintf("  - %s is cut to %s and moved to  %s\n", $unprocessed[$i], $pieces, $pieces->body->toDestination($range));

                        $processed[] = $pieces->body->toDestination($range);
                        $unprocessed = array_merge(array_slice($unprocessed, 0, $i), array_slice($unprocessed, $i + 1));
                        $i--; // because we are removing the body, we need to reprocess the new one at the same index
                    }
                }
            }
            // the whole seed was processed for this map and becomes the unprocessed for the next map
            // the remaining unprocessed ranges are the ones that were not covered by ranges
            $unprocessed = array_merge($unprocessed, $processed);
            $processed = [];
            // echo sprintf("Seed %s: %s\n", $seed, implode(' ', $unprocessed));
        }

        // check for minimum
        foreach ($unprocessed as $range) {
            if ($range->first() < $min) {
                $min = $range->first();
            }
        }

    }

    return $min;
}

class Solution_05
{
    public function silver(string $data, bool $seedsAreRanges = false): string
    {
        $sections = explode("\n\n", $data);
        $seed_numbers = array_map(fn ($seed) => intval($seed), array_slice(explode(' ', $sections[0]), 1));

        $seeds = [];
        for ($i = 0; $i < count($seed_numbers); $i += $seedsAreRanges ? 2 : 1) {
            $seeds[] = new Range(
                $seed_numbers[$i],
                $seedsAreRanges ? $seed_numbers[$i + 1] : 1,
            );
        }

        $maps = [];
        foreach (array_slice($sections, 1) as $map) {
            $map = Map::fromString($map);
            $maps[] = $map;
        }

        return solve($seeds, $maps);
    }

    public function gold(string $data): string
    {
        return $this->silver($data, true);
    }
}
