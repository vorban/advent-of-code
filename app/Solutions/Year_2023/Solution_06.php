<?php

namespace App\Solutions\Year_2023;

use Illuminate\Support\Str;

function ways_to_beat($time, $record): int
{
    $min_to_beat = 0;
    for ($i = 0; $i <= $time; $i++) {
        $distance = ($time - $i) * $i;
        if ($distance > $record) {
            $min_to_beat = $i;
            break;
        }
    }

    return $time + 1 - $min_to_beat * 2;
}

class Solution_06
{
    public function silver(string $data): string
    {
        [$times, $distances] = collect(explode("\n", $data))
            ->filter(fn ($line) => $line !== '')
            ->map(fn ($line) => Str::matchAll('(\d+)', $line)
                ->map(fn ($i) => intval($i))
            );

        $result = 1;
        foreach ($times as $key => $time) {
            $result *= ways_to_beat($time, $distances[$key]);
        }

        return $result;
    }

    public function gold(string $data): string
    {
        [$time, $record] = collect(explode("\n", $data))
            ->filter(fn ($line) => $line !== '')
            ->map(fn ($line) => intval(Str::matchAll('(\d+)', $line)->join('')));

        return ways_to_beat($time, $record);
    }
}
