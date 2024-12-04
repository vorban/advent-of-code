package solution202404

import (
	"fmt"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

var XMAS = []string{"X", "M", "A", "S"}

func parse(input string) [][]string {
	result := [][]string{}

	lines := strings.Split(input, "\n")

	for _, line := range lines {
		if line == "" {
			continue
		}

		row := []string{}
		for _, char := range line {
			row = append(row, string(char))
		}

		result = append(result, row)
	}

	return result
}

func searchHorizontal(grid [][]string, x int, y int, reverse bool) bool {
	var mul int
	if reverse {
		mul = -1
	} else {
		mul = 1
	}

	for i := 0; i < len(XMAS); i++ {
		index := x + i*mul
		if index < 0 || index >= len(grid[y]) {
			return false
		}

		if grid[y][index] != XMAS[i] {
			return false
		}
	}

	return true
}

func searchVertical(grid [][]string, x int, y int, reverse bool) bool {
	var mul int
	if reverse {
		mul = -1
	} else {
		mul = 1
	}

	for i := 0; i < len(XMAS); i++ {
		index := y + i*mul
		if index < 0 || index >= len(grid) {
			return false
		}

		if grid[index][x] != XMAS[i] {
			return false
		}
	}

	return true
}

func searchDiagonal(grid [][]string, x int, y int, reverse bool) bool {
	var mul int
	if reverse {
		mul = -1
	} else {
		mul = 1
	}

	for i := 0; i < len(XMAS); i++ {
		row := y + i*mul
		col := x + i*mul
		if row < 0 || row >= len(grid) || col < 0 || col >= len(grid[y]) {
			return false
		}

		if grid[row][col] != XMAS[i] {
			return false
		}
	}

	return true
}

func searchOtherDiagonal(grid [][]string, x int, y int, reverse bool) bool {
	var mul int
	if reverse {
		mul = -1
	} else {
		mul = 1
	}

	for i := 0; i < len(XMAS); i++ {
		row := y + i*mul
		col := x - i*mul

		if row < 0 || row >= len(grid) || col < 0 || col >= len(grid[y]) {
			return false
		}

		if grid[row][col] != XMAS[i] {
			return false
		}
	}

	return true
}

func canCross(grid [][]string, x int, y int) bool {
	return x >= 1 && x < len(grid[y])-1 && y >= 1 && y < len(grid)-1
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		result := 0

		grid := parse(input)

		for y := 0; y < len(grid); y++ {
			for x := 0; x < len(grid[y]); x++ {
				matched := []bool{
					searchHorizontal(grid, x, y, false),
					searchHorizontal(grid, x, y, true),
					searchVertical(grid, x, y, false),
					searchVertical(grid, x, y, true),
					searchDiagonal(grid, x, y, false),
					searchDiagonal(grid, x, y, true),
					searchOtherDiagonal(grid, x, y, false),
					searchOtherDiagonal(grid, x, y, true),
				}

				for _, m := range matched {
					if m {
						result++
					}
				}
			}
		}

		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		result := 0

		grid := parse(input)

		for y := 0; y < len(grid); y++ {
			for x := 0; x < len(grid[y]); x++ {
				if grid[y][x] != "A" || !canCross(grid, x, y) {
					continue
				}

				tl := grid[y-1][x-1]
				tr := grid[y-1][x+1]
				bl := grid[y+1][x-1]
				br := grid[y+1][x+1]

				matched := tl == "M" && br == "S" && bl == "M" && tr == "S"
				matched = matched || (tl == "S" && br == "M" && bl == "M" && tr == "S")
				matched = matched || (tl == "S" && br == "M" && bl == "S" && tr == "M")
				matched = matched || (tl == "M" && br == "S" && bl == "S" && tr == "M")

				if matched {
					result++
				}
			}
		}

		return fmt.Sprintf("%d", result)
	},
}
