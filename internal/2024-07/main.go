package solution202407

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parse(input string) map[int][]int {
	equations := map[int][]int{}

	for _, line := range strings.Split(input, "\n") {
		if line == "" {
			continue
		}

		// Parse the equation
		parts := strings.Split(line, ": ")
		key, _ := strconv.Atoi(parts[0])

		values := []int{}
		for _, part := range strings.Split(parts[1], " ") {
			if part == "" {
				continue
			}

			value, _ := strconv.Atoi(part)
			values = append(values, value)
		}
		slices.Reverse(values)
		equations[key] = values
	}

	return equations
}

func recurse(values []int, total int, isConcatenationEnabled bool) int {
	if len(values) == 1 {
		return values[0]
	}

	sum := recurse(values[1:], total-values[0], isConcatenationEnabled) + values[0]
	if sum == total {
		return sum
	}

	mul := recurse(values[1:], total/values[0], isConcatenationEnabled) * values[0]
	if mul == total {
		return mul
	}

	if isConcatenationEnabled {
		totalToString := fmt.Sprintf("%d", total)
		valueToString := fmt.Sprintf("%d", values[0])
		canBeConcatenated := strings.HasSuffix(totalToString, valueToString)
		if canBeConcatenated {
			deconcat, _ := strconv.Atoi(totalToString[:len(totalToString)-len(valueToString)])
			con := recurse(values[1:], deconcat, isConcatenationEnabled)
			if con == deconcat {
				return total
			}
		}
	}

	return -1
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		equations := parse(input)

		total := 0
		for key, values := range equations {
			if recurse(values, key, false) != -1 {
				total += key
			}
		}

		return fmt.Sprintf("%d", total)
	},
	Gold: func(input string) string {
		equations := parse(input)

		total := 0
		for key, values := range equations {
			if recurse(values, key, true) != -1 {
				total += key
			}
		}

		return fmt.Sprintf("%d", total)
	},
}
