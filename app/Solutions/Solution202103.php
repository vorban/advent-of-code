<?php

namespace App\Solutions;

class Solution202103
{
    public function silver(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $bitcount = strlen($lines[0]);

        $gamma = '';
        $epsilon = '';
        for ($i = 0; $i < $bitcount; $i++) {
            $bit = $lines->map(fn ($line) => $line[$i])
                ->countBy()
                ->sortDesc()
                ->keys()
                ->first();
            $gamma .= $bit;
            $epsilon .= $bit == '0' ? '1' : '0';
        }

        $gamma = intval($gamma, 2);
        $epsilon = intval($epsilon, 2);

        return $gamma * $epsilon;
    }

    public function gold(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $bitcount = strlen($lines[0]);

        $oxygen = '';
        $filtered = collect($lines->all());
        for ($i = 0; $i < $bitcount; $i++) {
            $bit = $filtered->map(fn ($line) => $line[$i])
                ->countBy()
                ->sortDesc();

            $bit = $bit->first() == $bit->last() ? '1' : $bit->keys()->first();
            $oxygen .= $bit;
            $filtered = $filtered->filter(fn ($line) => $line[$i] == $bit);

            if ($filtered->count() == 1) {
                $oxygen = $filtered->first();
                break;
            }
        }

        $carbon = '';
        $filtered = collect($lines->all());
        for ($i = 0; $i < $bitcount; $i++) {
            $bit = $filtered->map(fn ($line) => $line[$i])
                ->countBy()
                ->sort();

            $bit = $bit->first() == $bit->last() ? '0' : $bit->keys()->first();
            $carbon .= $bit;
            $filtered = $filtered->filter(fn ($line) => $line[$i] == $bit);

            if ($filtered->count() == 1) {
                $carbon = $filtered->first();
                break;
            }
        }

        $oxygen = intval($oxygen, 2);
        $carbon = intval($carbon, 2);

        return $oxygen * $carbon;
    }
}
