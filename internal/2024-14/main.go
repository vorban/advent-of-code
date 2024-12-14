package solution202414

import (
	"fmt"
	"image"
	"image/color"
	"image/png"
	"os"
	"regexp"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Vector struct {
	X int
	Y int
}

type Robot struct {
	pos Vector
	dir Vector
}

func (r Robot) isInBounds(topleft Vector, botright Vector) bool {
	if r.pos.X < topleft.X || r.pos.X > botright.X {
		return false
	}

	if r.pos.Y < topleft.Y || r.pos.Y > botright.Y {
		return false
	}

	return true
}

func parse(input string) []Robot {
	robots := []Robot{}

	r := regexp.MustCompile(`p=(\d+),(\d+) v=(-*\d+),(-*\d+)`)

	for _, line := range strings.Split(input, "\n") {
		if line == "" {
			continue
		}

		parts := r.FindStringSubmatch(line)
		x, _ := strconv.Atoi(parts[1])
		y, _ := strconv.Atoi(parts[2])
		dx, _ := strconv.Atoi(parts[3])
		dy, _ := strconv.Atoi(parts[4])

		robots = append(robots, Robot{
			pos: Vector{x, y},
			dir: Vector{dx, dy},
		})
	}

	return robots
}

func countQuadrant(robots []Robot, topleft Vector, botright Vector) int {
	count := 0

	for _, robot := range robots {
		if robot.isInBounds(topleft, botright) {
			count++
		}
	}

	return count
}

func simulate(robots []Robot, steps int, size Vector) {
	for i := range robots {
		x := (robots[i].pos.X + steps*robots[i].dir.X) % size.X
		y := (robots[i].pos.Y + steps*robots[i].dir.Y) % size.Y

		if x < 0 {
			x += size.X
		}

		if y < 0 {
			y += size.Y
		}

		robots[i].pos = Vector{x, y}
	}
}

func countQuadrants(robots []Robot, size Vector) []int {
	var topleft Vector
	var botright Vector
	counts := []int{}

	// handle top left quadrant
	topleft = Vector{0, 0}
	botright = Vector{size.X/2 - 1, size.Y/2 - 1}
	counts = append(counts, countQuadrant(robots, topleft, botright))

	// handle top right quadrant
	topleft = Vector{size.X/2 + 1, 0}
	botright = Vector{size.X, size.Y/2 - 1}
	counts = append(counts, countQuadrant(robots, topleft, botright))

	// handle bottom left quadrant
	topleft = Vector{0, size.Y/2 + 1}
	botright = Vector{size.X/2 - 1, size.Y}
	counts = append(counts, countQuadrant(robots, topleft, botright))

	// handle bottom right quadrant
	topleft = Vector{size.X/2 + 1, size.Y/2 + 1}
	botright = Vector{size.X, size.Y}
	counts = append(counts, countQuadrant(robots, topleft, botright))

	return counts
}

func printGrid(robots []Robot, width int, height int, splitted bool) {
	grid := make([][]int, height)
	for i := range grid {
		grid[i] = make([]int, width)
		for j := range grid[i] {
			grid[i][j] = 0
		}
	}

	for _, robot := range robots {
		grid[robot.pos.Y][robot.pos.X]++
	}

	for i := range grid {
		if i == height/2 && splitted {
			fmt.Println()
			continue
		}
		for j := range grid[i] {
			if j == width/2 && splitted {
				fmt.Printf(" ")
				continue
			}
			if grid[i][j] == 0 {
				fmt.Printf(".")
			} else {
				fmt.Printf("%d", grid[i][j])
			}
		}
		fmt.Println()
	}
}

func robotsToGrid(robots []Robot, width int, height int) [][]int {
	grid := make([][]int, height)
	for i := range grid {
		grid[i] = make([]int, width)
	}

	for _, robot := range robots {
		grid[robot.pos.Y][robot.pos.X] += 1
	}

	return grid
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		robots := parse(input)

		var width int
		var height int

		args := os.Args[1:]
		if len(args) > 3 { // ex: solver 2024 02 silver |sample|
			width = 11
			height = 7
		} else {
			width = 101
			height = 103
		}

		fmt.Printf("width: %d, height: %d; robots: %d\n", width, height, len(robots))
		simulate(robots, 100, Vector{width, height})

		counts := countQuadrants(robots, Vector{width, height})

		fmt.Printf("counts: %v\n", counts)
		mul := 1
		for _, count := range counts {
			mul *= count
		}

		return fmt.Sprintf("%d", mul)
	},
	Gold: func(input string) string {
		robots := parse(input)

		var width int
		var height int

		args := os.Args[1:]
		if len(args) > 3 { // ex: solver 2024 02 silver |sample|
			width = 11
			height = 7
		} else {
			width = 101
			height = 103
		}

		// note: keep this for future reference
		// for i := 0; i < 10000; i++ {
		// 	r := make([]Robot, len(robots))
		// 	copy(r, robots)
		// 	simulate(r, i, Vector{width, height})
		// 	grid := robotsToGrid(r, width, height)

		// 	a := image.NewAlpha(image.Rect(0, 0, width, height))
		// 	for y := 0; y < height; y++ {
		// 		for x := 0; x < width; x++ {
		// 			if grid[y][x] > 0 {
		// 				a.Set(x, y, color.RGBA{255, 255, 255, 255})
		// 			}
		// 		}
		// 	}

		// 	f, _ := os.Create(fmt.Sprintf("output/%04d.png", i))
		// 	png.Encode(f, a)
		// 	f.Close()
		// }

		simulate(robots, 7916, Vector{width, height})
		grid := robotsToGrid(robots, width, height)

		a := image.NewRGBA(image.Rect(0, 0, width, height))
		for y := 0; y < height; y++ {
			for x := 0; x < width; x++ {
				if grid[y][x] > 0 {
					fmt.Printf("%d\n", grid[y][x])
				}
				if grid[y][x] == 1 {
					a.Set(x, y, color.RGBA{255, 255, 255, 255})
				}
			}
		}

		f, _ := os.Create(fmt.Sprintf("%04d.png", 7916))
		png.Encode(f, a)
		f.Close()

		return fmt.Sprintf("%d", 42)
	},
}
