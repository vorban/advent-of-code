<?php

namespace App\Solutions;

class Cell
{
    public function __construct(public int $value, public bool $checked = false)
    {
    }

    public function check(int $value): bool
    {
        if ($this->checked) {
            return false;
        }

        if ($this->value == $value) {
            $this->checked = true;

            return true;
        }

        return false;
    }
}

class Board
{
    public function __construct(public array $rows)
    {
    }

    public static function deserialize(string $board): self
    {
        $rows = collect(explode("\n", $board))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => collect(explode(' ', $line))
                ->filter(fn ($number) => strlen($number) > 0)
                ->map(fn ($cell) => new Cell(intval($cell)))
                ->values()
                ->all())
            ->values()
            ->all();

        return new self($rows);
    }

    public function check(int $value): bool
    {
        foreach ($this->rows as $row) {
            foreach ($row as $cell) {
                if ($cell->check($value)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function bingo(): bool
    {
        // lines
        foreach ($this->rows as $row) {
            $checked = collect($row)->filter(fn ($cell) => $cell->checked)->count();
            if ($checked == 5) {
                return true;
            }
        }

        // columns
        for ($i = 0; $i < 5; $i++) {
            $checked = collect($this->rows)->map(fn ($row) => $row[$i])->filter(fn ($cell) => $cell->checked)->count();
            if ($checked == 5) {
                return true;
            }
        }

        return false;
    }

    public function score(): int
    {
        $score = 0;
        foreach ($this->rows as $row) {
            foreach ($row as $cell) {
                if (! $cell->checked) {
                    $score += $cell->value;
                }
            }
        }

        return $score;
    }
}

class Solution202104
{
    public function silver(string $data): string
    {
        $parts = explode("\n\n", $data);

        $boards = [];
        for ($i = 1; $i < count($parts); $i++) {
            $boards[] = Board::deserialize($parts[$i]);
        }

        $numbers = collect(explode(',', $parts[0]))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => intval($line));

        foreach ($numbers as $number) {
            foreach ($boards as $board) {
                if ($board->check($number)) {
                    if ($board->bingo()) {
                        return $board->score() * $number;
                    }
                }
            }
        }

        return 'No winning board :(...';
    }

    public function gold(string $data): string
    {
        $parts = explode("\n\n", $data);

        $boards = [];
        for ($i = 1; $i < count($parts); $i++) {
            $boards[] = Board::deserialize($parts[$i]);
        }

        $numbers = collect(explode(',', $parts[0]))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => intval($line));

        $winning_boards = [];

        foreach ($numbers as $number) {
            foreach ($boards as $i => $board) {
                if ($board->check($number)) {
                    if ($board->bingo()) {
                        $winning_boards[$i] = true;

                        if (count($winning_boards) == count($boards)) {
                            return $board->score() * $number;
                        }
                    }
                }
            }
        }

        return 'No winning board :(...';
    }
}
