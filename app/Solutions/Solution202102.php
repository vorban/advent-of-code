<?php

namespace App\Solutions;

enum InstructionType: string
{
    case FORWARD = 'forward';
    case DOWN = 'down';
    case UP = 'up';
}

class Instruction
{
    public function __construct(
        public InstructionType $type,
        public int $value
    ) {
    }

    public static function fromLine(string $line): self
    {
        $parts = explode(' ', $line);

        return new self(InstructionType::from($parts[0]), intval($parts[1]));
    }
}

class Solution202102
{
    public function silver(string $data): string
    {
        $instructions = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Instruction::fromLine($line));

        $depth = 0;
        $horizontal = 0;

        foreach ($instructions as $instruction) {
            match ($instruction->type) {
                InstructionType::FORWARD => $horizontal += $instruction->value,
                InstructionType::DOWN => $depth += $instruction->value,
                InstructionType::UP => $depth -= $instruction->value,
            };
        }

        return $horizontal * $depth;
    }

    public function gold(string $data): string
    {
        $instructions = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0)
            ->map(fn ($line) => Instruction::fromLine($line));

        $depth = 0;
        $horizontal = 0;
        $aim = 0;

        foreach ($instructions as $instruction) {
            switch ($instruction->type) {
                case InstructionType::FORWARD:
                    $horizontal += $instruction->value;
                    $depth += $aim * $instruction->value;
                    break;
                case InstructionType::DOWN:
                    $aim += $instruction->value;
                    break;
                case InstructionType::UP:
                    $aim -= $instruction->value;
                    break;
            }
        }

        return $horizontal * $depth;
    }
}
