package solution202412

import (
	"fmt"
	"slices"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"

	"github.com/pterm/pterm"
)

func abs(a int) int {
	if a < 0 {
		return -a
	}
	return a
}

type Position struct {
	X int
	Y int
}

func (p Position) String() string {
	return fmt.Sprintf("%d;%d", p.X, p.Y)
}

func (p Position) Equals(other Position) bool {
	return p.X == other.X && p.Y == other.Y
}

type Region struct {
	plant    rune
	plots    map[string]Position
	identity string
}

func (r Region) Area() int {
	return len(r.plots)
}

func (r Region) Perimeter(garden [][]rune, width int, height int) int {
	perimeter := 0
	for _, p := range r.plots {
		if p.X == 0 || garden[p.Y][p.X-1] != r.plant {
			perimeter++
		}
		if p.X == width-1 || garden[p.Y][p.X+1] != r.plant {
			perimeter++
		}
		if p.Y == 0 || garden[p.Y-1][p.X] != r.plant {
			perimeter++
		}
		if p.Y == height-1 || garden[p.Y+1][p.X] != r.plant {
			perimeter++
		}
	}
	return perimeter
}

func (r Region) Sides(garden [][]rune, width int, height int) int {
	left := []Position{}
	right := []Position{}
	up := []Position{}
	down := []Position{}

	for _, p := range r.plots {
		if p.X == 0 || garden[p.Y][p.X-1] != r.plant {
			left = append(left, p)
		}
		if p.X == width-1 || garden[p.Y][p.X+1] != r.plant {
			right = append(right, p)
		}
		if p.Y == 0 || garden[p.Y-1][p.X] != r.plant {
			up = append(up, p)
		}
		if p.Y == height-1 || garden[p.Y+1][p.X] != r.plant {
			down = append(down, p)
		}
	}

	slices.SortFunc(left, func(a, b Position) int {
		return (a.X-b.X)*1_000 + a.Y - b.Y // sorting by X then by Y
	})

	slices.SortFunc(right, func(a, b Position) int {
		return (a.X-b.X)*1_000 + a.Y - b.Y // sorting by X then by Y
	})

	slices.SortFunc(up, func(a, b Position) int {
		return (a.Y-b.Y)*1_000 + a.X - b.X // sorting by Y then by X
	})

	slices.SortFunc(down, func(a, b Position) int {
		return (a.Y-b.Y)*1_000 + a.X - b.X // sorting by Y then by X
	})

	sides := 0
	if len(left) > 0 {
		sides++
		for i := 1; i < len(left); i++ {
			dx := abs(left[i].X - left[i-1].X)
			dy := abs(left[i].Y - left[i-1].Y)

			if dx > 0 || dy > 1 {
				sides++
			}
		}
	}
	if len(right) > 0 {
		sides++
		for i := 1; i < len(right); i++ {
			dx := abs(right[i].X - right[i-1].X)
			dy := abs(right[i].Y - right[i-1].Y)

			if dx > 0 || dy > 1 {
				sides++
			}
		}
	}
	if len(up) > 0 {
		sides++
		for i := 1; i < len(up); i++ {
			dx := abs(up[i].X - up[i-1].X)
			dy := abs(up[i].Y - up[i-1].Y)

			if dy > 0 || dx > 1 {
				sides++
			}
		}
	}
	if len(down) > 0 {
		sides++
		for i := 1; i < len(down); i++ {
			dx := abs(down[i].X - down[i-1].X)
			dy := abs(down[i].Y - down[i-1].Y)

			if dy > 0 || dx > 1 {
				sides++
			}
		}
	}

	return sides
}

func (r Region) GetIdentity() string {
	minY := -1
	for _, p := range r.plots {
		if minY == -1 || p.Y < minY {
			minY = p.Y
		}
	}

	minX := -1
	for _, p := range r.plots {
		if p.Y != minY {
			continue
		}
		if minX == -1 || p.X < minX {
			minX = p.X
		}
	}

	return fmt.Sprintf("%c-%d-%d", r.plant, minY, minX)
}

func mergeRegions(dest Region, other Region) {
	for k, v := range other.plots {
		dest.plots[k] = v
	}
}

func isPositionInSlice(p Position, slice []Position) bool {
	for _, s := range slice {
		if s.Equals(p) {
			return true
		}
	}
	return false
}

func parse(input string) [][]rune {
	var result [][]rune
	for _, line := range strings.Split(input, "\n") {
		if line == "" {
			continue
		}
		result = append(result, []rune(line))
	}
	return result
}

func explore(garden [][]rune, width int, height int, p Position, from []Position) Region {
	r := Region{
		plant: garden[p.Y][p.X],
		plots: map[string]Position{
			p.String(): p,
		},
	}

	from = append(from, p)

	// left
	if p.X > 0 && !isPositionInSlice(Position{p.X - 1, p.Y}, from) && garden[p.Y][p.X-1] == garden[p.Y][p.X] {
		neighbors := explore(garden, width, height, Position{p.X - 1, p.Y}, from)
		mergeRegions(r, neighbors)
		for _, plot := range neighbors.plots {
			from = append(from, plot)
		}
	}
	// right
	if p.X < width-1 && !isPositionInSlice(Position{p.X + 1, p.Y}, from) && garden[p.Y][p.X+1] == garden[p.Y][p.X] {
		neighbors := explore(garden, width, height, Position{p.X + 1, p.Y}, from)
		mergeRegions(r, neighbors)
		for _, plot := range neighbors.plots {
			from = append(from, plot)
		}
	}
	// up
	if p.Y > 0 && !isPositionInSlice(Position{p.X, p.Y - 1}, from) && garden[p.Y-1][p.X] == garden[p.Y][p.X] {
		neighbors := explore(garden, width, height, Position{p.X, p.Y - 1}, from)
		mergeRegions(r, neighbors)
		for _, plot := range neighbors.plots {
			from = append(from, plot)
		}
	}
	// down
	if p.Y < height-1 && !isPositionInSlice(Position{p.X, p.Y + 1}, from) && garden[p.Y+1][p.X] == garden[p.Y][p.X] {
		neighbors := explore(garden, width, height, Position{p.X, p.Y + 1}, from)
		mergeRegions(r, neighbors)
		for _, plot := range neighbors.plots {
			from = append(from, plot)
		}
	}

	r.identity = r.GetIdentity()

	return r
}

func isRegionDiscovered(r Region, regions []Region) bool {
	for _, region := range regions {
		if r.identity == region.identity {
			return true
		}
	}
	return false
}

func discover(garden [][]rune, width int, height int) []Region {
	regions := []Region{}
	p, _ := pterm.DefaultProgressbar.WithTotal(width * height).WithTitle("Updating: XXXX").Start()
	for y := 0; y < height; y++ {
		for x := 0; x < width; x++ {
			p.UpdateTitle(fmt.Sprintf("Exploring: Y: %3d; X: %3d", y, x))
			r := explore(garden, width, height, Position{x, y}, []Position{{-1, -1}})
			if !isRegionDiscovered(r, regions) {
				pterm.Success.Println(fmt.Sprintf("Region of %c", r.plant))
				regions = append(regions, r)
			}
			p.Increment()
		}
	}
	return regions
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		garden := parse(input)
		height := len(garden)
		width := len(garden[0])

		regions := discover(garden, width, height)

		total := 0
		for _, r := range regions {
			total += r.Area() * r.Perimeter(garden, width, height)
		}

		return fmt.Sprintf("%d", total)
	},
	Gold: func(input string) string {
		garden := parse(input)
		height := len(garden)
		width := len(garden[0])

		regions := discover(garden, width, height)

		total := 0
		for _, r := range regions {
			total += r.Area() * r.Sides(garden, width, height)
		}

		return fmt.Sprintf("%d", total)
	},
}
