<?php

namespace App\Solutions;

class Instruction
{
    public function __construct(public int $count, public int $from, public int $to)
    {
    }

    public static function fromString(string $string): self
    {
        $parts = explode(' ', $string); // move $count from $from to $to

        return new self(intval($parts[1]), intval($parts[3]) - 1, intval($parts[5]) - 1);
    }
}

function setupStacks(string $layout): array
{
    $stacks = [];

    $lines = explode("\n", $layout);
    foreach ($lines as $line) {
        $parts = str_split($line, 4);
        foreach ($parts as $stack_index => $part) {
            if (! str_contains($part, '[')) {
                continue; // this is empty space or index, not a crate
            }
            if (! array_key_exists($stack_index, $stacks)) {
                $stacks[$stack_index] = collect();
            }
            $stacks[$stack_index]->push($part[1]); // crates are written as "[x] "
        }
    }

    foreach ($stacks as $stack_index => $stack) {
        $stacks[$stack_index] = $stack->reverse(); // the last item is now the top of the stack
    }
    ksort($stacks);

    return $stacks;
}

class Solution202205
{
    public function silver(string $data): string
    {
        $parts = explode("\n\n", $data);
        $stacks = setupStacks($parts[0]);
        $lines = explode("\n", $parts[1]);

        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }
            $instruction = Instruction::fromString($line);

            for ($i = 0; $i < $instruction->count; $i++) {
                $stacks[$instruction->to]->push($stacks[$instruction->from]->pop());
            }
        }

        $tops = [];
        foreach ($stacks as $stack) {
            $tops[] = $stack->last();
        }

        return implode('', $tops);
    }

    public function gold(string $data): string
    {
        $parts = explode("\n\n", $data);
        $stacks = setupStacks($parts[0]);
        $lines = explode("\n", $parts[1]);

        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                continue;
            }
            $instruction = Instruction::fromString($line);

            $crates = $stacks[$instruction->from]->splice(-$instruction->count);
            $stacks[$instruction->to] = $stacks[$instruction->to]->concat($crates);
        }

        $tops = [];
        foreach ($stacks as $stack) {
            $tops[] = $stack->last();
        }

        return implode('', $tops);
    }
}
