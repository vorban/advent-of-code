<?php

namespace App\Solutions;

class Solution202201
{
    public function silver(string $data): string
    {
        $lines = explode("\n", $data);

        $elves = collect();
        $current = 0;

        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                $elves->push($current);
                $current = 0;

                continue;
            }

            $current += intval($line);
        }

        return $elves->max();
    }

    public function gold(string $data): string
    {
        $lines = explode("\n", $data);

        $elves = collect();
        $current = 0;

        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                $elves->push($current);
                $current = 0;

                continue;
            }

            $current += intval($line);
        }

        return $elves->sortDesc()->slice(0, 3)->sum();
    }
}
