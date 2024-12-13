package solution202413

import (
	"fmt"
	"regexp"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Vector struct {
	X int
	Y int
}

type Machine struct {
	A     Vector
	B     Vector
	Prize Vector
}

func parse(input string) []Machine {
	entries := strings.Split(input, "\n\n")

	// match X and Y of any line
	r := regexp.MustCompile(`.*(?:\+|=)(\d+),.*(?:\+|=)(\d+)`)

	machines := []Machine{}
	for _, entry := range entries {
		lines := strings.Split(entry, "\n")
		ma := r.FindStringSubmatch(lines[0])
		ax, _ := strconv.Atoi(ma[1])
		ay, _ := strconv.Atoi(ma[2])
		mb := r.FindStringSubmatch(lines[1])
		bx, _ := strconv.Atoi(mb[1])
		by, _ := strconv.Atoi(mb[2])
		mp := r.FindStringSubmatch(lines[2])
		px, _ := strconv.Atoi(mp[1])
		py, _ := strconv.Atoi(mp[2])

		machines = append(machines, Machine{
			A:     Vector{ax, ay},
			B:     Vector{bx, by},
			Prize: Vector{px, py},
		})
	}

	return machines
}

// Returns the count of A and B presses, as X and Y respectively.
func GetSolution(m Machine) Vector {
	p := (m.A.X*m.Prize.Y - m.A.Y*m.Prize.X) / (m.A.X*m.B.Y - m.A.Y*m.B.X)
	n := (m.Prize.X - p*m.B.X) / m.A.X

	return Vector{n, p}
}

func GetCost(machines []Machine, capIteration bool) int {
	cost := 0
	for _, m := range machines {
		s := GetSolution(m)

		// check the solution
		x := m.A.X*s.X + m.B.X*s.Y
		y := m.A.Y*s.X + m.B.Y*s.Y

		if s.X < 0 || s.Y < 0 || ((s.X > 100 || s.Y > 100) && capIteration) {
			continue
		}
		if x != m.Prize.X || y != m.Prize.Y {
			continue
		}

		cost += s.X*3 + s.Y
	}
	return cost
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		machines := parse(input)

		cost := GetCost(machines, true)
		return fmt.Sprintf("%d", cost)
	},
	Gold: func(input string) string {
		machines := parse(input)

		for i := range machines {
			machines[i].Prize.X += 10_000_000_000_000
			machines[i].Prize.Y += 10_000_000_000_000
		}

		cost := GetCost(machines, false)
		return fmt.Sprintf("%d", cost)
	},
}
