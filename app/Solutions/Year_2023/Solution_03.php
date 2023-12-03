<?php

namespace App\Solutions\Year_2023;

class Position
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public function toKey(): string
    {
        return "({$this->y}; {$this->x})";
    }
}

function isSymbolAroundNumber(string $number, int $x, int $y, array $lines): ?Position
{
    $length = strlen($number);
    if ($length === 0) {
        return null;
    }

    $positions = array_merge(
        [new Position($x - 1, $y), new Position($x + $length, $y)],
        array_map(fn ($i) => new Position($i, $y - 1), range($x - 1, $x + $length)),
        array_map(fn ($i) => new Position($i, $y + 1), range($x - 1, $x + $length)),
    );

    foreach ($positions as $position) {
        $char = $lines[$position->y][$position->x] ?? '.';
        if (! is_numeric($char) && $char !== '.') {
            return $position;
        }
    }

    return null;
}

function iterateOverLines(array $lines, callable $callback): void
{
    for ($y = 0; $y < count($lines); $y++) {
        for ($x = 0; $x < count($lines[$y]); $x++) {
            if ($lines[$y][$x] === '.') {
                continue;
            }

            $i = $x;
            $number = '';
            while (is_numeric($lines[$y][$i] ?? '.')) {
                $number .= $lines[$y][$i];
                $i++;
            }
            $position = isSymbolAroundNumber($number, $x, $y, $lines);
            if ($position !== null) {
                $callback($number, $x, $y, $position);
                $x += strlen($number) - 1;
            }
        }
    }
}

class Solution_03
{
    public function silver(string $data): string
    {
        $lines = array_filter(explode("\n", $data), fn ($line) => strlen($line) > 0);
        $lines = array_map(fn ($line) => str_split($line), $lines);

        $sum = 0;
        iterateOverLines($lines, function (string $number, int $x, int $y, Position $position) use (&$sum) {
            $sum += intval($number);
        });

        return $sum;
    }

    public function gold(string $data): string
    {
        $lines = array_filter(explode("\n", $data), fn ($line) => strlen($line) > 0);
        $lines = array_map(fn ($line) => str_split($line), $lines);

        $gears = [];
        iterateOverLines($lines, function (string $number, int $x, int $y, Position $position) use (&$gears) {
            if (! isset($gears[$position->toKey()])) {
                $gears[$position->toKey()] = [];
            }
            $gears[$position->toKey()][] = intval($number);
        });

        $sum = 0;
        foreach ($gears as $gear) {
            if (count($gear) !== 2) {
                continue;
            }
            $sum += array_product($gear);
        }

        return $sum;
    }
}
