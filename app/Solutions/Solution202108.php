<?php

namespace App\Solutions;

use Illuminate\Support\Collection;

function formatSignals(string $signals): string
{
    $signals = str_split($signals);
    sort($signals);

    return implode('', $signals);
}

function decipher(array $encoded): Collection
{
    $cipher = collect(range(0, 9))->map(fn ($el) => '');
    $signals = ['top' => '', 'tl' => '', 'tr' => '', 'mid' => '', 'bl' => '', 'br' => '', 'bot' => ''];

    // map 1, 4, 7, 8 because of unique length
    foreach ($encoded as $code) {
        match (strlen($code)) {
            2 => $cipher[1] = formatSignals($code),
            4 => $cipher[4] = formatSignals($code),
            3 => $cipher[7] = formatSignals($code),
            7 => $cipher[8] = formatSignals($code),
            default => '',
        };
    }

    // the difference between 1 and 7 is the top signal
    foreach (str_split($cipher[7]) as $char) {
        if (! str_contains($cipher[1], $char)) {
            $signals['top'] = $char;
            break;
        }
    }

    // 6 is the only 6-signals digit that does not contain all signals of 1.
    foreach ($encoded as $code) {
        if (strlen($code) !== 6) {
            continue;
        }
        $code = formatSignals($code);
        $code_contains_first_char = str_contains($code, $cipher[1][0]);
        $code_contains_second_char = str_contains($code, $cipher[1][1]);

        if ($code_contains_first_char && $code_contains_second_char) {
            continue;
        }

        // $code is 6. The missing signal is tr
        $cipher[6] = $code;
        $signals['tr'] = $code_contains_first_char ? $cipher[1][1] : $cipher[1][0];
        break;
    }

    // the 3rd unidentified signal of 7 is br
    foreach (str_split($cipher[7]) as $char) {
        if ($char != $signals['top'] && $char != $signals['tr']) {
            $signals['br'] = $char;
            break;
        }
    }

    // in the 5-signals digits, 3 contains 7, 2 does not have br and 5 does not have tr
    foreach ($encoded as $code) {
        if (strlen($code) !== 5) {
            continue;
        }
        $code = formatSignals($code);
        if (! str_contains($code, $signals['br'])) {
            $cipher[2] = $code;
        } elseif (! str_contains($code, $signals['tr'])) {
            $cipher[5] = $code;
        } else {
            $cipher[3] = $code;
        }
    }

    // 2 misses 2 signals, one is in 7, the other is tl
    foreach (str_split('abcdefg') as $char) {
        if (! str_contains($cipher[2], $char) && ! str_contains($cipher[7], $char)) {
            $signals['tl'] = $char;
        }
    }

    // 5 misses 2 signals, one is in 7, the other is bl
    foreach (str_split('abcdefg') as $char) {
        if (! str_contains($cipher[5], $char) && ! str_contains($cipher[7], $char)) {
            $signals['bl'] = $char;
        }
    }

    // 0, 9 have 6 signals. 0 does not have mid, 9 does not have bl
    foreach ($encoded as $code) {
        $code = formatSignals($code);
        if (strlen($code) !== 6 || $code == $cipher[6]) {
            continue;
        }

        if (! str_contains($code, $signals['bl'])) {
            $cipher[9] = $code;
        } else {
            $cipher[0] = $code;
        }
    }

    return $cipher;
}

class Solution202108
{
    public function silver(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $number_count = collect(range(0, 9))->map(fn ($el) => 0);

        foreach ($lines as $line) {
            $parts = explode(' | ', $line);
            $encoded = explode(' ', $parts[0]);
            $output = explode(' ', $parts[1]);

            $cipher = decipher($encoded);
            foreach ($output as $value) {
                $value = formatSignals($value);
                if ($cipher->contains($value)) {
                    $number_count[$cipher->search($value)] += 1;
                }
            }
        }

        return $number_count->filter(fn ($el, $key) => in_array($key, [1, 4, 7, 8]))->sum();
    }

    public function gold(string $data): string
    {
        $lines = collect(explode("\n", $data))
            ->filter(fn ($line) => strlen($line) > 0);

        $sum = 0;
        foreach ($lines as $line) {
            $parts = explode(' | ', $line);
            $encoded = explode(' ', $parts[0]);
            $output = explode(' ', $parts[1]);

            $cipher = decipher($encoded);
            $number = '';
            foreach ($output as $value) {
                $value = formatSignals($value);

                foreach ($cipher as $digit => $signals) {
                    if ($signals == $value) {
                        $number .= $digit;
                    }
                }
            }
            $value = intval($number);
            $sum += $value;
        }

        return $sum;
    }
}
