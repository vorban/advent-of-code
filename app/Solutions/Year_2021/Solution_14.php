<?php

namespace App\Solutions\Year_2021;

function recurse(string $a, string $b, array $instructions, int $step, array &$keep): array
{
    if ($step === 0) {
        return [$a => 1];
    }

    if ($keep[$a.$b.$step] ?? false) {
        return $keep[$a.$b.$step];
    }

    $x = $instructions[$a.$b];
    $left = recurse($a, $x, $instructions, $step - 1, $keep);
    $right = recurse($x, $b, $instructions, $step - 1, $keep);

    $result = array_flip(array_merge(array_keys($left), array_keys($right)));

    foreach ($result as $key => $_) {
        $result[$key] = ($left[$key] ?? 0) + ($right[$key] ?? 0);
    }

    $keep[$a.$b.$step] = $result;

    return $result;
}

class Solution_14
{
    public function silver(string $data, int $steps = 10): string
    {
        $parts = explode("\n\n", $data);

        $polymer = $parts[0];
        $insersions = collect(explode("\n", $parts[1]))
            ->filter(fn ($line) => strlen($line) > 0)
            ->mapWithKeys(function ($line) {
                $parts = explode(' -> ', $line);

                return [$parts[0] => $parts[1]];
            })
            ->toArray();

        $totals = [];
        $keep = [];
        for ($i = 0; $i < strlen($polymer) - 1; $i++) {
            $counts = recurse($polymer[$i], $polymer[$i + 1], $insersions, $steps, $keep);
            foreach ($counts as $key => $value) {
                $totals[$key] = ($totals[$key] ?? 0) + $value;
            }
        }
        $totals[$polymer[strlen($polymer) - 1]] = ($totals[$polymer[strlen($polymer) - 1]] ?? 0) + 1;

        return max($totals) - min($totals);
    }

    public function gold(string $data): string
    {
        return $this->silver($data, 40);
    }
}
