package solution202410

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Position struct {
	x int
	y int
}

func (p Position) String() string {
	return fmt.Sprintf("%d;%d", p.x, p.y)
}

func parse(input string) [][]int {
	lines := strings.Split(input, "\n")
	result := [][]int{}

	for _, line := range lines {
		if line == "" {
			continue
		}

		chars := strings.Split(line, "")
		row := []int{}
		for _, char := range chars {
			if char == "" {
				continue
			}
			// handle dots for debugging
			if char == "." {
				row = append(row, -1)
				continue
			}
			value, _ := strconv.Atoi(char)
			row = append(row, value)
		}
		result = append(result, row)
	}

	return result
}

func explore(trailmap [][]int, head Position) []Position {
	if trailmap[head.y][head.x] == 9 {
		return []Position{head}
	}

	left := Position{head.x - 1, head.y}
	right := Position{head.x + 1, head.y}
	up := Position{head.x, head.y - 1}
	down := Position{head.x, head.y + 1}

	ends := []Position{}
	if left.x >= 0 && trailmap[left.y][left.x]-trailmap[head.y][head.x] == 1 {
		recursive := explore(trailmap, left)
		ends = slices.Concat(ends, recursive)
	}
	if right.x < len(trailmap[0]) && trailmap[right.y][right.x]-trailmap[head.y][head.x] == 1 {
		recursive := explore(trailmap, right)
		ends = slices.Concat(ends, recursive)
	}
	if up.y >= 0 && trailmap[up.y][up.x]-trailmap[head.y][head.x] == 1 {
		recursive := explore(trailmap, up)
		ends = slices.Concat(ends, recursive)
	}
	if down.y < len(trailmap) && trailmap[down.y][down.x]-trailmap[head.y][head.x] == 1 {
		recursive := explore(trailmap, down)
		ends = slices.Concat(ends, recursive)
	}

	return ends
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		trailmap := parse(input)

		score := 0
		for y, row := range trailmap {
			for x, value := range row {
				if value != 0 {
					continue
				}

				ends := map[string]Position{}
				trailends := explore(trailmap, Position{x, y})
				trailscore := 0
				for _, end := range trailends {
					if _, ok := ends[end.String()]; ok {
						continue
					}
					trailscore++
					ends[end.String()] = end
				}
				score += trailscore
			}
		}

		return fmt.Sprintf("%d", score)
	},
	Gold: func(input string) string {
		trailmap := parse(input)

		score := 0
		for y, row := range trailmap {
			for x, value := range row {
				if value != 0 {
					continue
				}

				trailends := explore(trailmap, Position{x, y})
				score += len(trailends)
			}
		}

		return fmt.Sprintf("%d", score)
	},
}
