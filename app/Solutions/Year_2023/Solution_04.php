<?php

namespace App\Solutions\Year_2023;

class Card
{
    public function __construct(
        public array $winning,
        public array $numbers,
        public int $index,
        public ?int $matches = null,
    ) {
        if ($this->matches === null) {
            $this->matches = $this->matches();
        }
    }

    public function matches(): int
    {
        return count(array_filter(
            $this->numbers,
            fn ($x) => in_array($x, $this->winning)
        ));
    }

    public function getNewCards(): array
    {
        if ($this->matches === 0) {
            return [];
        }

        return range($this->index + 1, $this->index + $this->matches);
    }
}

function parseInput(string $data): array
{
    $parser = function (string $number_list): array {
        $number_list = explode(' ', $number_list);
        $number_list = array_filter($number_list, fn ($x) => $x !== '');
        $number_list = array_map(fn ($x) => intval($x), $number_list);

        return $number_list;
    };

    return collect(explode("\n", $data))
        ->filter(fn ($card) => $card !== '')
        ->map(function ($card, $index) use ($parser) {
            $card = explode(': ', $card)[1];
            $parts = explode(' | ', $card);
            $winning = $parser($parts[0]);
            $numbers = $parser($parts[1]);

            return new Card($winning, $numbers, $index);
        })->all();
}

class Solution_04
{
    public function silver(string $data): string
    {
        $cards = parseInput($data);

        $points = 0;
        foreach ($cards as $card) {
            if ($card->matches <= 1) {
                $points += $card->matches;
            } else {
                $points += 1 << ($card->matches - 1);
            }
        }

        return $points;
    }

    public function gold(string $data): string
    {
        $cards = parseInput($data);
        $counts = array_fill(0, count($cards), 1);

        for ($i = 0; $i < count($counts); $i++) {
            foreach ($cards[$i]->getNewCards() as $new_card) {
                $counts[$new_card] += $counts[$i];
            }
        }

        return array_sum($counts);
    }
}
