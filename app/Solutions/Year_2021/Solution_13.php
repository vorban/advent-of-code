<?php

namespace App\Solutions\Year_2021;

function parse_sheet(string $data): array
{
    $sheet = [];
    $lines = explode("\n", $data);
    foreach ($lines as $line) {
        $parts = explode(',', $line);
        $x = intval($parts[0]);
        $y = intval($parts[1]);

        // make sure we have enough rows and columns
        for ($i = count($sheet); $i <= $y; $i++) {
            $sheet[$i] = [];
        }
        foreach ($sheet as $i => $row) {
            for ($j = count($row); $j <= $x; $j++) {
                $sheet[$i][$j] = 0;
            }
        }

        $sheet[$y][$x] = 1;
    }

    return $sheet;
}

function parse_instructions(string $data): array
{
    $lines = array_filter(explode("\n", $data), fn ($line) => strlen($line) > 0);
    $instructions = [];

    foreach ($lines as $line) {
        $parts = explode('=', $line);
        $instructions[] = (object) [
            'axis' => substr($parts[0], -1),
            'index' => intval($parts[1]),
        ];
    }

    return $instructions;
}

function print_sheet(array $sheet, string $axis = null, int $axis_index = null)
{
    foreach ($sheet as $y => $row) {
        foreach ($row as $cell) {
            echo sprintf('%s ', $cell > 0 ? '#' : '.');
        }
        if ($axis === 'y' && $axis_index === $y) {
            echo ' <';
        }
        echo PHP_EOL;
    }
    if ($axis === 'x') {
        echo str_repeat(' ', $axis_index * 2).'^'.PHP_EOL;
    }
}

function fold_x(array &$sheet, int $index)
{
    $folded = [];
    foreach ($sheet as $i => $row) {
        $folded[$i] = array_splice($sheet[$i], $index);
        $folded[$i] = array_splice($folded[$i], 1);
        $folded[$i] = array_reverse($folded[$i]);
    }

    foreach ($folded as $y => $row) {
        foreach ($row as $x => $cell) {
            $sheet[$y][$x] |= $cell;
        }
    }
}

function fold_y(array &$sheet, int $index)
{
    $folded = array_splice($sheet, $index);
    $folded = array_splice($folded, 1);
    $folded = array_reverse($folded);

    foreach ($folded as $y => $row) {
        foreach ($row as $x => $cell) {
            $sheet[$y][$x] += $cell;
        }
    }
}

class Solution_13
{
    public function silver(string $data): string
    {
        $parts = explode("\n\n", $data);

        $sheet = parse_sheet($parts[0]);
        $instructions = parse_instructions($parts[1]);

        if ($instructions[0]->axis === 'x') {
            fold_x($sheet, $instructions[0]->index);
        } else {
            fold_y($sheet, $instructions[0]->index);
        }

        return array_reduce($sheet, fn ($carry, $row) => $carry + count(array_filter($row, fn ($el) => $el > 0)), 0);
    }

    public function gold(string $data): string
    {
        $parts = explode("\n\n", $data);

        $sheet = parse_sheet($parts[0]);
        $instructions = parse_instructions($parts[1]);

        foreach ($instructions as $instruction) {
            if ($instruction->axis === 'x') {
                fold_x($sheet, $instruction->index);
            } else {
                fold_y($sheet, $instruction->index);
            }
        }

        print_sheet($sheet);

        return array_reduce($sheet, fn ($carry, $row) => $carry + count(array_filter($row, fn ($el) => $el > 0)), 0);
    }
}
