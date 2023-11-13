<?php

namespace App\Solutions;

use SebastianBergmann\Diff\Chunk;

enum ChunkType: string
{
    case PARENTHESIS = '(';
    case BRACKET = '[';
    case BRACE = '{';
    case ANGLE_BRACKET = '<';

    public function getClosingChar(): string
    {
        return match ($this) {
            self::PARENTHESIS => ')',
            self::BRACKET => ']',
            self::BRACE => '}',
            self::ANGLE_BRACKET => '>',
        };
    }

    public function getScore(): int
    {
        return match ($this) {
            self::PARENTHESIS => 3,
            self::BRACKET => 57,
            self::BRACE => 1197,
            self::ANGLE_BRACKET => 25137,
        };
    }

    public function getAutocompleteValue(): int
    {
        return match ($this) {
            self::PARENTHESIS => 1,
            self::BRACKET => 2,
            self::BRACE => 3,
            self::ANGLE_BRACKET => 4,
        };
    }

    public static function fromClosingChar(string $char): self
    {
        return match ($char) {
            ')' => self::PARENTHESIS,
            ']' => self::BRACKET,
            '}' => self::BRACE,
            '>' => self::ANGLE_BRACKET,
        };
    }
}

class Solution202110
{
    public function silver(string $data): string
    {
        $data = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $chunkOpenings = collect(ChunkType::cases());

        $score = 0;
        foreach ($data as $line) {
            $stack = collect();
            foreach (str_split($line) as $char) {
                $match = $chunkOpenings->first(fn ($opening) => $opening->value === $char);
                // opening a chunk
                if ($match) {
                    $stack->push($match);

                    continue;
                }
                // closing a chunk
                if ($char === $stack->last()->getClosingChar()) {
                    $stack->pop();

                    continue;
                }
                // corrupted chunk
                $chunk = ChunkType::fromClosingChar($char);
                $score += $chunk->getScore();
                break;
            }
            // incomplete chunk
            if ($stack->count() > 0) {
                continue;
            }
        }

        return $score;
    }

    public function gold(string $data): string
    {
        $data = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $chunkOpenings = collect(ChunkType::cases());

        $scores = [];
        foreach ($data as $line) {
            $stack = collect();
            $score = 0;
            foreach (str_split($line) as $char) {
                $match = $chunkOpenings->first(fn ($opening) => $opening->value === $char);
                // opening a chunk
                if ($match) {
                    $stack->push($match);

                    continue;
                }
                // closing a chunk
                if ($char === $stack->last()->getClosingChar()) {
                    $stack->pop();

                    continue;
                }
                // corrupted chunk
                $stack = $stack->empty();
                break;
            }
            // incomplete chunk
            if ($stack->count() > 0) {
                // find the closing pairs
                $stack->reverse()->each(function ($chunk) use (&$score) {
                    $score = $score * 5 + $chunk->getAutocompleteValue();
                });
                $scores[] = $score;
            }
        }

        sort($scores);
        $index = intval(count($scores) / 2);

        return $scores[$index];
    }
}
