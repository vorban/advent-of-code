<?php

namespace App\Solutions;

enum Option: int
{
    case ROCK = 1;
    case PAPER = 2;
    case SCISSORS = 3;

    public function beats(Option $option): Outcome
    {
        return match ($this) {
            Option::ROCK => match ($option) {
                Option::ROCK => Outcome::DRAW,
                Option::PAPER => Outcome::LOSE,
                Option::SCISSORS => Outcome::WIN,
            },
            Option::PAPER => match ($option) {
                Option::ROCK => Outcome::WIN,
                Option::PAPER => Outcome::DRAW,
                Option::SCISSORS => Outcome::LOSE,
            },
            Option::SCISSORS => match ($option) {
                Option::ROCK => Outcome::LOSE,
                Option::PAPER => Outcome::WIN,
                Option::SCISSORS => Outcome::DRAW,
            },
        };
    }

    public static function fromLeft(string $string): Option
    {
        return match ($string) {
            'A' => Option::ROCK,
            'B' => Option::PAPER,
            'C' => Option::SCISSORS,
        };
    }

    public static function fromRight(string $string): Option
    {
        return match ($string) {
            'X' => Option::ROCK,
            'Y' => Option::PAPER,
            'Z' => Option::SCISSORS,
        };
    }
}

enum Outcome: int
{
    case LOSE = 0;
    case DRAW = 3;
    case WIN = 6;

    public static function fromRight(string $string): Outcome
    {
        return match ($string) {
            'X' => Outcome::LOSE,
            'Y' => Outcome::DRAW,
            'Z' => Outcome::WIN,
        };
    }
}

class Solution202202
{
    public function silver(string $data): string
    {
        $lines = explode("\n", $data);

        $score = 0;
        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }
            $options = explode(' ', $line);
            $left = Option::fromLeft($options[0]);
            $right = Option::fromRight($options[1]);

            $score += $right->beats($left)->value + $right->value;
        }

        return $score;
    }

    public function gold(string $data): string
    {
        $lines = explode("\n", $data);

        $score = 0;
        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }
            $options = explode(' ', $line);
            $left = Option::fromLeft($options[0]);
            $outcome = Outcome::fromRight($options[1]);
            $right = match ($outcome) {
                Outcome::LOSE => match ($left) {
                    Option::ROCK => Option::SCISSORS,
                    Option::PAPER => Option::ROCK,
                    Option::SCISSORS => Option::PAPER,
                },
                Outcome::DRAW => $left,
                Outcome::WIN => match ($left) {
                    Option::ROCK => Option::PAPER,
                    Option::PAPER => Option::SCISSORS,
                    Option::SCISSORS => Option::ROCK,
                },
            };

            $score += $outcome->value + $right->value;
        }

        return $score;
    }
}
