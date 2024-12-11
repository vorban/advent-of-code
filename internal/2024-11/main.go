package solution202411

import (
	"fmt"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

var keep = map[string]int{}

func parse(input string) []int {
	result := []int{}

	for _, num := range strings.Split(input, " ") {
		value, _ := strconv.Atoi(strings.TrimSpace(num))
		result = append(result, value)
	}

	return result
}

func getKey(num int, blinks int) string {
	return fmt.Sprintf("%d;%d", num, blinks)
}

func simulate(stone int, blinks int) int {
	if blinks == 0 {
		return 1 // the current stone
	}

	key := getKey(stone, blinks)
	if count, ok := keep[key]; ok {
		return count
	}

	// process one blink on the stone
	str := strconv.Itoa(stone)
	additional := -1
	if stone == 0 {
		stone = 1
	} else if len(str)%2 == 0 {
		size := len(str) / 2
		stone, _ = strconv.Atoi(str[:size])
		additional, _ = strconv.Atoi(str[size:])
	} else {
		stone *= 2024
	}

	count := simulate(stone, blinks-1)
	keep[getKey(stone, blinks-1)] = count
	if additional != -1 {
		additionalCount := simulate(additional, blinks-1)
		keep[getKey(additional, blinks-1)] = additionalCount
		count += additionalCount
	}

	return count
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		stones := parse(input)

		total := 0
		for i := 0; i < len(stones); i++ {
			total += simulate(stones[i], 25)
		}

		return fmt.Sprintf("%d", total)
	},
	Gold: func(input string) string {
		stones := parse(input)

		total := 0
		for i := 0; i < len(stones); i++ {
			total += simulate(stones[i], 75)
		}

		return fmt.Sprintf("%d", total)
	},
}
