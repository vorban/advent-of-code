<?php

namespace App\Solutions;

enum Instruction: int
{
    case ADD = 1;
    case MULTIPLY = 2;
    case HALT = 99;

    public static function fromInt(int $int): Instruction
    {
        return match ($int) {
            1 => self::ADD,
            2 => self::MULTIPLY,
            99 => self::HALT,
        };
    }
}

class Solution201902
{
    public function silver(string $data, ?int $noun = 12, ?int $verb = 2): string
    {
        $memory = explode(',', $data);
        foreach ($memory as $key => $value) {
            $memory[$key] = intval($value);
        }

        $memory[1] = $noun;
        $memory[2] = $verb;

        for ($i = 0; $i < count($memory); $i += 4) {
            $instruction = Instruction::fromInt($memory[$i]);
            if ($instruction == Instruction::HALT) {
                break;
            }
            $left = $memory[$memory[$i + 1]];
            $right = $memory[$memory[$i + 2]];
            $target = $memory[$i + 3];

            $memory[$target] = match ($instruction) {
                Instruction::ADD => $left + $right,
                Instruction::MULTIPLY => $left * $right,
            };
        }

        return $memory[0];
    }

    public function gold(string $data): string
    {
        $target = 19690720;

        for ($noun = 0; $noun < 100; $noun++) {
            for ($verb = 0; $verb < 100; $verb++) {
                $result = $this->silver($data, $noun, $verb);
                if ($result == $target) {
                    return 100 * $noun + $verb;
                }
            }
        }

        return 'No solution found :(...';
    }
}
