<?php

namespace App\Solutions\Year_2023;

use Illuminate\Support\Collection;
use Illuminate\Support\Number;

function getSum(array|Collection $data): int
{
    $sum = 0;
    foreach ($data as $line) {
        $first = array_key_first($line);
        $last = array_key_last($line);

        if ($first === null || $last === null) {
            continue;
        }

        echo $line[$first].$line[$last]."\n";

        $sum += intval($line[$first].$line[$last]);
    }

    return $sum;
}

class Solution_01
{
    public function silver(string $data): string
    {
        $numbers = range(1, 9);
        $data = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => array_filter(str_split($line), fn ($char) => in_array($char, $numbers)));

        return getSum($data);
    }

    public function gold(string $data): string
    {
        $data = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $numbers = range(1, 9);
        $spelled = array_map(fn ($number) => Number::spell($number), range(1, 9));

        foreach ($data as $line_index => $line) {
            $normalized = [];
            $chars = str_split($line);
            for ($i = 0; $i < count($chars); $i++) {
                if (in_array($chars[$i], $numbers)) {
                    $normalized[] = $chars[$i];

                    continue;
                }

                foreach ($spelled as $key => $spell) {
                    $word = '';
                    $length = 0;
                    for ($j = $i; $j < count($chars); $j++) {
                        $word .= $chars[$j];
                        $length++;

                        if (! str_starts_with($spell, $word)) {
                            break;
                        }
                        if ($spell === $word) {
                            $normalized[] = $key + 1;
                            break 2;
                        }
                    }
                }
            }
            echo sprintf('%s => %s', $line, implode(' ', $normalized))."\n";
            $data[$line_index] = $normalized;
        }

        return getSum($data);
    }
}
