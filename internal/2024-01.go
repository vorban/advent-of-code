package solutions

import (
	"fmt"
	"sort"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parse(input string) ([]int, []int) {
	// Read input and parse it into a 2D int array
	left := []int{}
	right := []int{}

	lines := strings.Split(input, "\n")

	for _, line := range lines {
		if line == "" {
			continue
		}

		parts := strings.Split(line, "   ")
		l, _ := strconv.Atoi(parts[0])
		r, _ := strconv.Atoi(strings.TrimSpace(parts[1]))

		left = append(left, l)
		right = append(right, r)
	}

	return left, right
}

func count(list []int, value int) int {
	count := 0
	for _, v := range list {
		if v == value {
			count++
		}
	}

	return count
}

var Day202401 = solutionRegister.Solution{
	Silver: func(input string) string {
		left, right := parse(input)

		sort.Ints(left)
		sort.Ints(right)

		result := 0
		for i, l := range left {
			diff := (l - right[i])
			if diff < 0 {
				result += -diff
			} else {
				result += diff
			}
		}
		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		left, right := parse(input)

		sort.Ints(left)
		sort.Ints(right)

		result := 0
		for _, l := range left {
			similarity := count(right, l)
			result += similarity * l
		}
		return fmt.Sprintf("%d", result)
	},
}
