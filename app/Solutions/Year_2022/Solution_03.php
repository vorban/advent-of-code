<?php

namespace App\Solutions\Year_2022;

function getItemValue(string $item): int
{
    $ascii = ord($item);

    if ($ascii >= ord('a') && $ascii <= ord('z')) {
        return $ascii - ord('a') + 1;
    } elseif ($ascii >= ord('A') && $ascii <= ord('Z')) {
        return $ascii - ord('A') + 26 + 1;
    }

    return 0;
}

class Solution_03
{
    public function silver(string $data): string
    {
        $lines = explode("\n", $data);

        $sum = 0;
        foreach ($lines as $line) {
            if (strlen($line) === 0) {
                continue;
            }
            $middle = intval(strlen($line) / 2);

            $left = str_split(substr($line, 0, $middle));
            $right = str_split(substr($line, $middle));

            foreach ($left as $char) {
                if (in_array($char, $right)) {
                    $sum += getItemValue($char);

                    break;
                }
            }
        }

        return $sum;
    }

    public function gold(string $data): string
    {
        $lines = array_filter(explode("\n", $data), fn ($line) => strlen($line) > 0);

        $sum = 0;
        for ($i = 0; $i < count($lines); $i += 3) {
            $sack_1 = str_split($lines[$i]);
            $sack_2 = str_split($lines[$i + 1]);
            $sack_3 = str_split($lines[$i + 2]);

            foreach ($sack_1 as $item) {
                if (in_array($item, $sack_2) && in_array($item, $sack_3)) {
                    $sum += getItemValue($item);

                    break;
                }
            }
        }

        return $sum;
    }
}
