<?php

namespace App\Solutions\Year_2023;

enum Color: string
{
    case RED = 'red';
    case GREEN = 'green';
    case BLUE = 'blue';
}

class Pull
{
    public function __construct(
        public Color $color,
        public int $count,
    ) {
    }

    public static function fromString(string $str): self
    {
        $parts = explode(' ', trim($str));

        return new self(Color::from($parts[1]), intval($parts[0]));
    }
}

class Game
{
    public function __construct(
        public int $id,
        public array $pulls, // array<array<Pull>>
    ) {
    }

    public static function fromString(string $line): self
    {
        $parts = explode(':', $line);
        $id = explode(' ', $parts[0])[1];

        $pulls = [];
        foreach (explode(';', $parts[1]) as $part) {
            $pull = [];
            $colors = explode(',', $part);
            foreach ($colors as $color) {
                $pull[] = Pull::fromString($color);
            }
            $pulls[] = $pull;
        }

        return new self($id, $pulls);
    }
}

class Solution_02
{
    public function silver(string $data): string
    {
        $games = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Game::fromString($line));

        $max = [
            Color::RED->value => 12,
            Color::GREEN->value => 13,
            Color::BLUE->value => 14,
        ];

        $sum = 0;
        foreach ($games as $game) {
            foreach ($game->pulls as $group) {
                foreach ($group as $pull) {
                    if ($pull->count > $max[$pull->color->value]) {
                        continue 3;
                    }
                }
            }
            $sum += $game->id;
        }

        return $sum;
    }

    public function gold(string $data): string
    {
        $games = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Game::fromString($line));

        $sum = 0;
        foreach ($games as $game) {
            $max = [
                Color::RED->value => 0,
                Color::GREEN->value => 0,
                Color::BLUE->value => 0,
            ];

            foreach ($game->pulls as $group) {
                foreach ($group as $pull) {
                    if ($pull->count > $max[$pull->color->value]) {
                        $max[$pull->color->value] = $pull->count;
                    }
                }
            }

            $sum += array_reduce($max, fn ($carry, $item) => $carry * $item, 1);
        }

        return $sum;
    }
}
