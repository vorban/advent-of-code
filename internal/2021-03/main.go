package solution202103

import (
	"fmt"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parse(input string) [][]bool {
	result := [][]bool{}

	lines := strings.Split(input, "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}

		row := []bool{}
		for _, char := range line {
			if char == '1' {
				row = append(row, true)
			} else if char == '0' {
				row = append(row, false)
			}
		}
		result = append(result, row)
	}

	return result
}

func count(report [][]bool) []int {
	result := make([]int, len(report[0]))

	for _, row := range report {
		for i, on := range row {
			if on {
				result[i]++
			}
		}
	}

	return result
}

func countColumn(report [][]bool, column int) int {
	count := 0
	for _, row := range report {
		if row[column] {
			count++
		}
	}
	return count
}

func filter(report [][]bool, column int, value bool) [][]bool {
	result := [][]bool{}
	for _, row := range report {
		if row[column] == value {
			result = append(result, row)
		}
	}

	return result
}

func toDecimal(row []bool) int {
	result := 0
	for i, on := range row {
		if on {
			result += 1 << (len(row) - i - 1)
		}
	}
	return result
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		report := parse(input)
		counts := count(report)

		gamma := []bool{}
		epsilon := []bool{}
		for _, count := range counts {
			if count > len(report)/2 {
				gamma = append(gamma, true)
				epsilon = append(epsilon, false)
			} else {
				gamma = append(gamma, false)
				epsilon = append(epsilon, true)
			}
		}

		return fmt.Sprintf("%d", toDecimal(gamma)*toDecimal(epsilon))
	},
	Gold: func(input string) string {
		report := parse(input)
		for i := 0; len(report) > 1; i++ {
			count := countColumn(report, i)
			if count > len(report)/2 || (len(report)%2 == 0 && count == len(report)/2) {
				report = filter(report, i, true)
			} else {
				report = filter(report, i, false)
			}
		}
		oxygen := toDecimal(report[0])

		report = parse(input)
		for i := 0; len(report) > 1; i++ {
			count := countColumn(report, i)
			if count > len(report)/2 || (len(report)%2 == 0 && count == len(report)/2) {
				report = filter(report, i, false)
			} else {
				report = filter(report, i, true)
			}
		}

		scrubber := toDecimal(report[0])

		return fmt.Sprintf("%d", oxygen*scrubber)
	},
}
