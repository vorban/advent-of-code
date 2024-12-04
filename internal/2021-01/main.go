package solution202101

import (
	"fmt"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parse(input string) []int {
	result := []int{}

	lines := strings.Split(input, "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}

		value, _ := strconv.Atoi(line)
		result = append(result, value)
	}

	return result
}

func window(numbers []int, size int) [][]int {
	result := [][]int{}

	for i := 0; i < len(numbers)-size+1; i++ {
		result = append(result, numbers[i:i+size])
	}

	return result
}

func sum(numbers []int) int {
	result := 0
	for _, number := range numbers {
		result += number
	}

	return result
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		result := 0

		numbers := parse(input)
		for i := 1; i < len(numbers); i++ {
			if numbers[i] > numbers[i-1] {
				result++
			}
		}

		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		result := 0

		numbers := parse(input)
		windows := window(numbers, 3)
		for i := 1; i < len(windows); i++ {
			if sum(windows[i]) > sum(windows[i-1]) {
				result++
			}
		}

		return fmt.Sprintf("%d", result)
	},
}
