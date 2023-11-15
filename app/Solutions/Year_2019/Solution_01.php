<?php

namespace App\Solutions\Year_2019;

function getFuelCost(int $mass, bool $recursive = false): int
{
    $theoretical = intval($mass / 3) - 2;

    if ($theoretical <= 0) {
        return 0;
    }

    if ($recursive) {
        return $theoretical + getFuelCost($theoretical, true);
    }

    return $theoretical;
}

class Solution_01
{
    public function silver(string $data): string
    {
        $lines = explode("\n", $data);

        $totalFuel = 0;
        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }

            $mass = intval($line);
            $totalFuel += getFuelCost($mass);
        }

        return $totalFuel;
    }

    public function gold(string $data): string
    {
        $lines = explode("\n", $data);

        $totalFuel = 0;
        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }

            $mass = intval($line);
            $totalFuel += getFuelCost($mass, true);
        }

        return $totalFuel;
    }
}
