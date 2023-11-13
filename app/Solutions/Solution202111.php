<?php

namespace App\Solutions;

class Octopus
{
    public function __construct(public int $x, public int $y, public int $energy, public bool $flashed = false)
    {
    }
}

function reset_octopus(array &$map)
{
    foreach ($map as $row) {
        foreach ($row as $octopus) {
            $octopus->flashed = false;
        }
    }
}

function flash(array &$map, Octopus $octopus)
{
    if ($octopus->flashed) {
        return;
    }

    $octopus->energy++;

    if ($octopus->energy < 10) {
        return;
    }

    $octopus->flashed = true;
    $octopus->energy = 0;

    // flash neighbors
    $neighbors = collect([
        $map[$octopus->y - 1][$octopus->x] ?? null,
        $map[$octopus->y + 1][$octopus->x] ?? null,
        $map[$octopus->y][$octopus->x - 1] ?? null,
        $map[$octopus->y][$octopus->x + 1] ?? null,

        $map[$octopus->y + 1][$octopus->x + 1] ?? null,
        $map[$octopus->y + 1][$octopus->x - 1] ?? null,
        $map[$octopus->y - 1][$octopus->x + 1] ?? null,
        $map[$octopus->y - 1][$octopus->x - 1] ?? null,
    ])->filter(fn ($octopus) => $octopus !== null);

    foreach ($neighbors as $neighbor) {
        flash($map, $neighbor);
    }
}

class Solution202111
{
    public function silver(string $data): string
    {
        $map = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line, $y) => collect(str_split($line))
                ->map(fn ($char, $x) => new Octopus($x, $y, intval($char)))
                ->all()
            )->all();

        $flashes = 0;
        for ($i = 0; $i < 100; $i++) {
            foreach ($map as $row) {
                foreach ($row as $octopus) {
                    flash($map, $octopus);
                }
            }
            foreach ($map as $row) {
                foreach ($row as $octopus) {
                    if ($octopus->flashed) {
                        $flashes++;
                    }
                }
            }
            reset_octopus($map);
        }

        return $flashes;
    }

    public function gold(string $data): string
    {
        $map = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line, $y) => collect(str_split($line))
                ->map(fn ($char, $x) => new Octopus($x, $y, intval($char)))
                ->all()
            )->all();

        $octopus_count = count($map) * count($map[0]);

        for ($i = 0; $i < 1000; $i++) {
            $flashes = 0;
            foreach ($map as $row) {
                foreach ($row as $octopus) {
                    flash($map, $octopus);
                }
            }
            foreach ($map as $row) {
                foreach ($row as $octopus) {
                    if ($octopus->flashed) {
                        $flashes++;
                    }
                }
            }
            reset_octopus($map);
            if ($flashes == $octopus_count) {
                return $i + 1;
            }
        }

        return 'Never syncing';
    }
}
