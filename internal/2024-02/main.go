package solution202402

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parse(input string) [][]int {
	// Read input and parse it into a 2D int array
	result := [][]int{}

	lines := strings.Split(input, "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}

		report := []int{}
		levels := strings.Split(line, " ")

		for _, level := range levels {
			value, _ := strconv.Atoi(level)
			report = append(report, value)
		}

		result = append(result, report)
	}

	return result
}

func isSafe(report []int) bool {
	desc := make([]int, len(report))
	copy(desc, report)
	slices.Reverse(desc)

	if !slices.IsSorted(report) && !slices.IsSorted(desc) {
		return false
	}

	for i := 0; i < len(report)-1; i++ {
		diff := report[i] - report[i+1]
		if diff < 0 {
			diff = -diff
		}
		if diff < 1 || diff > 3 {
			return false
		}
	}
	return true
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		reports := parse(input)
		result := 0

		for _, report := range reports {
			if isSafe(report) {
				result++
			}
		}

		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		reports := parse(input)
		result := 0

		for _, report := range reports {
			if isSafe(report) {
				result++
				continue
			}

			for i := 1; i < len(report)+1; i++ {
				dampened := make([]int, len(report))
				copy(dampened, report)
				dampened = append(dampened[:i-1], dampened[i:]...)

				if isSafe(dampened) {
					result++
					break
				}
			}
		}

		return fmt.Sprintf("%d", result)
	},
}
