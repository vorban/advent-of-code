<?php

namespace App\Solutions\Year_2021;

function get_points(string $data): array
{
    $lines = explode("\n", $data);
    $points = [];

    foreach ($lines as $line) {
        if (strlen($line) === 0) {
            continue;
        }

        $parts = explode('-', $line);
        foreach ($parts as $point) {
            if (array_key_exists($point, $points)) {
                // add all the parts except the current point and those already in the list
                foreach ($parts as $el) {
                    if ($el !== $point && ! in_array($el, $points[$point])) {
                        $points[$point][] = $el;
                    }
                }
            } else {
                // add the point to the list and add all the other parts as its neighbours
                $points[$point] = array_filter($parts, fn ($el) => $el !== $point);
            }
        }
    }

    return $points;
}

function count_paths(array $points, array $visited, string $point, bool $bonus_visit = false): int
{
    if ($point === 'end') {
        return 1; // if we reached the end, this path is complete and valid, it counts as one.
    }
    if (in_array($point, $visited) && ctype_lower($point)) {
        if ($point === 'start') {
            return 0; // the start cannot be visited again
        }
        if ($bonus_visit) {
            $bonus_visit = false; // this is now counting as the bonus round
        } else {
            return 0;
        } // we cannot further explore this path because already visited without reaching the end. Does not count.
    }

    $visited[] = $point;

    $count = 0;
    foreach ($points[$point] as $neighbour) {
        $count += count_paths($points, $visited, $neighbour, $bonus_visit); // explore all neighbours recursively, add their paths
    }

    return $count;
}

class Solution_12
{
    public function silver(string $data): string
    {
        $points = get_points($data);

        return count_paths($points, [], 'start');
    }

    public function gold(string $data): string
    {
        $points = get_points($data);

        return count_paths($points, [], 'start', true);
    }
}
