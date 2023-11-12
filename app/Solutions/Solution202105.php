<?php

namespace App\Solutions;

enum Orientation
{
    case VERTICAL;
    case HORIZONTAL;
    case DIAGONAL;
}

class Point
{
    public function __construct(public int $x, public int $y)
    {
    }

    public static function fromString(string $point): self
    {
        $parts = explode(',', $point);

        return new self(intval($parts[0]), intval($parts[1]));
    }
}

class Line
{
    public function __construct(public Point $start, public Point $end, public Orientation $orientation)
    {
    }

    public static function fromString(string $line): self
    {
        $parts = explode(' -> ', $line);

        $start = Point::fromString($parts[0]);
        $end = Point::fromString($parts[1]);

        // Determine orientation + sort points so that start
        // is always the left most (or top most) point
        $orientation = Orientation::DIAGONAL;
        if ($start->x == $end->x) {
            $orientation = Orientation::VERTICAL;
            if ($start->y > $end->y) {
                [$start, $end] = [$end, $start];
            }
        } elseif ($start->y == $end->y) {
            $orientation = Orientation::HORIZONTAL;
            if ($start->x > $end->x) {
                [$start, $end] = [$end, $start];
            }
        } else {
            if ($start->x > $end->x) {
                [$start, $end] = [$end, $start];
            }
        }

        return new self($start, $end, $orientation);
    }
}

function expand(array &$map, Line $line)
{
    $x_ranges = [$line->start->x, $line->end->x];
    $y_ranges = [$line->start->y, $line->end->y];

    // expand rows
    foreach ($y_ranges as $y) {
        for ($i = count($map); $i <= $y; $i++) {
            $map[$i] = [];
        }
    }

    // expand columns of each row
    foreach ($map as $y => $row) {
        foreach ($x_ranges as $x) {
            for ($i = count($row); $i <= $x; $i++) {
                $map[$y][$i] = 0;
            }
        }
    }
}

function fill_horizontal(array &$map, Line $line)
{
    for ($x = $line->start->x; $x <= $line->end->x; $x++) {
        $map[$line->start->y][$x] += 1;
    }
}

function fill_vertical(array &$map, Line $line)
{
    for ($y = $line->start->y; $y <= $line->end->y; $y++) {
        $map[$y][$line->start->x] += 1;
    }
}

function fill_diagonal(array &$map, Line $line)
{
    $y = $line->start->y;
    for ($x = $line->start->x; $x <= $line->end->x; $x++) {
        $map[$y][$x] += 1;
        $y += $line->start->y < $line->end->y ? 1 : -1;
    }
}

class Solution202105
{
    public function silver(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Line::fromString($line));

        $map = [];
        foreach ($lines as $line) {
            expand($map, $line);
            match ($line->orientation) {
                Orientation::VERTICAL => fill_vertical($map, $line),
                Orientation::HORIZONTAL => fill_horizontal($map, $line),
                Orientation::DIAGONAL => null,
            };
        }

        return collect($map)
            ->flatMap(fn ($row) => $row)
            ->filter(fn ($value) => $value > 1)
            ->count();
    }

    public function gold(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Line::fromString($line));

        $map = [];
        foreach ($lines as $line) {
            expand($map, $line);
            match ($line->orientation) {
                Orientation::VERTICAL => fill_vertical($map, $line),
                Orientation::HORIZONTAL => fill_horizontal($map, $line),
                Orientation::DIAGONAL => fill_diagonal($map, $line),
            };
        }

        return collect($map)
            ->flatMap(fn ($row) => $row)
            ->filter(fn ($value) => $value > 1)
            ->count();
    }
}
