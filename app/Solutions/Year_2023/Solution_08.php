<?php

namespace App\Solutions\Year_2023;

use Illuminate\Support\Collection;

class Node
{
    public function __construct(
        public string $name,
        public string $left,
        public string $right,
    ) {
    }

    public static function fromString(string $str): self
    {
        $matches = [];
        preg_match('/(.{3}) = \((.{3}), (.{3})\)/', $str, $matches);

        return new self($matches[1], $matches[2], $matches[3]);
    }
}

function get_path_length(
    Collection $network,
    array $instructions,
    Node $start,
    bool $ends_with_z = false
) {
    $condition = fn ($node) => $ends_with_z ? $node->name[-1] === 'Z' : $node->name === 'ZZZ';
    $steps = 0;
    while ($condition($start) === false) {
        foreach ($instructions as $instruction) {
            if ($instruction === 'L') {
                $start = $network[$start->left];
            } else {
                $start = $network[$start->right];
            }

            $steps++;

            if ($condition($start) === true) {
                break;
            }
        }
    }

    return $steps;
}

function gcd(int $a, int $b): int
{
    // https://en.wikipedia.org/wiki/Euclidean_algorithm#Implementations
    while ($b !== 0) {
        $t = $b;
        $b = $a % $b;
        $a = $t;
    }

    return $a;
}

function lcm(int $a, int $b): int
{
    // https://en.wikipedia.org/wiki/Least_common_multiple#Calculation
    return ($a * $b) / gcd($a, $b);
}

function array_lcm(array $arr): int
{
    $lcm = 1;
    foreach ($arr as $el) {
        $lcm = lcm($lcm, $el);
    }

    return $lcm;
}

class Solution_08
{
    public function silver(string $data): string
    {
        $parts = explode("\n\n", $data);
        $instructions = str_split($parts[0]);

        $network = collect(explode("\n", $parts[1]))
            ->filter(fn ($line) => $line !== '')
            ->map(fn ($line) => Node::fromString($line))
            ->keyBy('name');

        return get_path_length($network, $instructions, $network['AAA']);
    }

    public function gold(string $data): string
    {
        $parts = explode("\n\n", $data);
        $instructions = str_split($parts[0]);

        $network = collect(explode("\n", $parts[1]))
            ->filter(fn ($line) => $line !== '')
            ->map(fn ($line) => Node::fromString($line))
            ->keyBy('name');

        $currents = $network->where(fn ($node) => $node->name[-1] == 'A');

        $paths = [];
        foreach ($currents as $current) {
            $paths[] = get_path_length($network, $instructions, $current, true);
        }

        return array_lcm($paths);
    }
}
