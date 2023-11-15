<?php

namespace App\Solutions\Year_2021;

class Point
{
    public function __construct(public int $x, public int $y, public int $value)
    {
    }

    public function equals(Point $point): bool
    {
        return $this->x === $point->x && $this->y === $point->y;
    }

    public function getNeighborsOfBasin(array $map, array $neighbors = []): array
    {
        foreach ($neighbors as $neighbor) {
            if ($this->equals($neighbor)) {
                return $neighbors; // already checked this area
            }
        }

        $neighbors[] = $this;

        $height = count($map);
        $width = count($map[0]);

        $immediateNeighbors = collect();
        if ($this->y > 0) {
            $immediateNeighbors->push($map[$this->y - 1][$this->x]);
        }
        if ($this->y < $height - 1) {
            $immediateNeighbors->push($map[$this->y + 1][$this->x]);
        }
        if ($this->x > 0) {
            $immediateNeighbors->push($map[$this->y][$this->x - 1]);
        }
        if ($this->x < $width - 1) {
            $immediateNeighbors->push($map[$this->y][$this->x + 1]);
        }

        $immediateNeighbors = $immediateNeighbors->filter(fn ($n) => $n->value !== 9 && $n->value > $this->value);

        foreach ($immediateNeighbors as $n) {
            $neighbors = $n->getNeighborsOfBasin($map, $neighbors);
        }

        return $neighbors;
    }
}

function parseMap(string $data): array
{
    return collect(explode("\n", $data))
        ->filter(fn ($line) => strlen($line) > 0)
        ->map(fn ($line, $y) => collect(str_split($line))
            ->map(fn ($char, $x) => new Point($x, $y, intval($char)))
            ->all()
        )->all();
}

function findLowPoints(array $map): array
{
    $height = count($map);
    $width = count($map[0]);

    $low_points = [];
    foreach ($map as $y => $row) {
        foreach ($row as $x => $point) {
            $top = $y > 0 ? $map[$y - 1][$x] : new Point($x, $y - 1, 10);
            $bot = $y < $height - 1 ? $map[$y + 1][$x] : new Point($x, $y + 1, 10);
            $left = $x > 0 ? $map[$y][$x - 1] : new Point($x - 1, $y, 10);
            $right = $x < $width - 1 ? $map[$y][$x + 1] : new Point($x + 1, $y, 10);

            if ($point->value < $top->value && $point->value < $bot->value && $point->value < $left->value && $point->value < $right->value) {
                $low_points[] = $point;
            }
        }
    }

    return $low_points;
}

class Solution_09
{
    public function silver(string $data): string
    {
        $map = parseMap($data);

        $low_points = findLowPoints($map);

        return collect($low_points)->map(fn ($el) => $el->value + 1)->sum();
    }

    public function gold(string $data): string
    {
        $map = parseMap($data);
        $low_points = findLowPoints($map);

        $basins = collect();

        foreach ($low_points as $point) {
            $basins->push($point->getNeighborsOfBasin($map));
        }

        return $basins->sortByDesc(fn ($basin) => count($basin))->take(3)->reduce(fn ($c, $i) => $c * count($i), 1);
    }
}
