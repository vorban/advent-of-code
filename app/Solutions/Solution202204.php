<?php

namespace App\Solutions;

class Range
{
    public function __construct(public int $start, public int $end)
    {
    }

    public static function fromString(string $range): self
    {
        $parts = explode('-', $range);

        return new self(intval($parts[0]), intval($parts[1]));
    }

    public function contains(Range $range): bool
    {
        return $this->start <= $range->start && $this->end >= $range->end;
    }

    public function overlaps(Range $range): bool
    {
        return $this->start <= $range->end && $this->end >= $range->start
            || $range->start <= $this->end && $range->end >= $this->start;
    }
}

class Solution202204
{
    public function silver(string $data): string
    {
        return collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(function ($line) {
                $ranges = explode(',', $line);

                $left = explode('-', $ranges[0]);
                $right = explode('-', $ranges[1]);

                return (object) [
                    'left' => new Range(intval($left[0]), intval($left[1])),
                    'right' => new Range(intval($right[0]), intval($right[1])),
                ];
            })
            ->filter(fn ($pair) => $pair->left->contains($pair->right) || $pair->right->contains($pair->left))
            ->count();
    }

    public function gold(string $data): string
    {
        return collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(function ($line) {
                $parts = explode(',', $line);

                return (object) [
                    'left' => Range::fromString($parts[0]),
                    'right' => Range::fromString($parts[1]),
                ];
            })
            ->filter(fn ($pair) => $pair->left->overlaps($pair->right))
            ->count();
    }
}
