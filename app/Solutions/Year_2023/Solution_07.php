<?php

namespace App\Solutions\Year_2023;

use Illuminate\Support\Collection;

const CARDS = [
    '2', '3', '4', '5', '6', '7', '8', '9',
    'T', 'J', 'Q', 'K', 'A',
];
const JCARDS = [
    'J', '2', '3', '4', '5', '6', '7', '8', '9',
    'T', 'Q', 'K', 'A',
];

enum Type: int
{
    case HIGH_CARD = 0;
    case PAIR = 1;
    case TWO_PAIR = 2;
    case THREE_OF_A_KIND = 3;
    case FULL_HOUSE = 4;
    case FOUR_OF_A_KIND = 5;
    case FIVE_OF_A_KIND = 6;

    public function compare(self $other)
    {
        return $this->value <=> $other->value;
    }
}

class Hand
{
    public Type $type;

    public function __construct(
        public Collection $cards,
        public int $bid,
    ) {
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public static function fromString(string $str)
    {
        $parts = explode(' ', $str);
        $cards = collect(str_split($parts[0]));

        return new self($cards, bid: intval($parts[1]));
    }

    public function compare(Hand $other, array $reference): int
    {
        $cmp = $this->type->compare($other->type);
        if ($cmp !== 0) {
            return $cmp;
        }

        foreach ($this->cards as $i => $card) {
            if ($card == $other->cards[$i]) {
                continue;
            }

            return array_search($card, $reference) <=> array_search($other->cards[$i], $reference);
        }

        return 0;
    }
}

function determineType(Hand $hand, bool $use_joker = false): Type
{
    $cards = clone $hand->cards;
    if ($use_joker && $hand->cards->contains('J')) {
        $best_card = $cards->countBy()
            ->sort()
            ->filter(fn ($c, $i) => $i != 'J')
            ->keys()
            ->last();

        foreach ($cards as $i => $card) {
            if ($card == 'J') {
                $cards[$i] = strval($best_card);
            }
        }
    }

    $occurrences = $cards->countBy();

    if ($occurrences->contains(5)) {
        return Type::FIVE_OF_A_KIND;
    }

    if ($occurrences->contains(4)) {
        return Type::FOUR_OF_A_KIND;
    }

    if ($occurrences->contains(3)) {
        if ($occurrences->contains(2)) {
            return Type::FULL_HOUSE;
        }

        return Type::THREE_OF_A_KIND;

    }

    if ($occurrences->countBy()->contains(2)) {
        return Type::TWO_PAIR;
    }

    if ($occurrences->contains(2)) {
        return Type::PAIR;
    }

    return Type::HIGH_CARD;
}

class Solution_07
{
    public function silver(string $data): string
    {
        $hands = collect(explode("\n", $data))
            ->filter(fn ($line) => $line !== '')
            ->map(function ($line) {
                $hand = Hand::fromString($line);
                $type = determineType($hand);

                return $hand->setType($type);
            });

        $hands = $hands->sortBy([
            fn ($a, $b) => $a->compare($b, CARDS),
        ])->values();

        return $hands->reduce(fn ($c, $h, $k) => $c + $h->bid * ($k + 1), 0);
    }

    public function gold(string $data): string
    {
        $hands = collect(explode("\n", $data))
            ->filter(fn ($line) => $line !== '')
            ->map(function ($line) {
                $hand = Hand::fromString($line);
                $type = determineType($hand, use_joker: true);

                return $hand->setType($type);
            });

        $hands = $hands->sortBy([
            fn ($a, $b) => $a->compare($b, JCARDS),
        ])->values();

        return $hands->reduce(fn ($c, $h, $k) => $c + $h->bid * ($k + 1), 0);
    }
}
