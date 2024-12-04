package solution202102

import (
	"fmt"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Operation int

const (
	Forward = iota
	Down
	Up
)

type Instruction struct {
	op  Operation
	arg int
}

func opFromString(s string) Operation {
	switch s {
	case "forward":
		return Forward
	case "down":
		return Down
	case "up":
		return Up
	}

	return Forward
}

func parse(input string) []Instruction {
	result := []Instruction{}

	lines := strings.Split(input, "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}

		parts := strings.Split(line, " ")
		op := opFromString(parts[0])
		arg, _ := strconv.Atoi(parts[1])

		result = append(result, Instruction{op, arg})
	}

	return result
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		instructions := parse(input)
		horizon, depth := 0, 0

		for _, instruction := range instructions {
			switch instruction.op {
			case Forward:
				horizon += instruction.arg
			case Down:
				depth += instruction.arg
			case Up:
				depth -= instruction.arg
			}
		}

		return fmt.Sprintf("%d", depth*horizon)
	},
	Gold: func(input string) string {
		instructions := parse(input)
		horizon, depth, aim := 0, 0, 0

		for _, instruction := range instructions {
			switch instruction.op {
			case Forward:
				horizon += instruction.arg
				depth += aim * instruction.arg
			case Down:
				aim += instruction.arg
			case Up:
				aim -= instruction.arg
			}
		}

		return fmt.Sprintf("%d", horizon*depth)
	},
}
