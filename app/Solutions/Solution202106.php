<?php

namespace App\Solutions;

class Solution202106
{
    public function silver(string $data): string
    {
        $lanternfish = explode(',', collect(explode("\n", $data))->first());
        foreach ($lanternfish as $i => $fish) {
            $lanternfish[$i] = intval($fish);
        }

        for ($iteration = 0; $iteration < 80; $iteration++) {
            $newborns = 0;
            foreach ($lanternfish as $i => $fish) {
                if ($fish == 0) {
                    $lanternfish[$i] = 6;
                    $newborns++;
                } else {
                    $lanternfish[$i] = $fish - 1;
                }
            }
            for ($i = 0; $i < $newborns; $i++) {
                $lanternfish[] = 8;
            }
        }

        return count($lanternfish);
    }

    public function gold(string $data): string
    {
        $lanternfish = explode(',', collect(explode("\n", $data))->first());
        foreach ($lanternfish as $i => $fish) {
            $lanternfish[$i] = intval($fish);
        }

        $life = array_map(fn ($el) => 0, range(0, 8));
        foreach ($lanternfish as $i => $fish) {
            $life[$fish]++;
        }

        for ($iteration = 0; $iteration < 256; $iteration++) {
            $newborns = 0;
            foreach ($life as $hp => $count) {
                if ($hp == 0) {
                    $newborns = $count;
                    $life[0] = 0;

                    continue;
                }

                $life[$hp - 1] += $count;
                $life[$hp] = 0;
            }
            $life[8] = $newborns;
            $life[6] += $newborns;
        }

        return collect($life)->sum();
    }
}
