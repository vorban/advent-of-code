<?php

namespace App\Solutions\Year_2023;

use Illuminate\Support\Str;

function ways_to_beat($time, $record): int
{
    // canonical quadratic equation
    // (t - x) * x > r
    // (t - x) * x - r > 0
    // -xx + tx - r > 0      <---- this is ax^2 + bx + c > 0
    //                             with a = -1; b = t; c = -1

    // delta = bb - 4ac
    // delta = tt - 4*-1*-r = tt - 4r
    $delta = $time * $time - 4 * $record;
    // I'm assuming $delta > 0, otherwise the puzzle is impossible to solve
    // because $delta == 0 means 1 root where equation == 0, so cannot beat
    // the score. $delta < 0 means we never even match the record.

    // a is negative, so the curve is a peak as such: /\, not a bowl \/
    // so the first time it crossed 0 is when

    // root1 = (-b - sqrt(delta)) / 2a; b = t; a = -1
    // root2 = (-b + sqrt(delta)) / 2a
    $left_root = (0 - $time - sqrt($delta)) / -2;
    $right_root = (0 - $time + sqrt($delta)) / -2;

    // $the 1st root is where we match the record
    $root = intval(min($left_root, $right_root));

    // ways not to beat = $root on the left
    //                  + $root on the right
    //                  - 1 index shenanigans
    return $time - $root * 2 - 1;
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
