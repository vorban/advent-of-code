<?php

namespace App\Solutions\Year_2021;

class Solution_07
{
    public function silver(string $data): string
    {
        $crabs = collect(explode(',', explode("\n", $data)[0]))
            ->map(fn ($position) => intval($position));

        $align = 0;

        $min_fuel = PHP_INT_MAX;

        for ($i = 0; $i < $crabs->max(); $i++) {
            $fuel = 0;
            foreach ($crabs as $crab) {
                $fuel += abs($crab - $i);
            }
            if ($fuel < $min_fuel) {
                $min_fuel = $fuel;
                $align = $i;
            }
        }

        return sprintf('%d fuel needed to align with %d', $min_fuel, $align);
    }

    public function gold(string $data): string
    {
        $crabs = collect(explode(',', explode("\n", $data)[0]))
            ->map(fn ($position) => intval($position));

        $align = 0;

        $min_fuel = PHP_INT_MAX;

        for ($i = 0; $i < $crabs->max(); $i++) {
            $fuel = 0;
            foreach ($crabs as $crab) {
                $n = abs($crab - $i);
                $fuel += $n * ($n + 1) / 2; // https://en.wikipedia.org/wiki/Triangular_number#Formula
            }
            if ($fuel < $min_fuel) {
                $min_fuel = $fuel;
                $align = $i;
            }
        }

        return sprintf('%d fuel needed to align with %d', $min_fuel, $align);
    }
}
