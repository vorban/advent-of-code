<?php

namespace App\Solutions\Year_2021;

class Solution_01
{
    public function silver(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => intval($line));

        $increaseCount = 0;
        for ($i = 1; $i < $lines->count(); $i++) {
            if ($lines[$i] > $lines[$i - 1]) {
                $increaseCount++;
            }
        }

        return $increaseCount;
    }

    public function gold(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => intval($line));

        $increaseCount = 0;
        for ($i = 0; $i < $lines->count() + 3; $i++) {
            $current = $lines->get($i) + $lines->get($i + 1) + $lines->get($i + 2);
            $next = $lines->get($i + 1) + $lines->get($i + 2) + $lines->get($i + 3);
            if ($current < $next) {
                $increaseCount++;
            }
        }

        return $increaseCount;
    }
}
